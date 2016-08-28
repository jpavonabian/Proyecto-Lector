<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);
$identificado="";
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
$dir_subida='/var/www/html/ff/web/proyectolector/libros/';
$notitulo="Sin título";
if($_POST['titulo']==null)
	$titulo=$notitulo;
else
	$titulo=$_POST['titulo'];


$fichero_subido=$dir_subida . basename($_FILES['fichero_usuario']['name']);
require("recursos.php");
header('Content-type: text/html; charset=utf-8');
mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$dameusuario="select id from usuarios where email="."'"."$identificado"."';";
$pillarusuario=mysql_query($dameusuario);
$esusuario=mysql_fetch_array($pillarusuario);
$idusuario=$esusuario['id'];
$sql="insert into libros (ruta, titulo) VALUES ( "."'".basename($_FILES['fichero_usuario']['name'])."', "."'".$titulo."');";
$result = mysql_query($sql);
$sql="select id from libros where titulo='".$titulo."';";
$result = mysql_query($sql);
$libroid=mysql_fetch_array($result);
$idlibro=$libroid['id'];
$sql="insert into librosusuarios (idlibro, idusuario, linea) VALUES ( "."'".$idlibro."', "."'".$idusuario."', 1);";
$result = mysql_query($sql);
mysql_close();
$archivo = basename($_FILES['fichero_usuario']['name']);
?>
<html>
<head>
<title>Subida de libros</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<?php
echo '<p>';
if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
    echo "El fichero es válido y se subió con éxito.</p>";
} else {
    echo "Posible ataque de subida de ficheros!</p>";
}


echo('<p>Vuelve al <a href="menu.php">menú de usuario</a> para continuar</p>');
?>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>

<?php
}
else {
	?>
	<html>
	<head>
	<title>Hackers no, gracias</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
	</head>
	<body><p>¡Largo de aquí!</p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
	</body>
	</html>
	<?php
}
?>
