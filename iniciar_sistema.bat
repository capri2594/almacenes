@echo off
chcp 65001 >nul
title SISTEMA DE ALMACENES - SERVIDOR LOCAL (PRUEBAS 003)

echo =====================================================================
echo       INICIANDO SISTEMA DE ALMACENES - ENTORNO LOCAL
echo =====================================================================
echo.

set "PHP_BIN="

if exist "C:\xampp\php\php.exe" set "PHP_BIN=C:\xampp\php\php.exe"
if not defined PHP_BIN if exist "C:\php-8.2\php.exe" set "PHP_BIN=C:\php-8.2\php.exe"
if not defined PHP_BIN if exist "C:\php-8.1\php.exe" set "PHP_BIN=C:\php-8.1\php.exe"
if not defined PHP_BIN if exist "C:\php\php.exe" set "PHP_BIN=C:\php\php.exe"
if not defined PHP_BIN if exist "C:\tools\php\php.exe" set "PHP_BIN=C:\tools\php\php.exe"
if not defined PHP_BIN (
    where php.exe >nul 2>nul
    if %ERRORLEVEL% EQU 0 set "PHP_BIN=php.exe"
)

if not defined PHP_BIN (
    echo [ERROR] No se encontro un ejecutable de PHP en su sistema.
    echo Asegurese de tener XAMPP con PHP 8.1 o superior instalado.
    echo.
    pause
    exit /b 1
)

echo [OK] PHP detectado: %PHP_BIN%

:: 1. Validar version de PHP
"%PHP_BIN%" -r "if (version_compare(PHP_VERSION, '8.1.0', '<')) { echo '[ERROR] Su version de PHP es ' . PHP_VERSION . '. CodeIgniter 4 requiere PHP 8.1 o superior.'.PHP_EOL; exit(1); }"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [SOLUCION] Por favor instale XAMPP con PHP 8.2 en esta maquina.
    echo.
    pause
    exit /b 1
)

:: 2. Validar extension intl obligatoria
"%PHP_BIN%" -r "if (!extension_loaded('intl')) { echo '[ERROR] La extension intl de PHP esta desactivada en php.ini.'.PHP_EOL; exit(1); }"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [SOLUCION PARA ACTIVAR INTL]:
    echo 1. Abra XAMPP Control Panel.
    echo 2. En la fila de Apache, presione 'Config' y elija 'PHP (php.ini)'.
    echo 3. Busque la linea: ;extension=intl
    echo 4. Quite el punto y coma (;) inicial para que quede: extension=intl
    echo 5. Guarde el archivo y vuelva a ejecutar este archivo .bat.
    echo.
    pause
    exit /b 1
)

:: 3. Validar extension mysqli obligatoria
"%PHP_BIN%" -r "if (!extension_loaded('mysqli')) { echo '[ERROR] La extension mysqli de PHP esta desactivada en php.ini.'.PHP_EOL; exit(1); }"
if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [SOLUCION] Active extension=mysqli en su php.ini de XAMPP.
    echo.
    pause
    exit /b 1
)

echo [OK] Validaciones de PHP e intl superadas con exito.
echo.

cd /d "%~dp0"

echo [OK] Abriendo navegador en http://localhost:8080 ...
start "" http://localhost:8080

echo.
echo =====================================================================
echo  El servidor esta funcionando en: http://localhost:8080
echo.
echo  IMPORTANTE:
echo  - NO CIERRE ESTA VENTANA mientras este trabajando en el sistema.
echo  - Para detener el servidor, presione Ctrl + C o cierre esta ventana.
echo =====================================================================
echo.

"%PHP_BIN%" -S localhost:8080 -t public system\Commands\Server\rewrite.php

if %ERRORLEVEL% NEQ 0 (
    echo.
    echo [AVISO] El servidor se ha detenido con codigo de salida %ERRORLEVEL%.
    pause
)
