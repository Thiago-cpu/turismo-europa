<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TurismoEuropa</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
    <link rel="stylesheet" href="destino.css">
</head>
<body>
<h1 style="position: relative; text-align: center;">Escoja un destino</h1>
<div class="input-group mb-3" style="width: 20%;">
  <input type="text" class="form-control" oninput='enter(event)' id='bus' autofocus placeholder="Buscar..." >
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" id="button-addon2">buscar</button>
  </div>
</div>


<div class="row row-cols-1 row-cols-md-3">

<?php
$url = $_SERVER['REQUEST_URI'];
$url_components = parse_url($url); 
if (isset($url_components['query'])){
parse_str($url_components['query'], $params); 
$consulta = "SELECT  destinos.nombre,destinos.des,destinos.src, destinos.destinos_id FROM destinos where destinos.nombre like '%$params[bus]%'";
} else {
    $consulta = "SELECT  destinos.nombre,destinos.des,destinos.src, destinos.destinos_id FROM destinos";
}
require_once ("../src/conect.php");



if ($resultado = mysqli_query($mysqli, $consulta)) {
    if($resultado->num_rows < 1){
        header('location: ./index.php?bus=');
    
    
    }
    $row = $resultado->fetch_all();
    $json = json_encode($row);
}
?>
<script>
<?php
echo "const lugares = $json;";


?>

function enter(e){
    const input = e.target.value.toLowerCase()
    const lugaresFiltrados = lugares.filter(lugar => reemplazarAcentos(lugar[0].toLowerCase()).includes(reemplazarAcentos(input)))
    mostrarlugares(lugaresFiltrados)
    !lugaresFiltrados.length ? e.target.style = 'border-color:red' :  e.target.style = ''
}
const mostrarlugares= (lugares) =>{
    const container = document.querySelector('.row-cols-md-3')
    container.innerHTML = ''
    lugares.map(lugar => {
        container.innerHTML += `
        <div class='col mb-4' onclick='destino(${lugar[3]})'>
        <div class='card' id='card-${lugar[3]}'>
        <img src='${lugar[2]}' alt='${lugar[0]}' class='card-img-top'>
        <div class='card-body'>
        <h2>${lugar[0]}</h2>
        <p class='card-text'>${lugar[1]}</p>
        </div>
        </div>
        </div>
        `
    })
    
    container.innerHTML += "</div>"
}
mostrarlugares(lugares)

var reemplazarAcentos=function(cadena)
{
	var chars={
		"á":"a", "é":"e", "í":"i", "ó":"o", "ú":"u",
		"à":"a", "è":"e", "ì":"i", "ò":"o", "ù":"u", "ñ":"n",
		"Á":"A", "É":"E", "Í":"I", "Ó":"O", "Ú":"U",
		"À":"A", "È":"E", "Ì":"I", "Ò":"O", "Ù":"U", "Ñ":"N"}
	var expr=/[áàéèíìóòúùñ]/ig;
	var res=cadena.replace(expr,function(e){return chars[e]});
	return res;
}
const destino = function(id){
    window.location.href = window.location.href.substring(0, window.location.href.lastIndexOf("/")) + '/destino.php?id=' + id;
}
</script>
<style>
.card{
    cursor:pointer;
}

</style>
</div>
</body>
</html>
