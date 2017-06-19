<?php

$serverName = "FRS2HTSTDB3\DEVWEB";
$connectionOptions = array(
    "Database" => "PSF",
    "Uid" => "PSF",
    "PWD" => "PSF"
);
//Establishes the connection

$conn = sqlsrv_connect($serverName, $connectionOptions);
if($conn)
    echo "Connected!"

?>