import os
import logging
from dotenv import load_dotenv
from recipe_automation import WordPressClient

# Configure logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

def post_gajar_halwa():
    # Load environment variables
    load_dotenv()
    
    wp_url = os.getenv('WORDPRESS_URL')
    wp_username = os.getenv('WORDPRESS_USERNAME')
    wp_app_password = os.getenv('WORDPRESS_APP_PASSWORD')

    if not all([wp_url, wp_username, wp_app_password]):
        logger.error("Missing WordPress configuration in .env")
        return

    wp = WordPressClient(wp_url, wp_username, wp_app_password)

    recipe_data = {
        "title": "Authentic Gajar Ka Halwa (Slow Cooked Carrot Pudding)",
        "cuisine": "Indian",
        "difficulty": "Medium",
        "prep_time": "15",
        "cook_time": "90",
        "servings": "6-8",
        "description": "A traditional, slow-cooked Gajar ka Halwa made with fresh red carrots, full cream milk, khoya, and dry fruits. No pressure cooker shortcuts, just patience and authentic taste. This recipe brings the magic of slow cooking to your kitchen, resulting in a rich, melt-in-the-mouth texture.",
        "ingredients": [
            "1 kg Fresh Red Carrots (grated)",
            "0.5 Liter Full Cream Milk",
            "400g Sugar (adjust to taste)",
            "250g Khoya (Mawa)",
            "50g Cashews",
            "50g Makhana (Foxnuts)",
            "Desi Ghee (as needed for cooking, sauteing and tempering)",
            "1/2 tsp Cardamom Powder",
            "2 Green Cardamom Pods",
            "1 Clove"
        ],
        "instructions": [
            "Take a heavy bottom pan and add all the grated carrots to it.",
            "Add 0.5 liter of full cream milk and cook on low heat. Avoid using a pressure cooker for the best texture.",
            "Let the carrots and milk cook slowly on low flame, stirring occasionally, until all the milk is absorbed and the mixture becomes dry.",
            "Add Desi Ghee to the pan and mix well to bring out the aroma.",
            "Add 400g of sugar. Note that the halwa will release some water after adding sugar and its texture will loosen.",
            "Cook for another 2-4 minutes until the sugar water dries up.",
            "Add 1/2 tsp of cardamom powder (elaichi powder) for a beautiful fragrance.",
            "Take 250g of Khoya, crumble it by hand, and add most of it to the pan (save a little for garnish). Mash and mix well so no lumps remain.",
            "Add 50g of cashews and sauté for 2-3 minutes on low flame to allow the flavors to meld.",
            "Add another spoonful of Desi Ghee at this stage for a professional shine and extra flavor.",
            "For the premium touch, prepare a tempering: Heat a little Desi Ghee in a small ladle, add 2 cardamom pods and 1 clove. Pour this over the halwa and mix.",
            "In a separate pan, fry 50g of makhana in a little Desi Ghee for 2-3 minutes until they become crunchy.",
            "Plate the warm halwa, garnish with the saved khoya, cashews, and the crunchy fried makhana."
        ],
        "tips": [
            "Slow cooking is the secret to authentic Gajar ka Halwa; jangan give in to 'fast' shortcuts.",
            "Always add sugar at the end as it prevents the carrots from softening further.",
            "Manual grating of carrots gives a better texture than using a food processor.",
            "Full cream milk is essential for that rich, creamy grainy texture."
        ],
        "tags": ["Gajar Ka Halwa", "Carrot Halwa", "Indian Dessert", "Traditional Recipe", "Sweet Dish"]
    }

    # Image path (update this with the actual generated image path)
    image_path = r"C:\Users\Syed_Parvez\.gemini\antigravity\brain\de3045d4-7451-4b10-b4c9-d3c49556711f\gajar_ka_halwa_top_view_1769708529401.png"
    
    try:
        # Upload image
        logger.info("Uploading featured image...")
        image_id = wp.upload_image(image_path, "Gajar Ka Halwa")
        
        if image_id:
            logger.info(f"Image uploaded with ID: {image_id}")
        else:
            logger.warning("Image upload failed, proceeding without featured image.")

        # Create post (setting publish=True as requested by 'add a recipe')
        logger.info("Creating WordPress post...")
        post_id = wp.create_recipe_post(recipe_data, image_id=image_id, publish=True)
        
        if post_id:
            logger.info(f"Successfully posted recipe! Post ID: {post_id}")
        else:
            logger.error("Failed to create WordPress post.")
            
    except Exception as e:
        logger.error(f"An error occurred: {e}")

if __name__ == "__main__":
    post_gajar_halwa()
