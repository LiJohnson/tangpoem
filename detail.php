<?php 
echo "<h1>$data[title]<small>$data[name]</small></h1>";
echo "<ul>";
foreach ($data['content'] as $li) {
	echo "<li>$li</li>";
}
echo "</ul>";
echo "<hr>";
foreach (array('note' , 'rhymed' , 'comment') as $key) {
	echo "<pre>".$data['info'][$key]."</pre>";
}
?>

<pre><a href="">shit</a></pre>