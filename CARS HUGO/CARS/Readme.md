Descripción
Este proyecto en PHP permite gestionar personajes en una base de datos MySQL. Utiliza un archivo de configuración (db_config.php) para establecer la conexión con la base de datos y 
un script de configuración (setup.php) para crear la tabla personajes si no existe.

Requisitos

PHP 7.4 o superior

MySQL 

Servidor local (XAMPP, WAMP, Laragon, etc.)

Instalación

Clonar o descargar el repositorio en tu servidor local.

Configurar el acceso a la base de datos en db_config.php.

Asegurar que el servidor MySQL está corriendo en el puerto correcto (por defecto 3307).

Ejecutar setup.php en el navegador o línea de comandos para crear la base de datos y la tabla personajes.


Archivos principales

db_config.php: Configuración de la conexión a la base de datos.

setup.php: Script para crear la tabla personajes.


Estructura de la tabla personajes

La tabla contiene los siguientes campos:

id: Identificador único (INT, AUTO_INCREMENT, PRIMARY KEY).

nombre: Nombre del personaje (VARCHAR(100), NOT NULL).

color: Color asociado al personaje (VARCHAR(50), NOT NULL).

tipo: Tipo de personaje (VARCHAR(50), NOT NULL).

nivel: Nivel del personaje (INT, NOT NULL).

foto: Ruta de la imagen del personaje (VARCHAR(255), NOT NULL).


Uso

Abrir el navegador y acceder a http://localhost/setup.php para ejecutar el script.

Verificar que la base de datos cars_db y la tabla personajes han sido creadas correctamente.


Notas

Asegúrate de que mysqli y PDO estén habilitados en tu configuración de PHP.

Puedes modificar db_config.php para cambiar las credenciales de acceso a la base de datos.

Hugo Donator