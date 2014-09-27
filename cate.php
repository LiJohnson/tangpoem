<?php 
$types = ['五言','七言','古诗','乐府','绝句','律诗'];
$selected = is_array($_GET['type']) ? $_GET['type'] : [];
?>
<div class="row">
	<div class="col-md-12">
		<form class="form-inline" action=".?action=cate" role="form" >
			<input type="hidden" name="action" value="cate" />
			<div class="form-group"><div class="btn-group" data-toggle="buttons" >
			<?php 
			foreach ($types as $i => $type) {
				$checked = in_array($type, $selected) ? 'checked active' : '';
				echo "<label class='btn btn-default $checked'><input type='checkbox' name='type[]' value='$type' $checked >$type</label>";
			}
			?>
			</div></div>

			<div class="form-group"><input class="form-control" type="search" name="key" placeholder="作者 标题 内容" value="<?= $_GET['key'] ?>"></div>
			<button type="submit" class="btn btn-default">查找</button>
		</form>
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<ul class="list-group">
			<?php
			foreach ($data['authors'] as $author) {
				echo "<li class='list-group-item'><a href='?action=cate&author=$author[name]' data-author-id='$author[authorId]'>$author[name]</a></li>";
			}
			?>
		</ul>
	</div>
	<div class="col-md-10">
		<div class="row">
			<?php 
			if ( count($data['poems']) ){			
				foreach ($data['poems'] as $poem) {
					echo "<div class=col-md-4 ><a href='?action=detail&poemId=$poem[poemId]' >$poem[title]</a></div>";
				}	
			}else{
				echo "no match";	
			}
			?>
		</div>
	</div>
</div>