
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atraciones</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<style>
html, body{
    height: 100%;
}
.card-text{
    font-size: 13px;
    
}
.card:hover{
    border: solid 2px green;
}
.input-group{
    margin-left: 20%;
}
.row{
    margin-right: 40%;
}
.form-control:focus{
    outline:none;
    box-shadow:none;
    border-color:inherit;
}

    .card-img-top{
        height: 20vh;
        object-fit: cover;
    }
</style>
<body>
<h1 style="position: relative; text-align: center; width:60%;">Escoja lugares que desee visitar</h1>
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
}
if (isset($params['id'])){
$id = $params['id'];

require_once ("../src/conect.php");

$consulta = "SELECT * from atrac where atrac.destinos_id = $id";

if ($resultado = mysqli_query($mysqli, $consulta)) {
    $row = $resultado->fetch_all();
    $json = json_encode($row);
 

}
}
?>
<script>
<?php
echo "const lugares = $json;";


?>

function enter(e){
    const input = e.target.value.toLowerCase()
    console.log(input)
    const lugaresFiltrados = lugares.filter(lugar => reemplazarAcentos(lugar[2].toLowerCase()).includes(reemplazarAcentos(input)))
    mostrarlugares(lugaresFiltrados)
    !lugaresFiltrados.length ? e.target.style = 'border-color:red' :  e.target.style = ''
}
const mostrarlugares= (lugares) =>{
    const container = document.querySelector('.row-cols-md-3')
    container.innerHTML = ''
    lugares.map(lugar => {
        container.innerHTML += `
        <div class='col mb-4' onclick=''>
        <div class='card' id='card-${lugar[0]}'>
        <img src='${lugar[4]}' alt='${lugar[2]}' class='card-img-top'>
        <div class='card-body'>
        <h2>${lugar[2]}</h2>
        <p class='card-text'>${lugar[3]}</p>
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
</script>

<?php
if (isset($params['id'])){
$mapa = "SELECT destinos.map FROM destinos WHERE destinos.destinos_id =$id";
if ($resultado1 = mysqli_query($mysqli, $mapa)) {
    $map = mysqli_fetch_row($resultado1);
    echo "</div> <iframe src='$map[0]' width='33%' height='100%' frameborder='0' style='border:0; allowfullscreen='' aria-hidden='false' tabindex='0'></iframe> ";
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