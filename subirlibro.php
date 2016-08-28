<?php
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
header('Content-type: text/html; charset=utf-8');
session_start();
?>
<html>
<head>
<title>Subir un libro</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<?php
echo('<h2 id="logeado">Tu cuenta: '. $identificado .' <a href="logout.php?SID">Salir</a></h2>');
?>
<p id="formulario">
<form enctype="multipart/form-data" action="subir.php" method="POST">
    <!-- MAX_FILE_SIZE debe preceder al campo de entrada del fichero -->
    <input type="hidden" name="MAX_FILE_SIZE" value="1000000000" />
    <!-- El nombre del elemento de entrada determina el nombre en el array $_FILES -->
    Selecciona un archivo: <input name="fichero_usuario" type="file" />
	Título:</br><input type="Text" name="titulo"><br>
    <input type="submit" value="Subir libro" />
</form>
</p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>
<?php
}
else {
echo('<html>
<head>
<title>¡ERROR!</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<p>Hakers no, ¡Gracias!</p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>');
    }
?>