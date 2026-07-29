@echo off
set REDIS_DIR=C:\laragon\bin\redis\redis-x64-5.0.14.1

tasklist | findstr /I redis-server.exe >nul
if %ERRORLEVEL%==0 (
    echo Redis is already running.
    exit /b 0
)

echo Starting Redis...
start "Redis Server" "%REDIS_DIR%\redis-server.exe" "%REDIS_DIR%\redis.windows.conf"
