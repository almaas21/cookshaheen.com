@echo off
echo 🍳 CookShaheen Recipe Automation
echo.

REM Check if virtual environment exists
if not exist "venv\" (
    echo Creating virtual environment...
    python -m venv venv
)

REM Activate virtual environment
call venv\Scripts\activate.bat

REM Check if .env exists
if not exist ".env" (
    echo ⚠️  .env file not found!
    echo Please copy .env.example to .env and configure your API keys
    pause
    exit /b 1
)

REM Run the automation
echo Starting recipe automation...
python recipe_automation.py %*

pause
