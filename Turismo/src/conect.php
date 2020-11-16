<?php

$mysqli = new mysqli("bqawoevzpjb6pqwruzqy-mysql.services.clever-cloud.com", "uud5lirnqw32sacb", "V801SaLdkAdYS7MU5dfB", "bqawoevzpjb6pqwruzqy");
if ($mysqli->connect_errno) {
    echo "Fallo al conectar a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

?>