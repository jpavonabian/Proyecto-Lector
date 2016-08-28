<?php
session_start();
setcookie('cusuario', '' , time() - 4900);
session_unset();
session_destroy();
header('Location: index.php');
?>
<html>
<head>
<title>Cerrando sesión</title>
</head>
<body>
<p><a href="ingresa.php">Entra con un nuevo usuario.</a></p>
</body>
</html>
