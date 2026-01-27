#!/usr/bin/env python3
"""
Pinterest to WordPress Recipe Automation
Analyzes cooking videos from Pinterest using Molmo AI and posts recipes to WordPress
"""

import os
import sys
import json
import time
import requests
import base64
from datetime import datetime
from typing import List, Dict, Optional
from dotenv import load_dotenv
from pathlib import Path
import logging

# Setup logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(levelname)s - %(message)s',
    handlers=[
        logging.FileHandler('recipe_automation.log'),
        logging.StreamHandler(sys.stdout)
    ]
)
logger = logging.getLogger(__name__)

# Load environment variables
load_dotenv()

class PinterestClient:
    """Client for interacting with Pinterest API"""
    
    def __init__(self, access_token: str):
        self.access_token = access_token
        self.base_url = "https://api.pinterest.com/v5"
        self.headers = {
            "Authorization": f"Bearer {access_token}",
            "Content-Type": "application/json"
        }
    
    def get_user_pins(self, username: str, limit: int = 25) -> List[Dict]:
        """Fetch pins from user's account"""
        try:
            # Get user boards first
            url = f"{self.base_url}/boards"
            response = requests.get(url, headers=self.headers)
            response.raise_for_status()
            
            boards = response.json().get('items', [])
            logger.info(f"Found {len(boards)} boards")
            
            all_pins = []
            for board in boards[:5]:  # Get pins from first 5 boards
                board_id = board.get('id')
                pins_url = f"{self.base_url}/boards/{board_id}/pins"
                params = {"page_size": limit}
                
                pins_response = requests.get(pins_url, headers=self.headers, params=params)
                if pins_response.status_code == 200:
                    pins = pins_response.json().get('items', [])
                    all_pins.extend(pins)
                    logger.info(f"Fetched {len(pins)} pins from board: {board.get('name')}")
                
                time.sleep(0.5)  # Rate limiting
            
            return all_pins
        except Exception as e:
            logger.error(f"Error fetching Pinterest pins: {e}")
            return []
    
    def download_pin_image(self, image_url: str, save_path: str) -> bool:
        """Download image from Pinterest"""
        try:
            response = requests.get(image_url, stream=True)
            response.raise_for_status()
            
            with open(save_path, 'wb') as f:
                for chunk in response.iter_content(chunk_size=8192):
                    f.write(chunk)
            
            logger.info(f"Downloaded image to {save_path}")
            return True
        except Exception as e:
            logger.error(f"Error downloading image: {e}")
            return False


class MolmoAI:
    """Client for OpenRouter Molmo AI vision analysis"""
    
    def __init__(self, api_key: str, model: str = "allenai/molmo-7b-d-0924"):
        self.api_key = api_key
        self.model = model
        self.base_url = "https://openrouter.ai/api/v1/chat/completions"
    
    def encode_image(self, image_path: str) -> str:
        """Encode image to base64"""
        with open(image_path, "rb") as image_file:
            return base64.b64encode(image_file.read()).decode('utf-8')
    
    def analyze_cooking_image(self, image_url: str) -> Optional[Dict]:
        """Analyze cooking image and extract recipe details"""
        try:
            prompt = """Analyze this cooking image and extract a detailed recipe in the following JSON format:

{
  "title": "Recipe name",
  "description": "Brief description (2-3 sentences)",
  "cuisine": "Type of cuisine",
  "difficulty": "Easy/Medium/Hard",
  "prep_time": "preparation time in minutes",
  "cook_time": "cooking time in minutes",
  "servings": "number of servings",
  "ingredients": [
    "ingredient 1 with quantity",
    "ingredient 2 with quantity"
  ],
  "instructions": [
    "Step 1 detailed instruction",
    "Step 2 detailed instruction"
  ],
  "tips": ["helpful tip 1", "helpful tip 2"],
  "tags": ["tag1", "tag2", "tag3"]
}

Be detailed and specific. If you can't determine exact values, make reasonable estimates based on what you see in the image."""

            headers = {
                "Authorization": f"Bearer {self.api_key}",
                "Content-Type": "application/json",
                "HTTP-Referer": "https://cookshaheen.com",
                "X-Title": "CookShaheen Recipe Automation"
            }
            
            payload = {
                "model": self.model,
                "messages": [
                    {
                        "role": "user",
                        "content": [
                            {
                                "type": "text",
                                "text": prompt
                            },
                            {
                                "type": "image_url",
                                "image_url": {
                                    "url": image_url
                                }
                            }
                        ]
                    }
                ],
                "temperature": 0.7,
                "max_tokens": 2000
            }
            
            response = requests.post(self.base_url, headers=headers, json=payload)
            response.raise_for_status()
            
            result = response.json()
            content = result['choices'][0]['message']['content']
            
            # Extract JSON from response
            try:
                # Try to parse as JSON
                if '```json' in content:
                    json_str = content.split('```json')[1].split('```')[0].strip()
                elif '```' in content:
                    json_str = content.split('```')[1].split('```')[0].strip()
                else:
                    json_str = content.strip()
                
                recipe_data = json.loads(json_str)
                logger.info(f"Successfully analyzed recipe: {recipe_data.get('title', 'Unknown')}")
                return recipe_data
            except json.JSONDecodeError as e:
                logger.error(f"Failed to parse JSON from Molmo response: {e}")
                logger.debug(f"Raw content: {content}")
                return None
                
        except Exception as e:
            logger.error(f"Error analyzing image with Molmo AI: {e}")
            return None


class WordPressClient:
    """Client for posting to WordPress via REST API"""
    
    def __init__(self, url: str, username: str, app_password: str):
        self.url = url.rstrip('/')
        self.username = username
        self.app_password = app_password
        self.api_base = f"{self.url}/wp-json/wp/v2"
        
        # Create auth header
        credentials = f"{username}:{app_password}"
        token = base64.b64encode(credentials.encode()).decode()
        self.headers = {
            "Authorization": f"Basic {token}",
            "Content-Type": "application/json"
        }
    
    def upload_image(self, image_path: str, title: str) -> Optional[int]:
        """Upload image to WordPress media library"""
        try:
            url = f"{self.api_base}/media"
            
            with open(image_path, 'rb') as img:
                files = {
                    'file': (os.path.basename(image_path), img, 'image/jpeg')
                }
                headers = {
                    "Authorization": self.headers["Authorization"]
                }
                
                response = requests.post(url, headers=headers, files=files)
                response.raise_for_status()
                
                media_id = response.json()['id']
                logger.info(f"Uploaded image, media ID: {media_id}")
                return media_id
        except Exception as e:
            logger.error(f"Error uploading image to WordPress: {e}")
            return None
    
    def create_recipe_post(self, recipe_data: Dict, image_id: Optional[int] = None, 
                          publish: bool = False) -> Optional[int]:
        """Create a recipe post in WordPress"""
        try:
            # Build HTML content
            content = self._build_recipe_html(recipe_data)
            
            post_data = {
                "title": recipe_data.get('title', 'Untitled Recipe'),
                "content": content,
                "status": "publish" if publish else "draft",
                "featured_media": image_id if image_id else None,
                "categories": [1],  # Default category, adjust as needed
                "tags": recipe_data.get('tags', [])
            }
            
            url = f"{self.api_base}/posts"
            response = requests.post(url, headers=self.headers, json=post_data)
            response.raise_for_status()
            
            post_id = response.json()['id']
            post_url = response.json()['link']
            logger.info(f"Created recipe post: {post_url}")
            return post_id
            
        except Exception as e:
            logger.error(f"Error creating WordPress post: {e}")
            return None
    
    def _build_recipe_html(self, recipe: Dict) -> str:
        """Build HTML content for recipe post"""
        html = f"""
<div class="recipe-content">
    <div class="recipe-meta">
        <p><strong>Cuisine:</strong> {recipe.get('cuisine', 'Various')}</p>
        <p><strong>Difficulty:</strong> {recipe.get('difficulty', 'Medium')}</p>
        <p><strong>Prep Time:</strong> {recipe.get('prep_time', 'N/A')} minutes</p>
        <p><strong>Cook Time:</strong> {recipe.get('cook_time', 'N/A')} minutes</p>
        <p><strong>Servings:</strong> {recipe.get('servings', 'N/A')}</p>
    </div>
    
    <div class="recipe-description">
        <p>{recipe.get('description', '')}</p>
    </div>
    
    <div class="recipe-ingredients">
        <h3>🥘 Ingredients</h3>
        <ul>
"""
        
        for ingredient in recipe.get('ingredients', []):
            html += f"            <li>{ingredient}</li>\n"
        
        html += """        </ul>
    </div>
    
    <div class="recipe-instructions">
        <h3>👨‍🍳 Instructions</h3>
        <ol>
"""
        
        for step in recipe.get('instructions', []):
            html += f"            <li>{step}</li>\n"
        
        html += """        </ol>
    </div>
"""
        
        if recipe.get('tips'):
            html += """    
    <div class="recipe-tips">
        <h3>💡 Tips</h3>
        <ul>
"""
            for tip in recipe.get('tips', []):
                html += f"            <li>{tip}</li>\n"
            
            html += """        </ul>
    </div>
"""
        
        html += """
    <div class="recipe-footer">
        <p><em>Recipe analyzed and generated from cooking images using AI. Adjust quantities and cooking times based on your preferences.</em></p>
    </div>
</div>
"""
        return html


class RecipeAutomation:
    """Main automation orchestrator"""
    
    def __init__(self):
        self.pinterest = PinterestClient(os.getenv('PINTEREST_ACCESS_TOKEN'))
        self.molmo = MolmoAI(
            os.getenv('OPENROUTER_API_KEY'),
            os.getenv('MOLMO_MODEL', 'allenai/molmo-7b-d-0924')
        )
        self.wordpress = WordPressClient(
            os.getenv('WORDPRESS_URL'),
            os.getenv('WORDPRESS_USERNAME'),
            os.getenv('WORDPRESS_APP_PASSWORD')
        )
        
        self.images_dir = Path('downloaded_images')
        self.images_dir.mkdir(exist_ok=True)
        
        self.processed_pins_file = Path('processed_pins.json')
        self.processed_pins = self._load_processed_pins()
    
    def _load_processed_pins(self) -> set:
        """Load list of already processed pin IDs"""
        if self.processed_pins_file.exists():
            with open(self.processed_pins_file, 'r') as f:
                return set(json.load(f))
        return set()
    
    def _save_processed_pin(self, pin_id: str):
        """Save processed pin ID"""
        self.processed_pins.add(pin_id)
        with open(self.processed_pins_file, 'w') as f:
            json.dump(list(self.processed_pins), f)
    
    def process_pins(self, limit: int = 10, dry_run: bool = False):
        """Process pins from Pinterest and create WordPress posts"""
        logger.info(f"Starting recipe automation (limit: {limit}, dry_run: {dry_run})")
        
        # Fetch pins from Pinterest
        pins = self.pinterest.get_user_pins(
            os.getenv('PINTEREST_USERNAME', 'cookshaheen'),
            limit=limit * 2  # Fetch more to account for already processed
        )
        
        if not pins:
            logger.warning("No pins found from Pinterest")
            return
        
        processed_count = 0
        
        for pin in pins:
            if processed_count >= limit:
                break
            
            pin_id = pin.get('id')
            if pin_id in self.processed_pins:
                logger.info(f"Skipping already processed pin: {pin_id}")
                continue
            
            # Get image URL
            image_url = None
            media = pin.get('media', {})
            if 'images' in media:
                image_url = media['images'].get('originals', {}).get('url') or \
                           media['images'].get('600x', {}).get('url')
            
            if not image_url:
                logger.warning(f"No image URL found for pin: {pin_id}")
                continue
            
            logger.info(f"Processing pin: {pin_id}")
            
            # Analyze image with Molmo AI
            recipe_data = self.molmo.analyze_cooking_image(image_url)
            
            if not recipe_data:
                logger.warning(f"Failed to analyze pin: {pin_id}")
                continue
            
            # Download image
            image_filename = f"{pin_id}.jpg"
            image_path = self.images_dir / image_filename
            
            if not self.pinterest.download_pin_image(str(image_url), str(image_path)):
                logger.warning(f"Failed to download image for pin: {pin_id}")
                image_path = None
            
            # Post to WordPress
            if not dry_run:
                image_id = None
                if image_path and image_path.exists():
                    image_id = self.wordpress.upload_image(
                        str(image_path),
                        recipe_data.get('title', 'Recipe Image')
                    )
                
                auto_publish = os.getenv('AUTO_PUBLISH', 'false').lower() == 'true'
                post_id = self.wordpress.create_recipe_post(
                    recipe_data,
                    image_id,
                    publish=auto_publish
                )
                
                if post_id:
                    self._save_processed_pin(pin_id)
                    processed_count += 1
                    logger.info(f"✅ Successfully processed pin {pin_id}")
                else:
                    logger.error(f"❌ Failed to create WordPress post for pin {pin_id}")
            else:
                logger.info(f"[DRY RUN] Would create post: {recipe_data.get('title')}")
                processed_count += 1
            
            # Rate limiting
            time.sleep(2)
        
        logger.info(f"Automation complete. Processed {processed_count} recipes.")


def main():
    """Main entry point"""
    import argparse
    
    parser = argparse.ArgumentParser(description='Pinterest to WordPress Recipe Automation')
    parser.add_argument('--limit', type=int, default=10, help='Maximum recipes to process')
    parser.add_argument('--dry-run', action='store_true', help='Test without posting to WordPress')
    
    args = parser.parse_args()
    
    # Validate environment variables
    required_vars = [
        'PINTEREST_ACCESS_TOKEN',
        'OPENROUTER_API_KEY',
        'WORDPRESS_URL',
        'WORDPRESS_USERNAME',
        'WORDPRESS_APP_PASSWORD'
    ]
    
    missing_vars = [var for var in required_vars if not os.getenv(var)]
    if missing_vars:
        logger.error(f"Missing required environment variables: {', '.join(missing_vars)}")
        logger.error("Please create a .env file with the required variables. See .env.example")
        sys.exit(1)
    
    # Run automation
    automation = RecipeAutomation()
    automation.process_pins(limit=args.limit, dry_run=args.dry_run)


if __name__ == '__main__':
    main()
