<?php
require("recursos.php");
header('Content-type: text/html; charset=utf-8');
$path=$_GET['ruta'];
$temp =  str_replace("..", "-", $path);
$path = $temp;

?>
<html>
<head>
<title>Leer un libro</title>
	<meta name="description" content='Lector de libros'>
	<meta name="keywords" content="lector, libros, accesible">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link rel="stylesheet" href="hoja.css">
</head>
<body>
<?php
mysql_connect('localhost',$user,$password);
@mysql_select_db($database) or die( "No conectó a la base de datos");
$identificado="";
if(isset($_COOKIE['cusuario'])) {
$identificado=$_COOKIE['cusuario'];
$dameusuario="select id from usuarios where email="."'"."$identificado"."';";
$pillarusuario=mysql_query($dameusuario);
$esusuario=mysql_fetch_array($pillarusuario);
$idusuario=$esusuario['id'];
$dameidlibro = "select idlibro from librosusuarios where idusuario = $idusuario and idlibro = (select id from libros where ruta='$path');";
$libroconsulta=mysql_query($dameidlibro);
$eslibro=mysql_fetch_array($libroconsulta);
$idlibro=$eslibro['idlibro'];
// Ahora que tenemos la id del libro y la del usuario, podemos sacar la línea en la que dejamos el susodicho libro.
$linea="select linea from librosusuarios where idlibro=$idlibro;";
$librolinea=mysql_query($linea);
$lalineaes=mysql_fetch_array($librolinea);
$lineacogida=$lalineaes['linea'];
$lines = file('/var/www/html/ff/web/proyectolector/libros/'.$path);
$line_amount = count($lines);
$perpage = 35; #This number specified how many lines to show on a page.
$p = isset($_GET['p']) ? $_GET['p'] : $lineacogida; // si no recibimos nada por get que nos indique qué leer, tiramos de base de datos.
for ($i = (($p * $perpage) - $perpage); $i <= (($perpage * $p) - 1); $i++){
    if($i >= $line_amount){
        break;
    }
    else{
        if($lines[$i] != ''){
            echo ''.$lines[$i].'<br />'; # This is the output loop.
        }
    }
}

?>            
<table summary="" cellpadding="10" cellspacing="0"  border="0" class="global-links-menu">
        <tr>
<?php

$total_pages = $line_amount/$perpage;
if($line_amount % $perpage != 0){
    $total_pages = $total_pages + 1;
}


if($p!=1)
{
  $back_page=$p-1;
    echo "<td ><a href='?p=$back_page&ruta=$path'>Atrás</a></td>";
}
else
{
    $back_page=$p-1;
    echo "<td >Atrás</td>";
}
$t=$p;
if(($p-2)>=2)
	$t=$p-2;
$stop=1;
if($p+5>=$total_pages)
	$stop=$total_pages;
else
	$stop=$p+5;

for($j=$t;$j<=$stop;$j++)
{
    
    if($j==$p)
    {        
        echo "<td >$p &nbsp;</td>";
    }
    else
    {
        echo "<td ><a href='?p=$j&ruta=$path'>$j &nbsp;</a></td>";    
    }
}

if($p <= $total_pages - 1){
    $next_page=$p+1;
    echo "<td ><a href='?p=$next_page&ruta=$path'>Siguiente</a></td>";    
}
else
{
    echo "<td >Siguiente</td>";
}
// Guardamos la posición en la que nos encontramos para una futura lectura.
$sql="update librosusuarios set linea=$p where idlibro=$idlibro;";
$result = mysql_query($sql);
mysql_close();
?>
    </tr></table>
<footer id="pie">
			&copy; Jesús Pavón Abián
		</footer>
	</body>
	</html>
	<?php
}
else {
	?>
<p>¡Largo, hacker!</p>
</body>
</html>
<?php
}
?>
