@echo off
cd /d "%~dp0"
php artisan queue:work redis --queue=default --sleep=3 --tries=3 --timeout=120
