<?php

$host ="";
$username ="";
$password ="";
$database ="";
mysqli_connect($host, $username, $password, $database);

if (mysqli_connect_errno())
  {
  echo "The database isn;t connected: " . mysqli_connect_error();
  }
?>