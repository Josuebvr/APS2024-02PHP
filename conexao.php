

                                                            
                                                <!-- NÃO MUDA NADA AQUI --> 


<?php

$hostname = "localhost";
$bancodedados = "APS2024-02PHP";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);
if ($mysqli->connect_error) {
    echo "Falha ao conectar: (" . $mysqli->connect_errno  . ") " .  $mysqli->connect_error;
} ?>



                                            <!-- NÃO MUDA NADA AQUI --> 