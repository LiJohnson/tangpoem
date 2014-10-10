<div class="row tool">
	<div class="col-md-offset-1 col-md-2" >
		<form class="" action="?action=user"  method="post">
			<?php
			foreach ($adminId as $id) {
			?>
			<div class="form-group has-feedback">
				<label for="" class="control-label">
					<a href="//weibo.com/<?php echo $id; ?>" target="_blank" ><?php echo $id; ?></a>
				</label>
				<input class="form-control" type="number" name="adminId[]" value="<?php echo $id; ?>" placeholder="Enter id " readonly="true" required >
				<span class="glyphicon glyphicon-remove remove form-control-feedback"></span>

			 </div>
			<?php
			}
			?>
			<div class="form-group">
				<a class="btn btn-default add"><i class="glyphicon glyphicon-plus"></i></a>
			</div>
			<div class="form-group">
				<input type="submit" class="btn btn-primary" value="save">
			</div>
		</form>
	</div>
</div>

<script>
	$(function(){
		var $input = $("form > .form-group:eq(0)").clone();
		$input.find('input').val("").removeProp("readonly");
		$input.find("label").html("new");

		$("form").on("click" , ".remove" , function(){
			$(this).parents(".form-group").remove();
		}).on("click",".add" , function(){
			$(this).parent().before($input.clone());
		});
	});
</script>