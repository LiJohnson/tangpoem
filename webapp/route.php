<?php

include __DIR__ . '/include/Router.class.php';

$a = new PoemRouter();

$a->get('/${action}',function( $action ){
	require 'index.php';
	return false;
})->get('/poem/${id}',function($id){
	$action =  'detail';
	$_GET['poemId'] = $id;
	require 'index.php';
	return false;
});

header('HTTP/1.1 404 Not Found');
header("status: 404 Not Found");
$action="notFound";
require 'index.php';