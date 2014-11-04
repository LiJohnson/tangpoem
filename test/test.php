<?php
$char = "明adf苛枯夺 ";
for( $i = 0 ; $i < strlen($char) ; $i++ ){
	echo "<br>";
	var_dump( iconv('UTF-8', 'UCS-2', $char));
}