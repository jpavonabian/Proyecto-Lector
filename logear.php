<?php
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

require("recursos.php");
header('Content-type: text/html; charset=utf-8');
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
            $_SESSION['usuario'] = $result->email;
$usuario=$result->email;
setcookie('cusuario', $usuario , time() + 4800);
?>
<html>
<head>
<title>Lector de libros: Acceso de usuarios</title>
	<meta name="description" content='Lector de libros'>
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<h2>Bienvenido</h2>
<?php
echo('<h2 id="logeado">Tu cuenta: '. $_SESSION['usuario'] .' <a href="logout.php?SID">Salir</a></h2>');
?>
<a href="subirlibro.php">Subir un libro en formato txt</a>

<a href="leerlibros.php">Leer un libro</a>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>
<?php
	}
    else
    {
echo('<html>
<head>
<title>¡ERROR!</title>
	<meta name="description" content="Lector de libros">
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<p>El usuario o la clave introducida no son datos correctos. <a href="ingresa.php">Vuelve a intentarlo.</a></p>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
</body>
</html>');
    }
?>