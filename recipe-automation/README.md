# Pinterest to WordPress Recipe Automation

Automated system to analyze cooking videos from Pinterest using Molmo AI and post recipes to WordPress.

## Features
- Fetch cooking videos/pins from Pinterest
- Analyze videos using OpenRouter Molmo AI
- Extract recipe details (ingredients, steps, cooking time)
- Auto-post to WordPress with proper formatting
- Image downloads and optimization

## Setup

1. Install dependencies:
```bash
pip install -r requirements.txt
```

2. Configure API keys in `.env`:
```
PINTEREST_ACCESS_TOKEN=your_pinterest_token
OPENROUTER_API_KEY=your_openrouter_key
WORDPRESS_URL=https://cookshaheen.com
WORDPRESS_USERNAME=your_wp_username
WORDPRESS_APP_PASSWORD=your_wp_app_password
```

3. Run the automation:
```bash
python recipe_automation.py
```

## Usage

- `--fetch-all`: Fetch all pins from Pinterest board
- `--limit N`: Process only N videos
- `--dry-run`: Test without posting to WordPress
