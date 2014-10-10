<?php 
$groupBy = $data['groupBy'];
?>
<div class="row">
	
	<form class="form-inline" action="?action=cate" role="form" >
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
		<div class="col-md-8">
			<input type="hidden" name="action" value="cate" />
			<div class="form-group">
				<lable for="type" >分类</label>
				<select name="type" id="type" class="form-control" >
					<option value="">全部</option>
					<?php 
					foreach ($data['types'] as $type) {
						echo "<option value='$type' ". ($_GET['type'] == $type ? 'selected' : '') ." >$type</option>";
					}
					?>
				</select>
			</div>

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
					echo "<div class='col-md-12'><h4><a href='?action=cate&$groupBy=$group' >$group</h4><hr></div>";
				}
				echo "<div class='col-md-3 poem-item' ><a href='?action=detail&poemId=$poem[poemId]' >$poem[title]</a></div>";
			}
		}else{
			echo "没有找到你要的哦~";	
		}
		?>
		</div>
	</div>
</div>

<script>
	$("form").on("change" , "input[type=radio]",function(){
		$("form").submit();
	});
</script>