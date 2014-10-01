<?php
session_start();
var_dump($_SESSION);
$_SESSION['a'] = $_SESSION['a'] ? array( 'ha' => $_SESSION['a'] ) : array(5); 

?>