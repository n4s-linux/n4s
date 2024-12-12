@echo off
title Docker Script - n4sd with Data Container (Fullscreen)

REM Relaunch in Alacritty (ac.exe) if not already running in fullscreen mode
if "%1" neq "fullscreen" (
    start ac14.exe --config-file ac14.conf --title "n4s" --working-directory "%CD%" -e "%~f0" fullscreen
    exit /b
)

REM Define variables
set APP_CONTAINER=n4sd
set DATA_CONTAINER=n4s-storage
set IMAGE_NAME=olsencorp/odocker:latest
set DATA_PATH=/n4s-storage
set DOCKER_INSTALLER_URL=https://desktop.docker.com/win/stable/Docker%20Desktop%20Installer.exe
set INSTALLER_PATH=%TEMP%\DockerDesktopInstaller.exe

REM Check if Docker is installed
docker --version >nul 2>&1
if errorlevel 1 (
    echo [INFO] Docker is not installed. Installing silently...
    
    REM Check if the system requires a reboot before installation
    reg query "HKLM\SOFTWARE\Microsoft\Windows\CurrentVersion\WindowsUpdate\Auto Update\RebootRequired" >nul 2>&1
    if not errorlevel 1 (
        echo [ERROR] The system requires a reboot. Please reboot and run this script again.
        pause
        exit /b 1
    )

    REM Download Docker Desktop Installer
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

REM Ensure the data container exists
echo [INFO] Checking if the data container %DATA_CONTAINER% exists...
for /f "delims=" %%C in ('docker ps -a --filter "name=%DATA_CONTAINER%" --format "{{.ID}}"') do set DATA_CONTAINER_ID=%%C
if not defined DATA_CONTAINER_ID (
    echo [INFO] Data container %DATA_CONTAINER% does not exist. Creating it...
    docker create --name %DATA_CONTAINER% -v %DATA_PATH% busybox
    if errorlevel 1 (
        echo [ERROR] Failed to create data container %DATA_CONTAINER%.
        pause
        exit /b 1
    )
    echo [INFO] Data container %DATA_CONTAINER% created.
)

REM Check if the app container is already running
for /f "delims=" %%C in ('docker ps --filter "name=%APP_CONTAINER%" --format "{{.ID}}"') do set RUNNING_CONTAINER_ID=%%C
if defined RUNNING_CONTAINER_ID (
    echo [INFO] App container %APP_CONTAINER% is already running. Attaching as user n4s...
    docker exec -it --user n4s %APP_CONTAINER% bash
    pause
    exit /b 0
)

REM Check if the app container exists but is stopped
for /f "delims=" %%C in ('docker ps -a --filter "name=%APP_CONTAINER%" --format "{{.ID}}"') do set STOPPED_CONTAINER_ID=%%C
if defined STOPPED_CONTAINER_ID (
    echo [INFO] Starting existing app container: %APP_CONTAINER%
    docker start %APP_CONTAINER%
    docker exec -it --user n4s %APP_CONTAINER% bash
    pause
    exit /b 0
)

REM Pull the latest image from Docker Hub
echo [INFO] Pulling the latest image: %IMAGE_NAME%...
docker pull %IMAGE_NAME%
if errorlevel 1 (
    echo [ERROR] Failed to pull image %IMAGE_NAME%. Please check your Docker Hub connection.
    pause
    exit /b 1
)

REM Start a new app container with the data container mounted
echo [INFO] Starting a new app container: %APP_CONTAINER%...
docker run -dit --name %APP_CONTAINER% --volumes-from %DATA_CONTAINER% %IMAGE_NAME%
if errorlevel 1 (
    echo [ERROR] Failed to start new app container %APP_CONTAINER%. Please verify that the data container exists.
    pause
    exit /b 1
)

REM Attach to the container as user n4s
docker exec -it --user n4s %APP_CONTAINER% bash

REM Keep the terminal open for debugging
pause
