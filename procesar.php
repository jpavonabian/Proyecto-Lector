<?php
header('Content-type: text/html; charset=utf-8');
require("recursos.php");
ini_set('display_errors', 'On');
ini_set('display_errors', 1);
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
echo ('Logeado como '. $identificado .'<a href="logout.php">Salir</a>');
echo('<html>
<head>
<title>¡ERROR!</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<p>¿Para qué quieres registrar un usuario estando identificado?</p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>');
}
// comprobamos que el usuario no esté ya en la base de datos.
// Si lo está redirigimos al index y lo sacamos sin hacer nada. De lo contrario insertamos datos.
mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$sql="select * from usuarios where email="."'"."$usuario"."';";
    $rec = mysql_query($sql);
mysql_close();
$count = 0;
    while($row = mysql_fetch_object($rec))
    {
        $count++;
        $result = $row;
    }
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
<p>Ese e-mail está registrado en la base de datos. <a href="javascript:history.go(-1)">Vuelve a intentarlo.</a></p>
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
<head>
<title>Procesando registro</title>
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
?>