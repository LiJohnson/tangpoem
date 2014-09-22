<?php 
echo "<h1>$data[title]<small>$data[name]</small></h1>";
echo "<p><a href=admin/?action=poem&poemId=$data[poemId] target='_blank'>edit</a></p>";
echo "<ul class='list-unstyled' >";
foreach ($data['content'] as $li) {
	echo "<li>$li</li>";
}
echo "</ul>";
echo "<hr>";
foreach (array('note' , 'rhymed' , 'comment') as $key) {
	echo "<pre>".$data['info'][$key]."</pre>";
}
?>