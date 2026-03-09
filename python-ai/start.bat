@echo off
cd /d "%~dp0"
echo Installing dependencies...
pip install -r requirements.txt
echo.
echo Starting Medix AI Service on http://localhost:8001 ...
python main.py
pause
