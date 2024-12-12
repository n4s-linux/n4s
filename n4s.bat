@echo off
setlocal

REM Define the target directory
set TARGET_DIR=C:\n4s

REM Define the file URLs
set FILE1_URL=https://raw.githubusercontent.com/n4s-linux/n4s/main/ac14.conf
set FILE2_URL=https://raw.githubusercontent.com/n4s-linux/n4s/main/ac14.exe

REM Define the file paths
set FILE1=%TARGET_DIR%\ac14.conf
set FILE2=%TARGET_DIR%\ac14.exe

REM Check if a reboot is required
reg query "HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\RebootRequired" >nul 2>&1
if not errorlevel 1 (
    echo [ERROR] A system reboot is required. Please reboot your system and rerun this script.
    pause
    exit /b 1
)

REM Check if Docker is installed
docker --version >nul 2>&1
if errorlevel 1 (
    echo [INFO] Docker is not installed. Installing Docker Desktop...

    REM Download Docker Desktop Installer
    set DOCKER_INSTALLER_URL=https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe
    set INSTALLER_PATH=%TEMP%\DockerDesktopInstaller.exe

    echo [INFO] Downloading Docker Desktop Installer...
    bitsadmin /transfer DockerDesktopInstaller /priority high "%DOCKER_INSTALLER_URL%" "%INSTALLER_PATH%"
    if errorlevel 1 (
        echo [ERROR] Failed to download Docker Desktop Installer. Please check your internet connection.
        pause
        exit /b 1
    )

    REM Run the installer silently
    echo [INFO] Running Docker Desktop Installer silently...
    "%INSTALLER_PATH%" install --quiet --accept-license
    if errorlevel 1 (
        echo [ERROR] Failed to install Docker Desktop. Please try installing it manually.
        pause
        exit /b 1
    )

    REM Cleanup installer
    echo [INFO] Cleaning up installer...
    del "%INSTALLER_PATH%"

    REM Check if a reboot is required after installation
    reg query "HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\RebootRequired" >nul 2>&1
    if not errorlevel 1 (
        echo [INFO] Docker Desktop has been installed successfully, but a reboot is required.
        echo [INFO] Please reboot your system and rerun this script.
        pause
        exit /b 1
    )
)

REM Create the target directory if it doesn't exist
if not exist "%TARGET_DIR%" (
    mkdir "%TARGET_DIR%"
)

REM Download ac14.conf if it doesn't exist
if not exist "%FILE1%" (
    echo Downloading ac14.conf...
    curl -o "%FILE1%" "%FILE1_URL%"
    if %errorlevel% neq 0 (
        echo Failed to download ac14.conf
        exit /b 1
    )
)

REM Download ac14.exe if it doesn't exist
if not exist "%FILE2%" (
    echo Downloading ac14.exe...
    curl -o "%FILE2%" "%FILE2_URL%"
    if %errorlevel% neq 0 (
        echo Failed to download ac14.exe
        exit /b 1
    )
)

REM Relaunch in fullscreen if not already in fullscreen mode
if "%1" neq "fullscreen" (
    start "" "%FILE2%" --config-file "%FILE1%" --title "n4s" -e "%~f0" fullscreen
    exit /b
)

REM Set up Docker containers and image
set APP_CONTAINER=n4sd
set DATA_CONTAINER=n4s-storage
set IMAGE_NAME=olsencorp/odocker:latest

REM Ensure the data container exists
echo [INFO] Checking if the data container exists...
docker ps -a --filter "name=%DATA_CONTAINER%" --format "{{.ID}}" >nul
if %errorlevel% neq 0 (
    echo [INFO] Creating the data container...
    docker create --name %DATA_CONTAINER% -v /n4s-storage busybox
)

REM Start the application container
docker ps --filter "name=%APP_CONTAINER%" --format "{{.ID}}" >nul
if %errorlevel% neq 0 (
    echo [INFO] Starting application container...
    docker run -dit --name %APP_CONTAINER% --volumes-from %DATA_CONTAINER% %IMAGE_NAME%
)

REM Attach to the application container
docker exec -it --user n4s %APP_CONTAINER% bash

REM Launch ac14.exe
cd "%TARGET_DIR%"
echo Launching ac14.exe with ac14.conf...
start "" "%FILE2%" --config-file "%FILE1%"

exit /b 0