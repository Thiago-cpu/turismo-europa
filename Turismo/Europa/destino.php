
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
.card{
    cursor: pointer;
}
.card-text{
    font-size: 13px;
    
}
.card:hover{
    border: solid 3px green;
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
    <a href="index.php">Regresar</a>
<h1 style="position: relative; text-align: center; width:60%; display: none;" id="cardetitle">Lugares escogidos</h1>
<?php
$url = $_SERVER['REQUEST_URI'];
$url_components = parse_url($url); 
if (isset($url_components['query'])){
parse_str($url_components['query'], $params);
}
if (isset($params['id'])){
$id = $params['id'];
echo "<form action='hoteles.php?id=$id' method='POST'>";
?>

<div class="row row-cols-1 row-cols-md-3" id="lugaresescogidos">
</div>
<button type="submit">Enviar</button>
</form>
<h1 style="position: relative; text-align: center; width:60%;">Escoja lugares que desee visitar</h1>
<div class="input-group mb-3" style="width: 20%;">
  <input type="text" class="form-control" oninput='enter(event)' id='bus' autofocus placeholder="Buscar..." >
  <div class="input-group-append">
    <button class="btn btn-outline-secondary" type="button" id="button-addon2">buscar</button>
  </div>
</div>
<div class="row row-cols-1 row-cols-md-3" id="lugares">
    

<?php


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
const mostrarlugaresescogidos= (lugaresescogidos,grid) =>{
    document.getElementById('cardetitle').style.display = ''
    const container = document.querySelector(`#${grid}`)
    container.innerHTML = ''
    lugaresescogidos.map(lugar => {
        container.innerHTML += `
        <div class='col mb-4' value = '${lugar.id.substring(5)}'  id='cardE-${lugar.id.substring(5)}'>
        <input type="hidden" name = 'atrac-${lugar.id.substring(5)}' value = '${lugar.id.substring(5)}'>
        <div class='card' style='border: solid 5px green' onclick='select(${lugar.id.substring(5)})'>
        <img src='${lugar.querySelector('img').src}' alt='${lugar.querySelector('img').alt}' class='card-img-top'>
        <div class='card-body'>
        <h2>${lugar.querySelector('img').alt}</h2>
        <p class='card-text'>${lugar.querySelector('p').textContent}</p>
        </div>
        </div>
        </div>
        `
    })
    
    container.innerHTML += "</div>"
}
function enter(e){
    const input = e.target.value.toLowerCase()
    cont = 0;
    lugares.forEach(element => {
        if (!reemplazarAcentos(element[2].toLowerCase()).includes(reemplazarAcentos(input))){
            document.getElementById(`card-${element[0]}`).style.display = "none";
            cont += 1
        } else {
            document.getElementById(`card-${element[0]}`).style.display = "";
            cont -= 1
        }
    });
    cont == lugares.length ? e.target.style = 'border-color:red' :  e.target.style = ''
}
const mostrarlugares= (lugares,grid) =>{
    const container = document.querySelector(`#${grid}`)
    container.innerHTML = ''
    lugares.map(lugar => {
        container.innerHTML += `
        <div class='col mb-4' onclick=''  id='card-${lugar[0]}'>
        <div class='card' onclick='select(${lugar[0]})'>
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
mostrarlugares(lugares, 'lugares')
atracselect = [];
var select = function (id){
    col = document.querySelector(`#card-${id}`)
    card = document.querySelector(`#card-${id} > .card`)
    if (!atracselect.includes(col)){
        card.style.border = "solid 5px green"
        atracselect.push(document.getElementById(`card-${id}`))
    }else {
        atracselect = atracselect.filter(titulo => titulo != col)
        card.style.border = ""

    }
    mostrarlugaresescogidos(atracselect, 'lugaresescogidos')
    console.log(atracselect)
}

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