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

//var_dump($_SERVER);