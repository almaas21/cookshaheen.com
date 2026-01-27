# API Keys Setup Guide

## 1. Pinterest Access Token

### Step 1: Create Pinterest App
1. Go to https://developers.pinterest.com/apps/
2. Click "Create App"
3. Fill in app details:
   - **App name**: CookShaheen Recipe Automation
   - **Description**: Automated recipe posting from Pinterest
   - **Website**: https://cookshaheen.com

### Step 2: Get Access Token
1. In your app dashboard, go to "OAuth"
2. Add redirect URI: `https://cookshaheen.com/auth/pinterest`
3. Note your **App ID** and **App Secret**
4. Generate access token with scopes:
   - `boards:read`
   - `pins:read`
   - `user_accounts:read`
5. Copy the access token to `.env` file

---

## 2. OpenRouter API Key

### Step 1: Create Account
1. Go to https://openrouter.ai/
2. Sign up or log in with Google/GitHub

### Step 2: Get API Key
1. Go to https://openrouter.ai/keys
2. Click "Create Key"
3. Name it: "CookShaheen Recipe Automation"
4. Copy the API key to `.env` file

### Step 3: Add Credits
1. Go to https://openrouter.ai/credits
2. Add $5-10 for testing (Molmo model is very affordable)
3. Monitor usage at https://openrouter.ai/activity

**Cost**: ~$0.01-0.05 per image analysis

---

## 3. WordPress Application Password

### Step 1: Create App Password
1. Log in to WordPress admin: https://cookshaheen.com/wp-admin
2. Go to **Users → Profile**
3. Scroll to "Application Passwords" section
4. Enter name: "Recipe Automation"
5. Click "Add New Application Password"
6. **IMPORTANT**: Copy the password immediately (you can't see it again)

### Step 2: Configure .env
```
WORDPRESS_URL=https://cookshaheen.com
WORDPRESS_USERNAME=your_wp_username
WORDPRESS_APP_PASSWORD=xxxx xxxx xxxx xxxx xxxx xxxx
```

---

## Quick Setup Checklist

- [ ] Pinterest Access Token obtained
- [ ] OpenRouter API key created and funded
- [ ] WordPress Application Password generated
- [ ] All keys added to `.env` file
- [ ] Dependencies installed: `pip install -r requirements.txt`
- [ ] Dry run successful: `python recipe_automation.py --dry-run`

---

## Testing

### Test 1: Dry Run
```bash
python recipe_automation.py --dry-run --limit 2
```
This will fetch pins and analyze them WITHOUT posting to WordPress.

### Test 2: Single Recipe
```bash
python recipe_automation.py --limit 1
```
This will process 1 recipe and create a draft post in WordPress.

### Test 3: Batch Processing
```bash
python recipe_automation.py --limit 10
```
Process 10 recipes at once.

---

## Troubleshooting

### Pinterest API Issues
- Check token hasn't expired
- Verify scopes are correct
- Ensure cookshaheen Pinterest account is accessible

### OpenRouter Issues
- Check API key is valid
- Verify you have credits
- Monitor rate limits

### WordPress Issues
- Verify Application Password is correct (no typos)
- Check WordPress REST API is enabled
- Test with `curl`:
  ```bash
  curl -u "username:app_password" https://cookshaheen.com/wp-json/wp/v2/posts
  ```

---

## Automation Schedule

### Option 1: Manual Run
Run whenever you want to add new recipes:
```bash
python recipe_automation.py --limit 5
```

### Option 2: Scheduled Task (Windows)
Create a scheduled task to run daily:
1. Open Task Scheduler
2. Create Basic Task
3. Set trigger (e.g., daily at 9 AM)
4. Action: Run `run.bat`

### Option 3: Cron Job (Linux)
```bash
# Add to crontab (run daily at 9 AM)
0 9 * * * cd /path/to/recipe-automation && python recipe_automation.py --limit 5
```
