@echo off
title Indian Railways Portal (Laravel) - Group 10 GMCA
color 0B
set "PATH=C:\Users\vaibh\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe;%PATH%"
cd /d "%~dp0"
echo Starting Laravel Server on http://127.0.0.1:8000 ...
start "" "http://127.0.0.1:8000"
php artisan serve --host=127.0.0.1 --port=8000
pause
