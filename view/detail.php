
<div class='theme-1'>
<?php 
$audioClass =  preg_match('/^http/', $data['audio']) ? '' : 'no-audio';

echo "<h1 class='$audioClass' ><span class='play-all btn-audio'><i class='glyphicon glyphicon-volume-down' ></i></span>$data[title]<small>$data[name]</small></h1>";

if( checkAdmin() ){
	echo "<p><a href=admin/?action=poem&poemId=$data[poemId] target='_blank'>edit</a></p>";
}

echo "<ul class='list-unstyled poem-content $audioClass' >";
foreach ($data['content'] as $i => $li) {
	$start = $data['info']['audioIndex'][$i];
	$end = $data['info']['audioIndex'][$i+1];
	echo "<li><span>$li</span> <div class='btn-group' ><button class='btn btn-default btn-xs btn-audio' data-start='$start' data-end='$end' ><i class='glyphicon glyphicon-volume-down' ></i> </button>";
	echo "<button class='btn btn-default btn-xs btn-good' data-index='$i' data-poem-id='$poemId' ><i class='glyphicon glyphicon-thumbs-up' ></i>(<small>".formatNum($good[$i])."</small>)</button></div></li>";
}
echo "</ul>";
?>

<div class="row">
	<div class="col-md-4">
		<?php 
		if( $prev ){
			echo "<a class='btn btn-warning btn-xs' data-toggle='tooltip' data-placement='left' title='$prev[title]' href='?action=detail&poemId=$prev[poemId]' >上一首</a>";
		}
		?>	
	</div>
	<div class="col-md-4">
		<a href="#comment" class="btn btn-warning btn-xs" >评论</a>	
	</div>
	<div class="col-md-4">
		<?php 
		if( $next ){
			echo "<a class='btn btn-warning btn-xs' data-toggle='tooltip' data-placement='rigth' title='$next[title]' href='?action=detail&poemId=$next[poemId]' >下一首</a>";
		}
		?>	
	</div>
</div>

<?php
echo "<hr>";
foreach (array('note' , 'rhymed' , 'comment') as $key) {
	echo "<pre>".$data['info'][$key]."</pre>";
	echo "<hr>";
}
$data['info']['url'] = $_SERVER['HTTP_HOST'];
?>
<a href="" id="comment"></a>
<?php 
echo preg_replace('/url=\S+/', 'url="'.getPoemURL($data['poemId']).'"', $kv->get("comment"));
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

		$("[data-toggle=tooltip]").tooltip();

		$(".poem-content ").on( "click" , ".btn.btn-good:not(.active)" , function(){
			var $this = $(this);
			$this.addClass('active');
			$this.find('small').html( ( $this.find('small').text()*1 || 0 ) + 1 )

			$.post('?action=good',$this.data(),function(data){});
		} );
	})
</script>