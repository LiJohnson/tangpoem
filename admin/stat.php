<div class="row stat">
	<div class="col-md-offset-1 col-md-10" >
		<form class="form-horizontal" action="?action=stat"  method="post">
			<div class=form-group >
				<input type="submit" class="btn btn-primary" name="update" value="update">
			</div>
		</form>
		<pre>
			<?=json_encode($data)?>
		</pre>
	</div>
</div>