<?php 
//var_dump($data);
?>
<div class="row">
	<div class="col-md-12">
		<div></div>
	</div>
</div>
<div class="row">
	<div class="col-md-2">
		<ul class="list-group">
			<?php
			foreach ($data['authors'] as $author) {
				echo "<li class='list-group-item'><a href='javascript:;' data-author-id='$author[authorId]'>$author[name]</a></li>";
			}
			?>
		</ul>
	</div>
	<div class="col-md-10">
		<div class="row">
			<?php 
			foreach ($data['poems'] as $poem) {
				echo "<div class=col-md-4 ><a href='?action=detail&poemId=$poem[poemId]' >$poem[title]</a></div>";
			}
			?>
		</div>
	</div>
</div>