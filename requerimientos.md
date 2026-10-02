# Requerimientos de Entorno - CodeIgniter 4

Para asegurar el correcto funcionamiento de la aplicación, el servidor o entorno de desarrollo debe cumplir con los siguientes estándares.

## 1. Lenguaje PHP
*   **Versión Mínima:** PHP 8.1

## 2. Extensiones de PHP Obligatorias
Es necesario que las siguientes extensiones estén habilitadas en el archivo `php.ini`:

- [ ] `intl`: Utilizada para el manejo de internacionalización y localización.
- [ ] `mbstring`: Necesaria para procesar cadenas de caracteres multibyte.
- [ ] `json`: (Habilitada por defecto en la mayoría de las instalaciones).
- [ ] `xml`: Requerida para el procesamiento de datos XML.
- [ ] `mysqlnd`: Requerida si utilizas bases de datos MySQL.
- [ ] `curl`: Requerida si planeas usar la librería `CURLRequest`.

## 2.1 Configuración php.ini
## Esta configuración se encuentra definida en el archivos .htaccess .user.ini php.ini por lo que con descompresion del archivo gador_warehouse.zip deberia ser suficiente.
- max_execution_time = 300 (minimo)
- max_input_time = 60 (minimo)
- memory_limit = 64M (minimo)
- post_max_size = 8M (minimo)
- upload_max_filesize = 8M (minimo)

## 3. Soporte de Base de Datos

*   **MySQL:** versión 8.0 o superior (vía driver `MySQLi`).

## 4. Servidor Web
*   **Apache:** versión 2.4 Con el módulo `mod_rewrite` habilitado para el manejo de URLs amigables.
