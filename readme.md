Introducción

Proyecto lector nace cuando un par de usuarios que usamos lectores de pantallas debido a que somos invidentes nos dimos cuenta que no teníamos una alternativa accesible para acceder a la lectura digital de libros de forma cómoda y sencilla.
Actualmente existen reproductores de libros en formato Daysi (libros grabados en audio, ordenados con un archivo xml que se reproducen con un hardware o software específico, dependiendo de la persona) y existe software para la lectura de libros en varios formatos.
Pero todo esto tiene un problema: Es software específico, y la portavilidad es casi inexistente.
Una persona vidente, sin mucho problema, puede sacar su teléfono y leer cualquier cosa ahí sin mucha dificultad. E incluso puede retomar la lectura desde el ordenador usando la aplicación adecuada, justo por el lugar que se quedó en el teléfono.
La persona invidente o deficiente visual no puede realizar esta tarea de forma cómoda si no es duplicando contenidos y memorizando en qué parte del texto se ha quedado.
Por tanto, con este proyecto, propongo una forma cómoda y sencilla con lectores de pantallas para la lectura de libros, se use el dispositivo que se use siempre y cuando tenga conexión a internet y disponga de un navegador web. El usuario podrá subir sus libros (de momento solo en formato txt) a la aplicación mediante un formulario, y podrá leerlo donde quiera. El sistema guardará su posición cuando cierre la web.

recursos Hardware y software necesarios:

Solo se necesita un servidor donde poder colgar la aplicación web y al que se pueda acceder con facilidad desde una dirección fácil de recordar con lo siguiente instalado.
- Sistema operativo linux. Debian, ubuntu o Centos. Da igual. Podría hacerse bajo windows, pero sería demasiado engorroso mantenerlo en línea.
- Apache2, Php, phpmyadmin, mariadb y bind9 para gestionar las DNS si no se dispone de alguna otra forma para controlar el dominio, y git para poder clonar el software que gestiona los certificados ssl gratuítos de Lets Encript.

Configuración del entorno

Para que la aplicación funcione hay que instalar el software anteriormente mencionado. Como he dicho, se puede ejecutar en windows, pero mencionaré como hacerlo funcionar en linux.

Por tanto, como root hay que ejecutar los siguientes comandos:

Instalar apache, mariadb y php.

apt-get -y install mariadb-server mariadb-client
apt-get -y install apache2
apt-get -y install php5 libapache2-mod-php5
apt-get -y install php5-mysqlnd php5-curl php5-gd php5-intl php-pear php5-imagick php5-imap php5-mcrypt php5-memcache php5-pspell php5-recode php5-snmp php5-sqlite php5-tidy php5-xmlrpc php5-xsl
apt-get install php5-apcu phpmyadmin curl git

Se siguen las instrucciones en pantalla y luego se teclea:

service apache2 restart

Se configura el dominio, que se debe tener apuntando al servidor del cual colgará el proyecto. aún como root:

chmod -R 755 /var/www
Se crea un directorio en /var/www/html llamado proyectolector. Dentro, una carpeta llamada web. Añadimos un usuario llamado proyectolector con el comando adduser.
Se da permisos al directorio:
chown -R proyectolector:proyectolector /var/www/html/proyectolector/web
Se copia un archivo para crear un virtual host en apache:
cp /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-available/dominio.es.conf
Se modifica el archivo recién creado, añadiendo y editando las siguientes líneas, según sea el caso.
ServerName dominio.es
ServerAlias www.dominio.es
DocumentRoot /var/www/html/usuario/web

Se guardan cambios y se finaliza con un:

a2ensite dominio.es.conf
service apache2 restart

Instalación y configuración del certificado gratuíto ssl con renovación automática por crontab:

Se ejecuta como root y se siguen las instrucciones:

git clone https://github.com/letsencrypt/letsencrypt /opt/letsencrypt
curl -L -o /usr/local/sbin/le-renew http://do.co/le-renew
cd /opt/letsencrypt
./letsencrypt-auto --apache -d dominio.es -d www.dominio.es
chmod +x /usr/local/sbin/le-renew
crontab -e

Se añade lo siguiente:

30 2 * * 1 /usr/local/sbin/le-renew dominio.es >> /var/log/le-renew.log
30 2 * * 1 /usr/local/sbin/le-renew www.dominio.es >> /var/log/le-renew.log

Se guardan cambios y se reinicia apache.

service apache2 restart


Configurando la base de datos:

Para que el proyecto funcione hay que crear una base de datos. por tanto, estando (o no, es indiferente) como root, se teclea:

mysql -u root -p

Pedirá la contraseña del root de sql, una vez tecleada, saldrá algo como esto:

Welcome to the MariaDB monitor.  Commands end with ; or \g.
Your MariaDB connection id is 23090
Server version: 10.0.23-MariaDB-0+deb8u1 (Debian)

Copyright (c) 2000, 2015, Oracle, MariaDB Corporation Ab and others.

Type 'help;' or '\h' for help. Type '\c' to clear the current input statement.

MariaDB [(none)]> 

Se crea la base de datos y el usuario con permisoss para gestionarla:

MariaDB [(none)]> create database proyecto;
Query OK, 1 row affected (0.00 sec)
MariaDB [(none)]> create user usuario@localhost identified by 'contraseña';
grant all privileges on proyecto.* to usuario@localhost; flush privileges;
Query OK, 0 rows affected (0.00 sec)
Query OK, 0 rows affected (0.00 sec)

Ya se puede salir con exit; y cerrar la consola.

La base de datos consiste en tres tablas:

Libros, donde almacenaremos el título, la ruta y la id del libro, usuarios, donde almacenaremos un e-mail, una contraseña y un id de usuarios y una tabla llamada librosusuarios donde almacenaremos una id de libro, una id de usuario y el número de línea por el que el usuario se ha quedado, de forma que en esta última tabla habrá tantos registros como libros existan en la base de datos, todos relacionados de 1 a 1 con el usuario que los subió y con su información de línea, que por defecto, si no has empezado a leer el libro, es 1.


MariaDB [proyecto]> describe libros;
+--------+--------------+------+-----+---------+----------------+
| Field  | Type         | Null | Key | Default | Extra          |
+--------+--------------+------+-----+---------+----------------+
| id     | int(100)     | NO   | PRI | NULL    | auto_increment |
| ruta   | varchar(140) | NO   | PRI | NULL    |                |
| titulo | varchar(140) | NO   |     | NULL    |                |
+--------+--------------+------+-----+---------+----------------+
3 rows in set (0.00 sec)

MariaDB [proyecto]> describe usuarios;
+------------+--------------+------+-----+---------+----------------+
| Field      | Type         | Null | Key | Default | Extra          |
+------------+--------------+------+-----+---------+----------------+
| id         | int(140)     | NO   | PRI | NULL    | auto_increment |
| nombre     | varchar(140) | NO   | UNI | NULL    |                |
| contrasena | varchar(140) | YES  |     | NULL    |                |
| email      | varchar(140) | NO   |     | NULL    |                |
+------------+--------------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

MariaDB [proyecto]> describe librosusuarios;
+-----------+----------+------+-----+---------+----------------+
| Field     | Type     | Null | Key | Default | Extra          |
+-----------+----------+------+-----+---------+----------------+
| id        | int(100) | NO   | PRI | NULL    | auto_increment |
| idlibro   | int(100) | NO   | MUL | NULL    |                |
| idusuario | int(100) | NO   | MUL | NULL    |                |
| linea     | int(100) | NO   |     | NULL    |                |
+-----------+----------+------+-----+---------+----------------+
4 rows in set (0.00 sec)

para que funcione, hay que modificar el recursos.php con los datos de la base de datos y la ruta al directorio donde se almacenarán los libros en los archivos adecuados:

$user="usuario";
$password="contraseña";
$database="proyecto";

En subir.php:

$dir_subida='/var/www/html/proyectolecotr/web/libros/';

En leer.php:

$lines = file('/var/www/html/proyectolector/web/libros/'.$path);

En el código hay que destacar lo siguiente:

Todos los archivos salvo recursos.php por obvias razones tienen una línea
require("recursos.php");
puesto que ahí es donde están todas las constantes y variables que necesitarán más adelante.
También, para evitar la máxima cantidad de problemas con acentos y eñes, todoss tienen la línea
header('Content-type: text/html; charset=utf-8');
para codificar la web en UTF8.
la comprobación de logeo es repetitiva, y si es cierto que podría mejorarse un poco. Existe una variable en todos los archivos llamada $identificado que es donde se guarda el valor que se obtiene en una cookie al iniciar sesión.
si $identificado es igual a un nombre existente en la base de datos, cosa que se comprueba al logear, se creará una cookie de usuario. Y el resto simplemente consiste en comprobar la existencia de esa cookie.
Ejemplo claro de ello:

$identificado="";
if(isset($_COOKIE['cusuario'])) {
Aquí el código de la página que sea.
}
else {
Aquí el código para arrojar un error o redirigir al index para que te logees.
}

La cookie cusuario la creamos en el logear.php de la siguiente forma:

mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$sql="select * from usuarios where email="."'"."$usuario"."' and contrasena="."'"."$contrasenya"."';";
    $rec = mysql_query($sql);
mysql_close();
    $count = 0;
    while($row = mysql_fetch_object($rec))
    {
        $count++;
        $result = $row;
    }
if($count == 1)
    {
$usuario=$result->email;
setcookie('cusuario', $usuario , time() + 4800);
...
}

El registro de usuario es sencillo. En procesar. php compruebo que el usuario no exista en la base de datos, y si existe lo mando a otro sitio. Si no existe, lo inserto y lo mando al login.

if($count == 1) {
echo('<html>
<head>
<title>¡ERROR!</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>

p>Ese e-mail está registrado en la base de datos. <a href="javascript:history.go(-1)">Vuelve a intentarlo.</a></p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>');
}
else {
mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$sql="insert into usuarios (email, contrasena, nombre) VALUES ( "."'".$usuario."', "."'".$contrasenya."', "."'".$nombre."');";
$result = mysql_query($sql);
mysql_close();
echo('<html>
<head><title>Procesando registro</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<p>Usuario registrado. <a href="ingresa.php">Continuar</a></p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
		</body>
</html>');
}

Subir.php sirve para subir libros y añadirlos a la base de datos, con su relación incluso.
Si al usuario se le olvida poner un título, el propio sistema añadirá uno, aunque puede ser confuso si se te olvida en varios libros.

$notitulo="Sin título";
if($_POST['titulo']==null)
	$titulo=$notitulo;
else
	$titulo=$_POST['titulo'];

Y por último, uno de los más importantes es el leer.php. en leerlibros compruebo qué usuario eres y qué libros tienes, y lo muestro en un formulario (<select>). cuando el usuario pulsa sobre enviar, leer.php recibe parte de la ruta del libro (el nombre del archivo para ser más exactos) y comprueba en la base de datos dos cosas:

1. Que el libro sea del usuario que está identificado. Así evito que alguien pueda leer un libro que no sea suyo.
2. La línea por la que se quedó el usuario, aunque sería más correcto llamarla página.

Después solo queda leer con un bucle for el archivo.

Después de leer el archivo, o las líneas especificadas en $LinesPerPage creo una tabla para la paginación.

Y una vez creada la tabla, actualizo con un update la relación para modificar el número de página en el que se encuentra el usuario.

Solo queda subir los archivos del código fuente del proyecto a /var/www/proyectolectort/web y añadir la base de datos desde phpmyadmin, importando el script sql llamado script.sql.

Se han realizado sobre todo, después de comprobar el funcionamiento, pruebas de seguridad. Destacan entre ellas las formas que un usuario ha tenido para intentar colgar el servidor u obtener datos críticos de la aplicación al verse parte de la ruta del libro que se quiere leer en la dirección URL. Actualmente, es imposible sacar dichos datos. En un principio, era tan sencillo como hacer que la aplicación se leyese a sí misma, con algo parecido a lo siguiente:
http://dominio.es/leer.php?ruta=%2Fvar%2Fwww%2Fhtml%2Fff%2Fweb%2Fproyectolector%2Frecursos.php&leer=Leer+libro

La solución fue sencilla, aplicar rutas relativas y evitar el uso del .. como parámetro en el get, añadiendo en el leer php las líneas siguientes:

$path=$_GET['ruta'];
$temp =  str_replace("..", "-", $path);
$path = $temp;

y usar $path para referirse al libro.

Conclusiones finales

Con Proyecto lector he sentado unas bases sencillas para la creación de un software lector de libros digitales accesible para todo el mundo, incluso para gente vidente usando cualquier dispositivo. la aplicación es cómoda según la mayoría de usuarios que la ha probado, y tras consultar a un experto en accesibilidad, se ha llegado a la conclusión de que actualmente, pese a que puede mejorarse muchísimo, es una de las soluciones más viables para poder leer libros de forma accesible.
En un futuro pienso añadir nuevas características, como la lectura de PDF, documentos de Word o epubs.
