<div class="row tool">
	<div class="col-md-offset-1 col-md-10" >
		<form class="form-horizontal" action="?action=tool"  method="post">
			<?php
			foreach ($data as $key => $value) {
				echo "<label for=$key>$key</label>";
				echo "<textarea name=$key class=form-control >$value</textarea>";
			}
			?>
			<input type="submit" class="btn btn-primary" value="save">
		</form>
	</div>
</div>