
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atraciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
   
</head>
<style>
    .card-img-top{
        height: 20vh;
        object-fit: cover;
    }
</style>
<body>

<div class="row row-cols-1 row-cols-md-3">

<?php
$url = $_SERVER['REQUEST_URI'];
$url_components = parse_url($url); 
if (isset($url_components['query'])){
parse_str($url_components['query'], $params);
}
if (isset($params['id'])){
$id = $params['id'];

require_once ("../src/conect.php");

$consulta = "SELECT * from atrac where atrac.destinos_id = $id";

if ($resultado = mysqli_query($mysqli, $consulta)) {

while ($fila = mysqli_fetch_row($resultado)){
    echo "
    <div class='col mb-4'>
    <div class='card'>
    <img src='$fila[4]' class='card-img-top'>
    <div class='card-body'>
    <p class='card-title'><b>$fila[2]</b></p>
    <p class='card-text'>$fila[3]</p>
    </div>
    </div>
    </div>
    ";
 

}
$mapa = "SELECT destinos.map FROM destinos WHERE destinos.destinos_id =$id";
if ($resultado1 = mysqli_query($mysqli, $mapa)) {
    $map = mysqli_fetch_row($resultado1);
    echo "</div> <iframe src='$map[0]' width='33%' height='100%' frameborder='0' style='border:0; allowfullscreen='' aria-hidden='false' tabindex='0'></iframe> ";
}
}


} else {
    echo "error pa ";
}
 
    ?>
<style>
.card {
    height: 100%;
}
.row{
    margin-right: 40%;
}
iframe{
    position:fixed;
    right: 0;
    top: 0;
    margin-right:3.5%;
}
</style>
</body>
</html>