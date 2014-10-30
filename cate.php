<?php 
$groupBy = $data['groupBy'];
?>
<div class="row">
	
	<form class="form-inline cate" action="<?=getAction('cate')?>" role="form" >
		<div class="col-md-4" >
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-primary <?php echo $data['groupByType'] ;?>">
					<input type="radio" name="groupBy" value="type"<?php echo $data['groupByType'] ;?> >章节
				</label>
				<label class="btn btn-primary <?php echo $data['groupByName'] ;?>">
					<input type="radio" name="groupBy" value="name" <?php echo $data['groupByName'] ;?> >作者
				</label>
			</div>
		</div>
		<div class="col-md-8 hide">
			<div class="form-group">
				<input class="form-control" type="search" name="key" placeholder="作者 标题 内容" value="<?= $_GET['key'] ?>">
			</div>
			<button type="submit" class="btn btn-default">查找</button>
		</div>
	</form>
</div>
<div class="row poem-list">
	<div class="col-md-12">
		<div class="row">
		<?php 

		if ( count($data['poems']) ){
			$groups = array();			
			foreach ($data['poems'] as $poem) {
				$group = $poem[$groupBy];
				if( !in_array( $group , $groups) ){
					$groups[] = $group;
					echo "</div>";
					echo "<div class='row'>";
					echo "<div class='col-md-12'><h4><a href='". getAction('cate' , $groupBy.'='.$group) ."' >$group</a><small > (<span class='count-poem' ></span>)</small></h4><hr></div>";
				}
				echo "<div class='col-md-3 poem-item' ><a href='". getPoemURL($poem['poemId']) ."' >$poem[title]</a></div>";
			}
		}else{
			echo "没有找到你要的哦~";	
		}
		?>
		</div>
	</div>
</div>

<script>
	$("form.cate").on("change" , "input[type=radio]",function(){
		$("form.cate").submit();
	});
	$(function(){
		$(".poem-list .row").each(function(){
			$(this).find(".count-poem").html($(this).find("a").length-1);
		});
	})
</script>