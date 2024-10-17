# examen_backend_alcaraz_garcia_gabriel

Pasos para ejecutar el proyecto

1.- Copia el archivo con nombre env y pégalo con el nombre de .env
2.- Dentro de este archivo busca la línea que tenga la variable CI_ENVIRONMENT y elimina el signo de # que se encuentra de lado izquierdo
3.- Dentro de este archivo busca la línea que tenga la variable app.baseURL y elimina el signo de # que se encuentra de lado izquierdo y agrega la siguiente información http://localhost/examen_backend_alcaraz_garcia_gabriel/
4.- Dentro de este archivo busca las líneas que tengan la variable database.default descomentalas y agrega lo siguiente
	4.1.- En database.default.database agrega el nombre crud_usuarios que es la base de datos
	4.2.- En database.default.username agrega el usuario para la conexión a la base de datos
	4.3.- En database.default.password agrega la contraseña para la conexión a la base de datos
5.- Crea la base de datos crud_usuarios
