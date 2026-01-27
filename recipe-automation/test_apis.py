#!/usr/bin/env python3
"""
Test script to verify API connections
"""

import os
import sys
from dotenv import load_dotenv
import requests
import base64

load_dotenv()

def test_pinterest():
    """Test Pinterest API connection"""
    print("\n🔍 Testing Pinterest API...")
    token = os.getenv('PINTEREST_ACCESS_TOKEN')
    
    if not token or token == 'your_pinterest_access_token_here':
        print("❌ Pinterest token not configured")
        return False
    
    try:
        headers = {"Authorization": f"Bearer {token}"}
        response = requests.get("https://api.pinterest.com/v5/user_account", headers=headers)
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ Pinterest API connected!")
            print(f"   Username: {data.get('username', 'N/A')}")
            return True
        else:
            print(f"❌ Pinterest API error: {response.status_code}")
            print(f"   {response.text}")
            return False
    except Exception as e:
        print(f"❌ Pinterest connection failed: {e}")
        return False


def test_openrouter():
    """Test OpenRouter API connection"""
    print("\n🔍 Testing OpenRouter API...")
    api_key = os.getenv('OPENROUTER_API_KEY')
    
    if not api_key or api_key == 'your_openrouter_api_key_here':
        print("❌ OpenRouter API key not configured")
        return False
    
    try:
        headers = {
            "Authorization": f"Bearer {api_key}",
            "Content-Type": "application/json"
        }
        
        # Test with a simple request
        payload = {
            "model": "allenai/molmo-7b-d-0924",
            "messages": [{"role": "user", "content": "Hello"}],
            "max_tokens": 10
        }
        
        response = requests.post(
            "https://openrouter.ai/api/v1/chat/completions",
            headers=headers,
            json=payload
        )
        
        if response.status_code == 200:
            print("✅ OpenRouter API connected!")
            print("   Molmo model is accessible")
            return True
        else:
            print(f"❌ OpenRouter API error: {response.status_code}")
            print(f"   {response.text}")
            return False
    except Exception as e:
        print(f"❌ OpenRouter connection failed: {e}")
        return False


def test_wordpress():
    """Test WordPress API connection"""
    print("\n🔍 Testing WordPress API...")
    url = os.getenv('WORDPRESS_URL')
    username = os.getenv('WORDPRESS_USERNAME')
    password = os.getenv('WORDPRESS_APP_PASSWORD')
    
    if not all([url, username, password]) or password == 'your_wordpress_app_password':
        print("❌ WordPress credentials not configured")
        return False
    
    try:
        credentials = f"{username}:{password}"
        token = base64.b64encode(credentials.encode()).decode()
        headers = {"Authorization": f"Basic {token}"}
        
        response = requests.get(f"{url.rstrip('/')}/wp-json/wp/v2/users/me", headers=headers)
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ WordPress API connected!")
            print(f"   Site: {url}")
            print(f"   User: {data.get('name', username)}")
            return True
        else:
            print(f"❌ WordPress API error: {response.status_code}")
            print(f"   {response.text}")
            return False
    except Exception as e:
        print(f"❌ WordPress connection failed: {e}")
        return False


def main():
    print("=" * 60)
    print("🍳 CookShaheen Recipe Automation - API Test")
    print("=" * 60)
    
    results = {
        'Pinterest': test_pinterest(),
        'OpenRouter': test_openrouter(),
        'WordPress': test_wordpress()
    }
    
    print("\n" + "=" * 60)
    print("📊 Test Results Summary")
    print("=" * 60)
    
    for service, status in results.items():
        icon = "✅" if status else "❌"
        print(f"{icon} {service}: {'PASS' if status else 'FAIL'}")
    
    all_passed = all(results.values())
    
    print("\n" + "=" * 60)
    if all_passed:
        print("🎉 All tests passed! You're ready to run the automation.")
        print("\nNext step: python recipe_automation.py --dry-run")
    else:
        print("⚠️  Some tests failed. Please check your .env configuration.")
        print("\nSee API_SETUP.md for detailed setup instructions.")
    print("=" * 60 + "\n")
    
    sys.exit(0 if all_passed else 1)


if __name__ == '__main__':
    main()
