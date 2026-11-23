@echo off
title Indian Railways Portal (Laravel 11/12) - Group 10 GMCA
color 0B
echo ===============================================================================
echo       INDIAN RAILWAYS MULTI-DIVISION PORTAL (LARAVEL MVC MIGRATION)
echo               Government MCA College, Maninagar (GMCA)
echo                    Group 10 - Academic Year 2025-2026
echo ===============================================================================
echo.
echo [1] Checking PHP and Environment...
set "PATH=C:\Users\vaibh\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe;%PATH%"

cd /d "%~dp0laravel"
if not exist "database\database.sqlite" (
    echo [2] Initializing SQLite database...
    type nul > "database\database.sqlite"
    php artisan migrate --force
    php artisan db:seed --force
)

echo [3] Starting Laravel Development Server on http://127.0.0.1:8000 ...
start "" "http://127.0.0.1:8000"
php artisan serve --host=127.0.0.1 --port=8000
pause
