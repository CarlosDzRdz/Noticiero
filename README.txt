Este proyecto es un noticiero web que corre en PHP con una base de datos MySQL. A continuación se detalla cómo instalar WAMP, importar la base de datos y ejecutar el proyecto localmente.

---

## Requisitos

- Windows 10 u 11
- Navegador web (Chrome, Firefox, Edge)
- [WAMP Server](https://www.wampserver.com/en/)

---

## Instalación de WAMP

1. Descarga WAMP desde su sitio oficial:  
   https://www.wampserver.com/en/
2. Instala WAMP con la configuración predeterminada.
3. Abre WAMP y asegúrate de que el ícono en la bandeja esté **verde** (significa que Apache y MySQL están funcionando).
4. Abre tu navegador y visita:  
   http://localhost/  
   para verificar que WAMP está corriendo correctamente.

---

## Estructura del proyecto

Coloca la carpeta del proyecto en la carpeta `www` de WAMP, usualmente en:
C:\wamp64\www\Noticiero


## Importar la base de datos en phpMyAdmin

1. Abre tu navegador y entra a:  
   http://localhost/phpmyadmin
2. Inicia sesión si es necesario (por defecto: **usuario `root` sin contraseña**).
3. Haz clic en **"Nueva"** para crear una nueva base de datos.  
   Nómbrala, como: news_tech
4. Haz clic en la nueva base de datos creada.
5. Ve a la pestaña **"Importar"**.
6. Haz clic en **"Seleccionar archivo"** y elige el archivo `noticiero.sql` que está en:
   C:\wamp64\www\Noticiero\BD\news_tech.sql
7. Haz clic en **"Continuar"**.
8. Espera a que phpMyAdmin confirme que la importación fue exitosa.

---

## Ejecutar el proyecto

1. Asegúrate de que WAMP esté corriendo.
2. Abre tu navegador.
3. Ve a la siguiente URL:
   http://localhost/Noticiero/index.php
4. ¡Listo! Deberías ver la página principal del Noticiero.

---

## Notas adicionales

- Asegúrate de que los archivos `.php` puedan conectarse a la base de datos con las credenciales correctas:
  ```php
  $host = "localhost";
  $user = "root";
  $pass = ""; // sin contraseña por defecto
  $db = "noticiero";
  ```
- Si hiciste cambios a la base de datos, puedes volver a importar el `.sql` o usar phpMyAdmin para editar directamente.

---

## Soporte

Si tienes problemas con la instalación, verifica:

- Que el firewall no esté bloqueando Apache o MySQL.
- Que no haya otros servicios usando los puertos 80 o 3306.
- Que la carpeta `Noticiero` esté directamente dentro de `www`.
