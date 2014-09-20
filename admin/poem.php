<?php
$form = array(
	array(
		'id' => 'title',
		'type' => 'text',
		'text' => '标题'
		),
	array(
		'id'=>'authorId',
		'type' => 'select',
		'text' => '作者',
		'data' => $data['authors']
		),
	array(
		'id' => 'content',
		'type' => 'textarea',
		'text' => '诗句'
		),
	array(
		'id' => 'audio',
		'type' => 'url',
		'text' => '音频'
		),
	array(
		'id' => 'type',
		'type' => 'select',
		'text' => '律句',
		'data' => $data['types']
		),
	array(
		'id' => 'rhymed',
		'type' => 'textarea',
		'text' => '韵译'
		),
	array(
		'id' => 'note',
		'type' => 'textarea',
		'text' => '注解'
		),
	array(
		'id' => 'comment',
		'type' => 'textarea',
		'text' => '评析'
		),
	array(
		'id' => 'url',
		'type' => 'url',
		'text' => '源'
		)
	);
?>
<div class="poem">
	<div class="row">
		<div class="col-md-2 left" >
			<a class="btn btn-default btn-block add-poem"><i class="glyphicon glyphicon-plus" ></i></a>
			<form>
				<select name="type" class="form-control">
					<option value="all">全部</option>
					<?php
					foreach ($data['types'] as $item) {
						echo "<option value='$item[key]'>$item[value]</option>";
					}
					?>
				</select>
				<input class="form-control" type="search" name="key" placeholder="搜索" />
			</form>
			<ul class="list-group poem-list" >
			</ul>
		</div>
		<div class="col-md-10" >
			<form class="form-horizontal" name="poem" >
				<input type="hidden" name="poemId" />
				<?php
				foreach ($form as $input) {
					echo "<div class='form-group'>";
					echo "    <label for='$input[id]' class='col-sm-1 control-label'>$input[text]</label>";
					echo "	<div class='col-sm-10'>";

					if( $input['type'] == 'textarea' ){
						echo "<textarea class='form-control' name='$input[id]' placeholder='$input[text]'></textarea>";
					}else if( $input['type'] == 'select' ){
						echo "<select class='form-control' name='$input[id]' readonly >";
						foreach ($input['data'] as $item) {
							echo "<option value='$item[key]'>$item[value]</option>";
						}
						echo "</select>";
					}else{
						echo "		<input type='text' name='$input[id]' class='form-control' id='$input[id]' placeholder='$input[text]'>";
					}
					
					echo "    </div>";
					echo "</div>";
				}
				?>

				<div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-default">commit</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
$(function(){
	var $list = $("ul.poem-list");
	var $form = $("form[name=poem]");

	var loadPoemList = function(){
		$list.html("<li>loading</li>");

		$(".left form").postData("?action=loadPoemList&ajax=1",function(data){
			$list.empty();
			$.each(data, function(index,poem) {
				var $li = $("<li class='list-group-item' data-toggle='tooltip' data-placement='top' data-html=true ><a href='javascript:;' ><span html-title></span> - <span html-name ></span></a><a class=close >&times;</a></li>");
				//$li.prop("title" , poem.content.join("<br>")).tooltip();
				$li.data(poem);
				$list.append($li.setHtml(poem));
			});
		});
	};

	var loadPoem = function(id){
		$.post("?action=loadPoem&ajax=1",{poemId:id},function(poem){
			poem.content = poem.content.join("\n");
			$form.setData(poem).setData(poem.info);
		});
	};

	$(".left form select").change(function(){
		loadPoemList();
	});

	$(".left form").submit(function(){
		loadPoemList();
		return false;
	});

	$(".left .btn.add-poem").click(function(event) {
		$.post("?action=addPoem",function(poem){
			loadPoemList();
			loadPoem(poem.poemId);
		});
	});

	$list.on("click","li",function(){
		loadPoem($(this).data("poemId"));
	});

	$list.on("click" , "li a.close" , function(){
		var param = {poemId:$(this).parent().data("poemId")};
		$.box({message:"确定",ok:function(){

			$.post("?action=deletePoem",param,function(){
				loadPoemList();
			});
		}});
	});
	$form.submit(function(event) {
		$form.postData("?action=updatePoem",function(data){
			if( data.result ){
				$.alertMessage("修改成功");
				loadPoem(data.poemId);
			}else{
				$.box("修改失败");
			}
		});
		return false;
	});
	loadPoemList();
});
</script>