<?php
$f = new SaeImage(base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAAC0lEQVQIW2NkAAIAAAoAAggA9GkAAAAASUVORK5CYII='));	
$f->resize(150,150) ;
$f->annotate($_GET['text'],1,SAE_Center,array('name' => SAE_MicroHei , 'size' => 150 ));
$f->exec('png',true);
?>