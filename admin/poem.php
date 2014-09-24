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
		),
	array(
		'id' => 'audio',
		'type' => 'url',
		'text' => '音频'
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
		<div class="col-md-9" >
			<form class="form-horizontal" name="poem" >
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<input type="submit" name="submit" value="保存" class="btn btn-default" />
					</div>
				</div>
				<input type="hidden" name="poemId" />
				<?php
				foreach ($form as $input) {
					echo "<div class='form-group  has-feedback'>";
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

					if( $input['id'] == 'audio' ){
						echo "<span class='glyphicon glyphicon-volume-up form-control-feedback player'></span>";
					}
					
					echo "    </div>";
					echo "</div>";
				}
				?>
				<div class="form-group has-feedback">
					<label class="col-sm-1">
						上传 
					</label>
					<div class=" col-sm-10">
						<input type=file name=file />
					</div>
				</div>
				<div class="form-group has-feedback">
					<div class="col-sm-offset-1 col-sm-10">
						<ul class="list-group poem-audio-setting" >
							<li class="list-group-item" >
								
								<div class="input-group">
									<div class="input-group-addon">
										<span></span>
										<a href="javascript:;" class="glyphicon glyphicon-exclamation-sign"></a>
									</div>
									<input class="form-control col-md-2"  type="number" name="audioIndex[]" step="0.001">
									<i class='glyphicon glyphicon-volume-up form-control-feedback play-index'></i>
								</div>
							</li>
						</ul>
					</div>
				</div>
				<div class="form-group">
					<div class="col-sm-offset-1 col-sm-10">
						<button type="submit" class="btn btn-default">保存</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-1 format-tool">
			<ul class="list-group">
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-primary preview">
						预览
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-primary space">
						空格
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-primary replace">
						换行
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-primary split">
						拆分
					</a>
				</li>
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-danger reset">
						重置
					</a>
				</li>
			</ul>
		</div>
	</div>
</div>
<script>
$(function(){
	var $list = $("ul.poem-list");
	var $form = $("form[name=poem]");
	var $poemAudio = $("ul.poem-audio-setting");
	var $tmp = $poemAudio.find('li').remove();
	var playerUI = new MyPlayerUI();

	var loadPoemList = function(){
		$list.html("<li>loading</li>");

		$(".left form").postData("?action=loadPoemList&ajax=1",function(data){
			$list.empty();
			$.each(data, function(index,poem) {
				var $li = $("<li class='list-group-item' data-toggle='tooltip' data-placement='top' data-html=true ><a href='javascript:;' ><span html-title></span> - <span html-name ></span></a><a class=close >&times;</a></li>");
				$li.data(poem);
				$list.append($li.setHtml(poem));
			});
		});
	};

	var loadPoem = function(id){
		$.post("?action=loadPoem&ajax=1",{poemId:id},function(poem){
			poem.info.audioIndex = poem.info.audioIndex || [];
			$poemAudio.empty( );
			$.each(poem.content, function(index, li) {
				var $li = $tmp.clone();
				$li.find("span").html(li);
				$poemAudio.append($li);
				$li.find("input").val(poem.info.audioIndex[index]);
			});

			poem.content = poem.content.join("\n");
			$form.setData(poem).setData(poem.info);

			playerUI.player.set(poem.audio);

			updateUrl(poem.poemId);
		});
	};

	var upload = function(file){
		new $().uploadFile("?action=upload&ajax=1" ,{file:file,name:file.name},function(data){
			$form.find("input[name=audio]").val(encodeURI(data.url));
			$form.find("input[name=file]").val("");
		})
	};

	var updateUrl = function(poemId){
		var url = location.href;
		if (!location.search) {
			url += "?poemId=" + poemId;
		} else {
			if( url.match(/poemId=\d+/) ){
				url = url.replace(/poemId=\d+/,"poemId=" + poemId);
			}else{
				url += "&poemId=" + poemId;
			}
		}
		history.pushState({},0,url);
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
	}).on("click" , ".player" , function(){
		
		playerUI.player.isPlaying() ? 
		playerUI.player.pause() :
		playerUI.player.play();
	}).on("click",".poem-audio-setting a",function(){
		var $inputs = $form.find(".poem-audio.setting input");
		var $this = $(this).parents("li:eq(0)").find("input");
		var step = 0;
		$this.val(playerUI.player.audio.currentTime.toFixed(3));

		$input.each(function(i){
			if(i == 0)return;
		});
	}).on("click",".poem-audio-setting .play-index",function(){
		playerUI.player.playFrom( $(this).prev().val() );
	});

	$form.find("input[name=audio]").getFile(function(file){
		upload(file);
	});

	$form.find("input[type=file]").change(function(event) {
		upload(this.files[0]);
	});

	loadPoemList();
	$("body").append(playerUI.$player);
	playerUI.$player.drag({handle:".control"}).css("position","fixed");
	playerUI.player.playMode(playerUI.player.PLAY_MODE.one);

	<?php
	if( $_GET['poemId'] ){
		echo "loadPoem($_GET[poemId])";
	}
	?>
});
</script>

<script>
	$(function(){
		var $tool = $(".format-tool");
		var $textarea = false;

		$(document).on("focus","textarea",function(){
			$textarea = $(this);
		});

		$tool.on("click",".preview",function(){
			window.open("<?php echo SITE_URL ?>?action=detail&poemId=" +$("input[name=poemId]").val(),"_blank");
		}).on("click",".space",function(){
			var text = $textarea.val().replace(/\n/g,"##");
			$textarea.val(text.replace(/\s+/g,"").replace(/##/g,"\n"));
		}).on("click",".replace",function(){
			var text = $textarea.val().replace(/\n/g,"##");
			$textarea.val(text.replace(/\s+/g,"\n").replace(/##/g,"\n"));
		}).on("click",".split",function(){
			var text = $textarea.val().split("【");
			while( text.length ){
				var t = text.pop();
				if( t.indexOf("注解") == 0){
					$("form[name=poem] textarea[name=note]").val("【" + t);
				}else if( t.indexOf("韵译") == 0){
					$("form[name=poem] textarea[name=rhymed]").val("【" + t);
				}else if( t.indexOf("评析") == 0){
					$("form[name=poem] textarea[name=comment]").val("【" + t);
				}else{
					$("form[name=poem] textarea[name=content]").val(t.replace(/\s+/,"\n"));
				}
			}
		}).on("click",".reset",function(){
			location.reload();
		});
	});
</script>
