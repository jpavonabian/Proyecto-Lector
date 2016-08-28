<?php
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
echo('Logeado como '. $identificado .'<a href="logout.php?SID">Salir</a>');
header('Content-type: text/html; charset=utf-8');
header('Location: menu.php');
}
else {
?>
<html>
<head>
<title>Acceso de usuarios</title>
	<meta name="description" content='Lector de libros'>
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">

</head>
<body>
<form method="post" action="logear.php">
Correo electrónico   :</br><input type="Text" name="email"><br>
Contraseña:</br><input type="password" name="contrasenya"><br>
<input type="Submit" name="enviar" value="Entrar">
</form>
<p>¿No tienes cuenta? <a href="registro.html">¡Consigue una!</a></p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>
<?php
}

?>