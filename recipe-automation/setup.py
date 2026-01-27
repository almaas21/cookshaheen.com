#!/usr/bin/env python3
"""
Quick setup script for recipe automation
"""

import os
import subprocess
import sys
from pathlib import Path

def main():
    print("🍳 CookShaheen Recipe Automation Setup\n")
    
    # Check Python version
    if sys.version_info < (3, 8):
        print("❌ Python 3.8 or higher is required")
        sys.exit(1)
    
    print("✅ Python version OK")
    
    # Create virtual environment
    if not Path('venv').exists():
        print("\n📦 Creating virtual environment...")
        subprocess.run([sys.executable, '-m', 'venv', 'venv'])
        print("✅ Virtual environment created")
    
    # Determine pip path
    if os.name == 'nt':  # Windows
        pip_path = Path('venv/Scripts/pip.exe')
        python_path = Path('venv/Scripts/python.exe')
    else:  # Unix-like
        pip_path = Path('venv/bin/pip')
        python_path = Path('venv/bin/python')
    
    # Install dependencies
    print("\n📚 Installing dependencies...")
    subprocess.run([str(pip_path), 'install', '-r', 'requirements.txt'])
    print("✅ Dependencies installed")
    
    # Setup .env file
    if not Path('.env').exists():
        print("\n⚙️ Setting up configuration...")
        subprocess.run(['cp', '.env.example', '.env'])
        print("✅ Created .env file")
        print("\n⚠️  IMPORTANT: Edit .env file and add your API keys:")
        print("   - Pinterest Access Token")
        print("   - OpenRouter API Key")
        print("   - WordPress credentials")
    
    print("\n✨ Setup complete!")
    print("\n📖 Next steps:")
    print("   1. Edit .env file with your API keys")
    print("   2. Activate virtual environment:")
    if os.name == 'nt':
        print("      venv\\Scripts\\activate")
    else:
        print("      source venv/bin/activate")
    print("   3. Run: python recipe_automation.py --dry-run")
    print("   4. When ready: python recipe_automation.py --limit 5")

if __name__ == '__main__':
    main()
