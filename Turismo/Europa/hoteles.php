
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hoteles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <h2 style = "text-align: left;">Hoteles recomendados según la posición de las atracciones requeridas</h2>
    <style>
        html, body{
            height: 100%;
        }
        .card-img{
            height: 25vh;
            object-fit: cover;
        }
        .row-cols-md-3{
            margin-left:1%;
            margin-right:1%;
        }
        .card{
            margin: 0.2%;
        }
        a{
            color: black;
            text-decoration: none;
        }
        a:hover{
            color: black;
            text-decoration: none;
        }
    
    </style>
    <div class="row row-cols-1 row-cols-md-3">
    <?php
        require_once ("../src/conect.php");
        $url = $_SERVER['REQUEST_URI'];
        $url_components = parse_url($url); 
        if (isset($url_components['query'])){
            parse_str($url_components['query'], $params);
        }
        if (isset($params['id'])){
            $id = $params['id'];

            $consulta_hoteles = "SELECT * from hoteles WHERE hoteles.destinos_id = $id";
            if ($resultado3 = $mysqli->query($consulta_hoteles) ) {
                $hoteles = $resultado3->fetch_all();
            }

            $cons = "SELECT * FROM atrac WHERE atrac.atrac_id = (SELECT MAX(atrac.atrac_id) FROM atrac where atrac.destinos_id = $id) or atrac.atrac_id = (SELECT MIN(atrac.atrac_id) FROM atrac where atrac.destinos_id = $id);  ";
            
            $distancias = array();
            $existe = 0;
            if ($resultado = $mysqli->query($cons) ) {
                if ($resultado->num_rows >= 1){
                    $selected = $resultado->fetch_all();
                    $start = $selected[0][0];
                    $end = $selected[1][0];
                    foreach ($hoteles as $key => $value) {
                        $disthotelatrac = array();
                        for ($i=$start; $i <= $end ; $i++) { 
                            if (isset($_POST["atrac-$i"])){
                                $existe = 1;
                                $row = "SELECT atrac.latitud, atrac.longitud from atrac where atrac.atrac_id = $i";
                                if ($resultado2 = $mysqli->query($row) ) {
                                    $fila = mysqli_fetch_row($resultado2);
                                    $dist = sqrt(pow(abs($fila[0]-$hoteles[$key][6]), 2) +pow(abs($fila[1]-$hoteles[$key][7]), 2));
                                    array_push($disthotelatrac, $dist);
                                }
                            }
                        }
                        if ($existe){
                            $prom = 0;
                            foreach ($disthotelatrac as $key2 => $value2) {
                                    $prom += $value2;
                            }
                            $prom = $prom / count($disthotelatrac);
                            array_push($distancias, [$prom, $hoteles[$key][0]]);
                        }
                    }
                    
                    for ($x = 0; $x < count($distancias); $x++) {
                        for ($i = 0; $i < count($distancias)-$x -1; $i++) {
                            if($distancias[$i][0] > $distancias[$i+1][0]){
                                $tmp = [$distancias[$i+1][0], $distancias[$i+1][1]];
                                $distancias[$i+1][0] = $distancias[$i][0];
                                $distancias[$i+1][1] = $distancias[$i][1];
                                $distancias[$i][0] = $tmp[0];
                                $distancias[$i][1] = $tmp[1];
                            }
                        }
                    }
                }
            }
            foreach ($distancias as $key => $value) {
                foreach ($hoteles as $llave => $valor) {
                        if ($hoteles[$llave][0] == $distancias[$key][1]){
                            $link = $hoteles[$llave][8];
                            $src = $hoteles[$llave][9];
                            $title = $hoteles[$llave][2];
                            $precio = $hoteles[$llave][4];
                            $lugar = $hoteles[$llave][5];
                            $estrellas = $hoteles[$llave][3];
                            echo "<div class='card mb-4' style='max-width: 540px;'>
                            <a href='$link'>
                            <div class='row no-gutters'>
                              <div class='col-md-4'>
                                <img src=$src class='card-img' alt='...'>
                              </div>
                              <div class='col-md-8'>
                                <div class='card-body'>
                                  <h5 class='card-title'>$title</h5>
                                  <p class='card-text'>$lugar</p>
                                  <p class='card-text'>$$precio</p>
                                  <p class='card-text'><small class='text-muted'>$estrellas</small></p>
                                </div>
                              </div>
                            </div>
                            </a>
                          </div>";
                        }
                }
            }
        }
    ?>
</body>
</html>