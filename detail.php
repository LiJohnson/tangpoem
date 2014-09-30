<div class='theme-1'>
<?php 
$audioClass = $data['audio'] ? '' : 'no-audio';

echo "<h1 class='$audioClass' ><span class='play-all btn-audio'><i class='glyphicon glyphicon-volume-down' ></i></span>$data[title]<small>$data[name]</small></h1>";
echo "<p><a href=admin/?action=poem&poemId=$data[poemId] target='_blank'>edit</a></p>";
echo "<ul class='list-unstyled poem-content $audioClass' >";
foreach ($data['content'] as $i => $li) {
	$start = $data['info']['audioIndex'][$i];
	$end = $data['info']['audioIndex'][$i+1];
	echo "<li><span>$li</span> <button class='btn btn-default btn-xs btn-audio' data-start='$start' data-end='$end' ><i class='glyphicon glyphicon-volume-down' ></i> </button></li>";
}
echo "</ul>";
echo "<hr>";
foreach (array('note' , 'rhymed' , 'comment') as $key) {
	echo "<pre>".$data['info'][$key]."</pre>";
	echo "<hr>";
}
$data['info']['url'] = $_SERVER['HTTP_HOST'];
?>
</div>
<script>
	var audio = "<?php echo $data['audio'] ;?>";
	$(function(){
		var player = new MyPlayer();
		player.set(audio);
		player.playMode(player.PLAY_MODE.one);

		$(".poem-content").on("click" , ".btn[data-start]" , function(){
			player.playFrom($(this).data("start") , $(this).data("end"))
		});

		$(".play-all").click(function(){
			player.playFrom(0);
		});
	})
</script>