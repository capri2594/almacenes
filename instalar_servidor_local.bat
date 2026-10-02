@echo off
chcp 65001 >nul
title INSTALADOR AUTOMATICO - SISTEMA DE ALMACENES (PRUEBAS 003)

echo =====================================================================
echo       INSTALADOR AUTOMATICO - SISTEMA ALMACENES (LOCAL)
echo =====================================================================
echo.
echo [1/4] Buscando motor MySQL local...

set "MYSQL_BIN="

if exist "C:\xampp\mysql\bin\mysql.exe" (
    set "MYSQL_BIN=C:\xampp\mysql\bin\mysql.exe"
) else (
    where mysql.exe >nul 2>nul
    if %ERRORLEVEL% EQU 0 (
        set "MYSQL_BIN=mysql.exe"
    )
)

if "%MYSQL_BIN%"=="" (
    echo.
    echo [ERROR] No se encontro mysql.exe en C:\xampp\mysql\bin ni en el PATH.
    echo Por favor asegurese de tener XAMPP instalado e iniciado el servicio MySQL.
    echo.
    pause
    exit /b 1
)

echo        Encontrado: %MYSQL_BIN%

echo.
echo [2/4] Creando Base de Datos 'warehouse' e importando datos...

set "SQL_FILE=%~dp0dump-warehouse-20260929_PRUEBAS_003.sql"
if not exist "%SQL_FILE%" (
    if exist "C:\Users\ati\Desktop\dump-warehouse-20260929_PRUEBAS_003.sql" (
        set "SQL_FILE=C:\Users\ati\Desktop\dump-warehouse-20260929_PRUEBAS_003.sql"
    )
)

if not exist "%SQL_FILE%" (
    echo [ERROR] No se encontro el archivo SQL dump-warehouse-20260929_PRUEBAS_003.sql
    pause
    exit /b 1
)

echo        Archivo SQL: %SQL_FILE%
echo        Creando base de datos...
"%MYSQL_BIN%" -u root -e "CREATE DATABASE IF NOT EXISTS warehouse CHARACTER SET utf8 COLLATE utf8_general_ci;"

echo        Importando tablas y stock centralizado (esto puede tomar 15-30 segundos)...
"%MYSQL_BIN%" -u root --default-character-set=utf8 warehouse < "%SQL_FILE%"

if %ERRORLEVEL% NEQ 0 (
    echo [ADVERTENCIA] Si root tiene clave, por favor ejecute con: mysql -u root -p warehouse
) else (
    echo        Base de datos importada correctamente con exito.
)

echo.
echo [3/4] Configurando archivo de entorno .env...
if not exist "%~dp0.env" (
    if exist "%~dp0env" (
        copy "%~dp0env" "%~dp0.env" >nul
    )
)

echo.
echo [4/4] Limpiando cache del sistema...
if exist "%~dp0writable\cache\*" del /q "%~dp0writable\cache\*" 2>nul

echo =====================================================================
echo       INSTALACION Y CONFIGURACION COMPLETADA EXITOSAMENTE!
echo =====================================================================
echo.
echo Ahora puede iniciar el sistema haciendo doble clic en:
echo     iniciar_sistema.bat
echo.
pause
