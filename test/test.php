<?php
session_start();
include "../config.php";
include "../include/class/MyKV.php";

$kv = new MyKV();
var_dump($kv);
$kv->delete("adminId");
$kv->delete("root");
?>