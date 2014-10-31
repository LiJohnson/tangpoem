<div class="row tool">
	<div class="col-md-offset-1 col-md-10" >
		<form class="form-horizontal" action="?action=tool"  method="post">
			<?php
			foreach ($data as $key => $value) {
				echo '<div class=form-group >';
				echo "<label for=$key>$key</label>";
				echo "<input type=text name=$key class=form-control value='$value' >";
				echo '</div>';
			}
			?>
			<div class=form-group >
				<input type="submit" class="btn btn-primary" value="save">
			</div>
		</form>
	</div>
</div>