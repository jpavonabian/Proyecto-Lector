<?php
require("recursos.php");
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$dameusuario="select id from usuarios where email="."'"."$identificado"."';";
$pillarusuario=mysql_query($dameusuario);
$esusuario=mysql_fetch_array($pillarusuario);
$idusuario=$esusuario['id'];
$sql = "select * from libros where id in (select idlibro from librosusuarios where idusuario='".$idusuario."');";

$result = mysql_query($sql);
$datos ="";
while ($row=mysql_fetch_array($result)) { //Bucle para ver todos los registros
$ruta=$row['ruta'];
$titulo=$row['titulo'];
$datos.='<option value="'.$ruta.'">'.$titulo.'</option>';
}
header('Content-type: text/html; charset=utf-8');
mysql_close();
?>
<html>
<head>
<title>Lee donde quieras: Lector de libros accesible</title>
	<meta name="description" content='Lector de libros'>
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<?php
echo('<h2 id="logeado">Tu cuenta: '. $identificado .' <a href="logout.php?SID">Salir</a></h2>');
?>
<p>Selecciona un libro de los disponibles y pulsa el botón para leerlo.</p>
<form method="get" action="leer.php">
Libro: <select name="ruta">
<?php
echo($datos);
?>
</select>
<input type="Submit" name="leer" value="Leer libro">
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>
<?php
} else {
?>
<html>
<head>
<title>¡Error!</title>

	<meta name="description" content='Lector de libros'>
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<p>Largo, hacker.</p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>
<?php
}
?>
