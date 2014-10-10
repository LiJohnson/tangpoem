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
				<li class="list-group-item">
					<a href="javascript:;" class="btn btn-success save">
						保存
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
			poem.info.audio = poem.info.audio || ("h" + poem.title.substring(0,2) + " " ); 
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
		playerUI.player.play($(this).prev().val());
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
		}).on("click" , ".save" , function(){
			$("form[name=poem]").trigger('submit');
		});
	});
</script>

<link rel="stylesheet" href="http://ichord.github.io/At.js/dist/css/jquery.atwho.css">	
<script src="http://ichord.github.io/Caret.js/src/jquery.caret.js" ></script>
<script src="http://ichord.github.io/At.js/dist/js/jquery.atwho.js" ></script>
<script>
	(function(){
		var data = [{"name":"001感遇其一.mp3","href":"001%e6%84%9f%e9%81%87%e5%85%b6%e4%b8%80.mp3"},{"name":"002感遇其二.mp3","href":"002%e6%84%9f%e9%81%87%e5%85%b6%e4%ba%8c.mp3"},{"name":"003感遇其三.mp3","href":"003%e6%84%9f%e9%81%87%e5%85%b6%e4%b8%89.mp3"},{"name":"004感遇其四.mp3","href":"004%e6%84%9f%e9%81%87%e5%85%b6%e5%9b%9b.mp3"},{"name":"005下终南山过斛斯山人宿置酒.mp3","href":"005%e4%b8%8b%e7%bb%88%e5%8d%97%e5%b1%b1%e8%bf%87%e6%96%9b%e6%96%af%e5%b1%b1%e4%ba%ba%e5%ae%bf%e7%bd%ae%e9%85%92.mp3"},{"name":"006月下独酌.mp3","href":"006%e6%9c%88%e4%b8%8b%e7%8b%ac%e9%85%8c.mp3"},{"name":"007春思（李白）.mp3","href":"007%e6%98%a5%e6%80%9d%ef%bc%88%e6%9d%8e%e7%99%bd%ef%bc%89.mp3"},{"name":"008望岳（杜甫）.mp3","href":"008%e6%9c%9b%e5%b2%b3%ef%bc%88%e6%9d%9c%e7%94%ab%ef%bc%89.mp3"},{"name":"009赠卫八处士.mp3","href":"009%e8%b5%a0%e5%8d%ab%e5%85%ab%e5%a4%84%e5%a3%ab.mp3"},{"name":"010佳人.mp3","href":"010%e4%bd%b3%e4%ba%ba.mp3"},{"name":"011梦李白.mp3","href":"011%e6%a2%a6%e6%9d%8e%e7%99%bd.mp3"},{"name":"012梦李白（杜甫）.mp3","href":"012%e6%a2%a6%e6%9d%8e%e7%99%bd%ef%bc%88%e6%9d%9c%e7%94%ab%ef%bc%89.mp3"},{"name":"013送綦毋潜落第还乡.mp3","href":"013%e9%80%81%e7%b6%a6%e6%af%8b%e6%bd%9c%e8%90%bd%e7%ac%ac%e8%bf%98%e4%b9%a1.mp3"},{"name":"014送别（王维）.mp3","href":"014%e9%80%81%e5%88%ab%ef%bc%88%e7%8e%8b%e7%bb%b4%ef%bc%89.mp3"},{"name":"015青溪（王维）.mp3","href":"015%e9%9d%92%e6%ba%aa%ef%bc%88%e7%8e%8b%e7%bb%b4%ef%bc%89.mp3"},{"name":"016渭川田家（王维）.mp3","href":"016%e6%b8%ad%e5%b7%9d%e7%94%b0%e5%ae%b6%ef%bc%88%e7%8e%8b%e7%bb%b4%ef%bc%89.mp3"},{"name":"017西施咏（王维）.mp3","href":"017%e8%a5%bf%e6%96%bd%e5%92%8f%ef%bc%88%e7%8e%8b%e7%bb%b4%ef%bc%89.mp3"},{"name":"018秋登兰山寄张五.mp3","href":"018%e7%a7%8b%e7%99%bb%e5%85%b0%e5%b1%b1%e5%af%84%e5%bc%a0%e4%ba%94.mp3"},{"name":"019夏日南亭怀辛大.mp3","href":"019%e5%a4%8f%e6%97%a5%e5%8d%97%e4%ba%ad%e6%80%80%e8%be%9b%e5%a4%a7.mp3"},{"name":"020宿业师山房待丁大不至.mp3","href":"020%e5%ae%bf%e4%b8%9a%e5%b8%88%e5%b1%b1%e6%88%bf%e5%be%85%e4%b8%81%e5%a4%a7%e4%b8%8d%e8%87%b3.mp3"},{"name":"021同从弟南斋玩月.mp3","href":"021%e5%90%8c%e4%bb%8e%e5%bc%9f%e5%8d%97%e6%96%8b%e7%8e%a9%e6%9c%88.mp3"},{"name":"022寻西山隐者不遇（丘为）.mp3","href":"022%e5%af%bb%e8%a5%bf%e5%b1%b1%e9%9a%90%e8%80%85%e4%b8%8d%e9%81%87%ef%bc%88%e4%b8%98%e4%b8%ba%ef%bc%89.mp3"},{"name":"023春泛若耶溪.mp3","href":"023%e6%98%a5%e6%b3%9b%e8%8b%a5%e8%80%b6%e6%ba%aa.mp3"},{"name":"024宿王昌龄隐居.mp3","href":"024%e5%ae%bf%e7%8e%8b%e6%98%8c%e9%be%84%e9%9a%90%e5%b1%85.mp3"},{"name":"025与高适薛据登慈恩寺浮图.mp3","href":"025%e4%b8%8e%e9%ab%98%e9%80%82%e8%96%9b%e6%8d%ae%e7%99%bb%e6%85%88%e6%81%a9%e5%af%ba%e6%b5%ae%e5%9b%be.mp3"},{"name":"026贼退示官吏并序.mp3","href":"026%e8%b4%bc%e9%80%80%e7%a4%ba%e5%ae%98%e5%90%8f%e5%b9%b6%e5%ba%8f.mp3"},{"name":"027郡斋雨中与诸文士燕集.mp3","href":"027%e9%83%a1%e6%96%8b%e9%9b%a8%e4%b8%ad%e4%b8%8e%e8%af%b8%e6%96%87%e5%a3%ab%e7%87%95%e9%9b%86.mp3"},{"name":"028初发扬子寄元大校书.mp3","href":"028%e5%88%9d%e5%8f%91%e6%89%ac%e5%ad%90%e5%af%84%e5%85%83%e5%a4%a7%e6%a0%a1%e4%b9%a6.mp3"},{"name":"029寄全椒山中道士.mp3","href":"029%e5%af%84%e5%85%a8%e6%a4%92%e5%b1%b1%e4%b8%ad%e9%81%93%e5%a3%ab.mp3"},{"name":"030长安遇冯著.mp3","href":"030%e9%95%bf%e5%ae%89%e9%81%87%e5%86%af%e8%91%97.mp3"},{"name":"031夕次盱眙县.mp3","href":"031%e5%a4%95%e6%ac%a1%e7%9b%b1%e7%9c%99%e5%8e%bf.mp3"},{"name":"032东郊.mp3","href":"032%e4%b8%9c%e9%83%8a.mp3"},{"name":"034晨诣超师院读禅经.mp3","href":"034%e6%99%a8%e8%af%a3%e8%b6%85%e5%b8%88%e9%99%a2%e8%af%bb%e7%a6%85%e7%bb%8f.mp3"},{"name":"035溪居.mp3","href":"035%e6%ba%aa%e5%b1%85.mp3"},{"name":"036塞上曲之一_王昌龄.mp3","href":"036%e5%a1%9e%e4%b8%8a%e6%9b%b2%e4%b9%8b%e4%b8%80_%e7%8e%8b%e6%98%8c%e9%be%84.mp3"},{"name":"037塞下曲之二_王昌龄.mp3","href":"037%e5%a1%9e%e4%b8%8b%e6%9b%b2%e4%b9%8b%e4%ba%8c_%e7%8e%8b%e6%98%8c%e9%be%84.mp3"},{"name":"038关山月.mp3","href":"038%e5%85%b3%e5%b1%b1%e6%9c%88.mp3"},{"name":"039子夜春歌.mp3","href":"039%e5%ad%90%e5%a4%9c%e6%98%a5%e6%ad%8c.mp3"},{"name":"040子夜夏歌.mp3","href":"040%e5%ad%90%e5%a4%9c%e5%a4%8f%e6%ad%8c.mp3"},{"name":"041子夜秋歌.mp3","href":"041%e5%ad%90%e5%a4%9c%e7%a7%8b%e6%ad%8c.mp3"},{"name":"042子夜冬歌.mp3","href":"042%e5%ad%90%e5%a4%9c%e5%86%ac%e6%ad%8c.mp3"},{"name":"043长干行（李白）其一.mp3","href":"043%e9%95%bf%e5%b9%b2%e8%a1%8c%ef%bc%88%e6%9d%8e%e7%99%bd%ef%bc%89%e5%85%b6%e4%b8%80.mp3"},{"name":"044长干行（李白）其二.mp3","href":"044%e9%95%bf%e5%b9%b2%e8%a1%8c%ef%bc%88%e6%9d%8e%e7%99%bd%ef%bc%89%e5%85%b6%e4%ba%8c.mp3"},{"name":"045烈女操.mp3","href":"045%e7%83%88%e5%a5%b3%e6%93%8d.mp3"},{"name":"046游子吟.mp3","href":"046%e6%b8%b8%e5%ad%90%e5%90%9f.mp3"},{"name":"047登幽州台歌.mp3","href":"047%e7%99%bb%e5%b9%bd%e5%b7%9e%e5%8f%b0%e6%ad%8c.mp3"},{"name":"048古意.mp3","href":"048%e5%8f%a4%e6%84%8f.mp3"},{"name":"049送陈章甫.mp3","href":"049%e9%80%81%e9%99%88%e7%ab%a0%e7%94%ab.mp3"},{"name":"050琴歌.mp3","href":"050%e7%90%b4%e6%ad%8c.mp3"},{"name":"051听董大弹胡笳兼寄语弄房给事.mp3","href":"051%e5%90%ac%e8%91%a3%e5%a4%a7%e5%bc%b9%e8%83%a1%e7%ac%b3%e5%85%bc%e5%af%84%e8%af%ad%e5%bc%84%e6%88%bf%e7%bb%99%e4%ba%8b.mp3"},{"name":"052听安万善吹筚篥歌.mp3","href":"052%e5%90%ac%e5%ae%89%e4%b8%87%e5%96%84%e5%90%b9%e7%ad%9a%e7%af%a5%e6%ad%8c.mp3"},{"name":"053夜归鹿门歌.mp3","href":"053%e5%a4%9c%e5%bd%92%e9%b9%bf%e9%97%a8%e6%ad%8c.mp3"},{"name":"054庐山谣寄卢侍御虚舟.mp3","href":"054%e5%ba%90%e5%b1%b1%e8%b0%a3%e5%af%84%e5%8d%a2%e4%be%8d%e5%be%a1%e8%99%9a%e8%88%9f.mp3"},{"name":"055梦游天姥吟留别.mp3","href":"055%e6%a2%a6%e6%b8%b8%e5%a4%a9%e5%a7%a5%e5%90%9f%e7%95%99%e5%88%ab.mp3"},{"name":"056金陵酒肆留别.mp3","href":"056%e9%87%91%e9%99%b5%e9%85%92%e8%82%86%e7%95%99%e5%88%ab.mp3"},{"name":"057宣州谢眺楼.mp3","href":"057%e5%ae%a3%e5%b7%9e%e8%b0%a2%e7%9c%ba%e6%a5%bc.mp3"},{"name":"058走马川行.mp3","href":"058%e8%b5%b0%e9%a9%ac%e5%b7%9d%e8%a1%8c.mp3"},{"name":"059轮台歌奉送.mp3","href":"059%e8%bd%ae%e5%8f%b0%e6%ad%8c%e5%a5%89%e9%80%81.mp3"},{"name":"060白雪歌送武.mp3","href":"060%e7%99%bd%e9%9b%aa%e6%ad%8c%e9%80%81%e6%ad%a6.mp3"},{"name":"061韦讽录事宅.mp3","href":"061%e9%9f%a6%e8%ae%bd%e5%bd%95%e4%ba%8b%e5%ae%85.mp3"},{"name":"062丹青引赠曹将军霸.mp3","href":"062%e4%b8%b9%e9%9d%92%e5%bc%95%e8%b5%a0%e6%9b%b9%e5%b0%86%e5%86%9b%e9%9c%b8.mp3"},{"name":"063寄韩建议.mp3","href":"063%e5%af%84%e9%9f%a9%e5%bb%ba%e8%ae%ae.mp3"},{"name":"064古柏行.mp3","href":"064%e5%8f%a4%e6%9f%8f%e8%a1%8c.mp3"},{"name":"065观公孙大娘弟子.mp3","href":"065%e8%a7%82%e5%85%ac%e5%ad%99%e5%a4%a7%e5%a8%98%e5%bc%9f%e5%ad%90.mp3"},{"name":"066石鱼湖上醉歌.mp3","href":"066%e7%9f%b3%e9%b1%bc%e6%b9%96%e4%b8%8a%e9%86%89%e6%ad%8c.mp3"},{"name":"067山石.mp3","href":"067%e5%b1%b1%e7%9f%b3.mp3"},{"name":"068八月十五夜赠张功曹.mp3","href":"068%e5%85%ab%e6%9c%88%e5%8d%81%e4%ba%94%e5%a4%9c%e8%b5%a0%e5%bc%a0%e5%8a%9f%e6%9b%b9.mp3"},{"name":"069谒衡岳庙遂宿岳寺题门楼.mp3","href":"069%e8%b0%92%e8%a1%a1%e5%b2%b3%e5%ba%99%e9%81%82%e5%ae%bf%e5%b2%b3%e5%af%ba%e9%a2%98%e9%97%a8%e6%a5%bc.mp3"},{"name":"070石鼓歌.mp3","href":"070%e7%9f%b3%e9%bc%93%e6%ad%8c.mp3"},{"name":"071渔翁.mp3","href":"071%e6%b8%94%e7%bf%81.mp3"},{"name":"072长恨歌.mp3","href":"072%e9%95%bf%e6%81%a8%e6%ad%8c.mp3"},{"name":"073琵琶行.mp3","href":"073%e7%90%b5%e7%90%b6%e8%a1%8c.mp3"},{"name":"074韩碑.mp3","href":"074%e9%9f%a9%e7%a2%91.mp3"},{"name":"075燕歌行并序.mp3","href":"075%e7%87%95%e6%ad%8c%e8%a1%8c%e5%b9%b6%e5%ba%8f.mp3"},{"name":"076古从军行.mp3","href":"076%e5%8f%a4%e4%bb%8e%e5%86%9b%e8%a1%8c.mp3"},{"name":"077洛阳女儿行.mp3","href":"077%e6%b4%9b%e9%98%b3%e5%a5%b3%e5%84%bf%e8%a1%8c.mp3"},{"name":"078老将行.mp3","href":"078%e8%80%81%e5%b0%86%e8%a1%8c.mp3"},{"name":"079桃源行.mp3","href":"079%e6%a1%83%e6%ba%90%e8%a1%8c.mp3"},{"name":"080蜀道难.mp3","href":"080%e8%9c%80%e9%81%93%e9%9a%be.mp3"},{"name":"081长相思之一.mp3","href":"081%e9%95%bf%e7%9b%b8%e6%80%9d%e4%b9%8b%e4%b8%80.mp3"},{"name":"082长相思之二.mp3","href":"082%e9%95%bf%e7%9b%b8%e6%80%9d%e4%b9%8b%e4%ba%8c.mp3"},{"name":"083行路难之一.mp3","href":"083%e8%a1%8c%e8%b7%af%e9%9a%be%e4%b9%8b%e4%b8%80.mp3"},{"name":"084行路难之二.mp3","href":"084%e8%a1%8c%e8%b7%af%e9%9a%be%e4%b9%8b%e4%ba%8c.mp3"},{"name":"085行路难之三.mp3","href":"085%e8%a1%8c%e8%b7%af%e9%9a%be%e4%b9%8b%e4%b8%89.mp3"},{"name":"086将进酒.mp3","href":"086%e5%b0%86%e8%bf%9b%e9%85%92.mp3"},{"name":"087兵车行.mp3","href":"087%e5%85%b5%e8%bd%a6%e8%a1%8c.mp3"},{"name":"088丽人行.mp3","href":"088%e4%b8%bd%e4%ba%ba%e8%a1%8c.mp3"},{"name":"089哀江头.mp3","href":"089%e5%93%80%e6%b1%9f%e5%a4%b4.mp3"},{"name":"090哀王孙.mp3","href":"090%e5%93%80%e7%8e%8b%e5%ad%99.mp3"},{"name":"091经邹鲁祭孔子而叹之.mp3","href":"091%e7%bb%8f%e9%82%b9%e9%b2%81%e7%a5%ad%e5%ad%94%e5%ad%90%e8%80%8c%e5%8f%b9%e4%b9%8b.mp3"},{"name":"092望月怀远.mp3","href":"092%e6%9c%9b%e6%9c%88%e6%80%80%e8%bf%9c.mp3"},{"name":"093送杜少甫之任蜀州.mp3","href":"093%e9%80%81%e6%9d%9c%e5%b0%91%e7%94%ab%e4%b9%8b%e4%bb%bb%e8%9c%80%e5%b7%9e.mp3"},{"name":"094在狱咏蝉并序.mp3","href":"094%e5%9c%a8%e7%8b%b1%e5%92%8f%e8%9d%89%e5%b9%b6%e5%ba%8f.mp3"},{"name":"095和晋陵陆丞相早春游望.mp3","href":"095%e5%92%8c%e6%99%8b%e9%99%b5%e9%99%86%e4%b8%9e%e7%9b%b8%e6%97%a9%e6%98%a5%e6%b8%b8%e6%9c%9b.mp3"},{"name":"096杂诗.mp3","href":"096%e6%9d%82%e8%af%97.mp3"},{"name":"097题大庾岭北驿.mp3","href":"097%e9%a2%98%e5%a4%a7%e5%ba%be%e5%b2%ad%e5%8c%97%e9%a9%bf.mp3"},{"name":"098次北固山下.mp3","href":"098%e6%ac%a1%e5%8c%97%e5%9b%ba%e5%b1%b1%e4%b8%8b.mp3"},{"name":"099题破山寺后禅院.mp3","href":"099%e9%a2%98%e7%a0%b4%e5%b1%b1%e5%af%ba%e5%90%8e%e7%a6%85%e9%99%a2.mp3"},{"name":"100寄左省杜拾遗.mp3","href":"100%e5%af%84%e5%b7%a6%e7%9c%81%e6%9d%9c%e6%8b%be%e9%81%97.mp3"},{"name":"101赠孟浩然.mp3","href":"101%e8%b5%a0%e5%ad%9f%e6%b5%a9%e7%84%b6.mp3"},{"name":"102渡荆门送别.mp3","href":"102%e6%b8%a1%e8%8d%86%e9%97%a8%e9%80%81%e5%88%ab.mp3"},{"name":"103送友人.mp3","href":"103%e9%80%81%e5%8f%8b%e4%ba%ba.mp3"},{"name":"104听蜀僧浚弹琴.mp3","href":"104%e5%90%ac%e8%9c%80%e5%83%a7%e6%b5%9a%e5%bc%b9%e7%90%b4.mp3"},{"name":"105夜泊牛渚怀古.mp3","href":"105%e5%a4%9c%e6%b3%8a%e7%89%9b%e6%b8%9a%e6%80%80%e5%8f%a4.mp3"},{"name":"106春望.mp3","href":"106%e6%98%a5%e6%9c%9b.mp3"},{"name":"107月夜.mp3","href":"107%e6%9c%88%e5%a4%9c.mp3"},{"name":"108春宿左省.mp3","href":"108%e6%98%a5%e5%ae%bf%e5%b7%a6%e7%9c%81.mp3"},{"name":"109至德二载甫自京金光门出.mp3","href":"109%e8%87%b3%e5%be%b7%e4%ba%8c%e8%bd%bd%e7%94%ab%e8%87%aa%e4%ba%ac%e9%87%91%e5%85%89%e9%97%a8%e5%87%ba.mp3"},{"name":"110月夜忆舍弟.mp3","href":"110%e6%9c%88%e5%a4%9c%e5%bf%86%e8%88%8d%e5%bc%9f.mp3"},{"name":"111天末怀李白.mp3","href":"111%e5%a4%a9%e6%9c%ab%e6%80%80%e6%9d%8e%e7%99%bd.mp3"},{"name":"112奉济驿重送严公四韵.mp3","href":"112%e5%a5%89%e6%b5%8e%e9%a9%bf%e9%87%8d%e9%80%81%e4%b8%a5%e5%85%ac%e5%9b%9b%e9%9f%b5.mp3"},{"name":"113别房太尉墓.mp3","href":"113%e5%88%ab%e6%88%bf%e5%a4%aa%e5%b0%89%e5%a2%93.mp3"},{"name":"114旅夜抒怀.mp3","href":"114%e6%97%85%e5%a4%9c%e6%8a%92%e6%80%80.mp3"},{"name":"115登岳阳楼.mp3","href":"115%e7%99%bb%e5%b2%b3%e9%98%b3%e6%a5%bc.mp3"},{"name":"116辋川闲居赠裴秀才迪 .mp3","href":"116%e8%be%8b%e5%b7%9d%e9%97%b2%e5%b1%85%e8%b5%a0%e8%a3%b4%e7%a7%80%e6%89%8d%e8%bf%aa%20.mp3"},{"name":"117山居秋暝.mp3","href":"117%e5%b1%b1%e5%b1%85%e7%a7%8b%e6%9a%9d.mp3"},{"name":"118归嵩山作.mp3","href":"118%e5%bd%92%e5%b5%a9%e5%b1%b1%e4%bd%9c.mp3"},{"name":"119终南山.mp3","href":"119%e7%bb%88%e5%8d%97%e5%b1%b1.mp3"},{"name":"120酬张少府 .mp3","href":"120%e9%85%ac%e5%bc%a0%e5%b0%91%e5%ba%9c%20.mp3"},{"name":"121过香积寺.mp3","href":"121%e8%bf%87%e9%a6%99%e7%a7%af%e5%af%ba.mp3"},{"name":"122送梓州李使君.mp3","href":"122%e9%80%81%e6%a2%93%e5%b7%9e%e6%9d%8e%e4%bd%bf%e5%90%9b.mp3"},{"name":"123汉江临眺.mp3","href":"123%e6%b1%89%e6%b1%9f%e4%b8%b4%e7%9c%ba.mp3"},{"name":"124终南别业.mp3","href":"124%e7%bb%88%e5%8d%97%e5%88%ab%e4%b8%9a.mp3"},{"name":"125临洞庭上张丞相.mp3","href":"125%e4%b8%b4%e6%b4%9e%e5%ba%ad%e4%b8%8a%e5%bc%a0%e4%b8%9e%e7%9b%b8.mp3"},{"name":"126与诸子登岘山.mp3","href":"126%e4%b8%8e%e8%af%b8%e5%ad%90%e7%99%bb%e5%b2%98%e5%b1%b1.mp3"},{"name":"127宴梅道士山房.mp3","href":"127%e5%ae%b4%e6%a2%85%e9%81%93%e5%a3%ab%e5%b1%b1%e6%88%bf.mp3"},{"name":"128岁暮归南山.mp3","href":"128%e5%b2%81%e6%9a%ae%e5%bd%92%e5%8d%97%e5%b1%b1.mp3"},{"name":"129过故人庄.mp3","href":"129%e8%bf%87%e6%95%85%e4%ba%ba%e5%ba%84.mp3"},{"name":"130秦中寄远上人.mp3","href":"130%e7%a7%a6%e4%b8%ad%e5%af%84%e8%bf%9c%e4%b8%8a%e4%ba%ba.mp3"},{"name":"131宿桐庐江寄广陵旧游.mp3","href":"131%e5%ae%bf%e6%a1%90%e5%ba%90%e6%b1%9f%e5%af%84%e5%b9%bf%e9%99%b5%e6%97%a7%e6%b8%b8.mp3"},{"name":"132留别王维.mp3","href":"132%e7%95%99%e5%88%ab%e7%8e%8b%e7%bb%b4.mp3"},{"name":"133早寒有怀.mp3","href":"133%e6%97%a9%e5%af%92%e6%9c%89%e6%80%80.mp3"},{"name":"134秋日登吴公台上寺远眺.mp3","href":"134%e7%a7%8b%e6%97%a5%e7%99%bb%e5%90%b4%e5%85%ac%e5%8f%b0%e4%b8%8a%e5%af%ba%e8%bf%9c%e7%9c%ba.mp3"},{"name":"135送李中丞归汉阳别业.mp3","href":"135%e9%80%81%e6%9d%8e%e4%b8%ad%e4%b8%9e%e5%bd%92%e6%b1%89%e9%98%b3%e5%88%ab%e4%b8%9a.mp3"},{"name":"136饯别王十一南游.mp3","href":"136%e9%a5%af%e5%88%ab%e7%8e%8b%e5%8d%81%e4%b8%80%e5%8d%97%e6%b8%b8.mp3"},{"name":"137寻南溪常道士.mp3","href":"137%e5%af%bb%e5%8d%97%e6%ba%aa%e5%b8%b8%e9%81%93%e5%a3%ab.mp3"},{"name":"138新年作.mp3","href":"138%e6%96%b0%e5%b9%b4%e4%bd%9c.mp3"},{"name":"139送僧归日本.mp3","href":"139%e9%80%81%e5%83%a7%e5%bd%92%e6%97%a5%e6%9c%ac.mp3"},{"name":"140谷口书斋寄杨补阙.mp3","href":"140%e8%b0%b7%e5%8f%a3%e4%b9%a6%e6%96%8b%e5%af%84%e6%9d%a8%e8%a1%a5%e9%98%99.mp3"},{"name":"141淮上喜会梁州故人.mp3","href":"141%e6%b7%ae%e4%b8%8a%e5%96%9c%e4%bc%9a%e6%a2%81%e5%b7%9e%e6%95%85%e4%ba%ba.mp3"},{"name":"142赋得暮雨送李曹.mp3","href":"142%e8%b5%8b%e5%be%97%e6%9a%ae%e9%9b%a8%e9%80%81%e6%9d%8e%e6%9b%b9.mp3"},{"name":"143酬程延秋夜即事见赠.mp3","href":"143%e9%85%ac%e7%a8%8b%e5%bb%b6%e7%a7%8b%e5%a4%9c%e5%8d%b3%e4%ba%8b%e8%a7%81%e8%b5%a0.mp3"},{"name":"144阙题.mp3","href":"144%e9%98%99%e9%a2%98.mp3"},{"name":"145江乡故人偶集客舍.mp3","href":"145%e6%b1%9f%e4%b9%a1%e6%95%85%e4%ba%ba%e5%81%b6%e9%9b%86%e5%ae%a2%e8%88%8d.mp3"},{"name":"146送李端.mp3","href":"146%e9%80%81%e6%9d%8e%e7%ab%af.mp3"},{"name":"147喜见外弟又言别.mp3","href":"147%e5%96%9c%e8%a7%81%e5%a4%96%e5%bc%9f%e5%8f%88%e8%a8%80%e5%88%ab.mp3"},{"name":"148云阳馆与外弟宿别.mp3","href":"148%e4%ba%91%e9%98%b3%e9%a6%86%e4%b8%8e%e5%a4%96%e5%bc%9f%e5%ae%bf%e5%88%ab.mp3"},{"name":"149喜外弟卢伦见宿.mp3","href":"149%e5%96%9c%e5%a4%96%e5%bc%9f%e5%8d%a2%e4%bc%a6%e8%a7%81%e5%ae%bf.mp3"},{"name":"150贼平后送人北归.mp3","href":"150%e8%b4%bc%e5%b9%b3%e5%90%8e%e9%80%81%e4%ba%ba%e5%8c%97%e5%bd%92.mp3"},{"name":"151蜀先主庙.mp3","href":"151%e8%9c%80%e5%85%88%e4%b8%bb%e5%ba%99.mp3"},{"name":"152没蕃故人.mp3","href":"152%e6%b2%a1%e8%95%83%e6%95%85%e4%ba%ba.mp3"},{"name":"153草.mp3","href":"153%e8%8d%89.mp3"},{"name":"154旅宿.mp3","href":"154%e6%97%85%e5%ae%bf.mp3"},{"name":"155秋日赴阙题潼关楼.mp3","href":"155%e7%a7%8b%e6%97%a5%e8%b5%b4%e9%98%99%e9%a2%98%e6%bd%bc%e5%85%b3%e6%a5%bc.mp3"},{"name":"156早秋.mp3","href":"156%e6%97%a9%e7%a7%8b.mp3"},{"name":"157蝉.mp3","href":"157%e8%9d%89.mp3"},{"name":"158风雨.mp3","href":"158%e9%a3%8e%e9%9b%a8.mp3"},{"name":"159落花.mp3","href":"159%e8%90%bd%e8%8a%b1.mp3"},{"name":"160凉思.mp3","href":"160%e5%87%89%e6%80%9d.mp3"},{"name":"161北青萝.mp3","href":"161%e5%8c%97%e9%9d%92%e8%90%9d.mp3"},{"name":"162送人东游.mp3","href":"162%e9%80%81%e4%ba%ba%e4%b8%9c%e6%b8%b8.mp3"},{"name":"163灞上秋居.mp3","href":"163%e7%81%9e%e4%b8%8a%e7%a7%8b%e5%b1%85.mp3"},{"name":"164楚江怀古.mp3","href":"164%e6%a5%9a%e6%b1%9f%e6%80%80%e5%8f%a4.mp3"},{"name":"165书边事.mp3","href":"165%e4%b9%a6%e8%be%b9%e4%ba%8b.mp3"},{"name":"166除夜有怀.mp3","href":"166%e9%99%a4%e5%a4%9c%e6%9c%89%e6%80%80.mp3"},{"name":"167孤雁.mp3","href":"167%e5%ad%a4%e9%9b%81.mp3"},{"name":"168春宫怨.mp3","href":"168%e6%98%a5%e5%ae%ab%e6%80%a8.mp3"},{"name":"169章台夜思.mp3","href":"169%e7%ab%a0%e5%8f%b0%e5%a4%9c%e6%80%9d.mp3"},{"name":"170寻陆鸿渐不遇.mp3","href":"170%e5%af%bb%e9%99%86%e9%b8%bf%e6%b8%90%e4%b8%8d%e9%81%87.mp3"},{"name":"171黄鹤楼.mp3","href":"171%e9%bb%84%e9%b9%a4%e6%a5%bc.mp3"},{"name":"172行经华阴.mp3","href":"172%e8%a1%8c%e7%bb%8f%e5%8d%8e%e9%98%b4.mp3"},{"name":"173望蓟门.mp3","href":"173%e6%9c%9b%e8%93%9f%e9%97%a8.mp3"},{"name":"174九日登望仙台呈刘明府容.mp3","href":"174%e4%b9%9d%e6%97%a5%e7%99%bb%e6%9c%9b%e4%bb%99%e5%8f%b0%e5%91%88%e5%88%98%e6%98%8e%e5%ba%9c%e5%ae%b9.mp3"},{"name":"175送魏万之京.mp3","href":"175%e9%80%81%e9%ad%8f%e4%b8%87%e4%b9%8b%e4%ba%ac.mp3"},{"name":"176登金陵凤凰台.mp3","href":"176%e7%99%bb%e9%87%91%e9%99%b5%e5%87%a4%e5%87%b0%e5%8f%b0.mp3"},{"name":"177送李少府贬峡中王少府贬长沙.mp3","href":"177%e9%80%81%e6%9d%8e%e5%b0%91%e5%ba%9c%e8%b4%ac%e5%b3%a1%e4%b8%ad%e7%8e%8b%e5%b0%91%e5%ba%9c%e8%b4%ac%e9%95%bf%e6%b2%99.mp3"},{"name":"178岑参和贾至舍人早朝大明宫之作.mp3","href":"178%e5%b2%91%e5%8f%82%e5%92%8c%e8%b4%be%e8%87%b3%e8%88%8d%e4%ba%ba%e6%97%a9%e6%9c%9d%e5%a4%a7%e6%98%8e%e5%ae%ab%e4%b9%8b%e4%bd%9c.mp3"},{"name":"179王维和贾至舍人早朝大明宫之作.mp3","href":"179%e7%8e%8b%e7%bb%b4%e5%92%8c%e8%b4%be%e8%87%b3%e8%88%8d%e4%ba%ba%e6%97%a9%e6%9c%9d%e5%a4%a7%e6%98%8e%e5%ae%ab%e4%b9%8b%e4%bd%9c.mp3"},{"name":"180奉和圣制从蓬莱向兴庆阁道中留春雨中春望之作应制.mp3","href":"180%e5%a5%89%e5%92%8c%e5%9c%a3%e5%88%b6%e4%bb%8e%e8%93%ac%e8%8e%b1%e5%90%91%e5%85%b4%e5%ba%86%e9%98%81%e9%81%93%e4%b8%ad%e7%95%99%e6%98%a5%e9%9b%a8%e4%b8%ad%e6%98%a5%e6%9c%9b%e4%b9%8b%e4%bd%9c%e5%ba%94%e5%88%b6.mp3"},{"name":"181积雨辋川庄作.mp3","href":"181%e7%a7%af%e9%9b%a8%e8%be%8b%e5%b7%9d%e5%ba%84%e4%bd%9c.mp3"},{"name":"182酬郭给事.mp3","href":"182%e9%85%ac%e9%83%ad%e7%bb%99%e4%ba%8b.mp3"},{"name":"183蜀相.mp3","href":"183%e8%9c%80%e7%9b%b8.mp3"},{"name":"184客至.mp3","href":"184%e5%ae%a2%e8%87%b3.mp3"},{"name":"185野望.mp3","href":"185%e9%87%8e%e6%9c%9b.mp3"},{"name":"186闻官军收河南河北.mp3","href":"186%e9%97%bb%e5%ae%98%e5%86%9b%e6%94%b6%e6%b2%b3%e5%8d%97%e6%b2%b3%e5%8c%97.mp3"},{"name":"187登高.mp3","href":"187%e7%99%bb%e9%ab%98.mp3"},{"name":"188登楼.mp3","href":"188%e7%99%bb%e6%a5%bc.mp3"},{"name":"189宿府.mp3","href":"189%e5%ae%bf%e5%ba%9c.mp3"},{"name":"190阁夜.mp3","href":"190%e9%98%81%e5%a4%9c.mp3"},{"name":"191咏怀古迹支离东北.mp3","href":"191%e5%92%8f%e6%80%80%e5%8f%a4%e8%bf%b9%e6%94%af%e7%a6%bb%e4%b8%9c%e5%8c%97.mp3"},{"name":"192咏怀古迹摇落深知.mp3","href":"192%e5%92%8f%e6%80%80%e5%8f%a4%e8%bf%b9%e6%91%87%e8%90%bd%e6%b7%b1%e7%9f%a5.mp3"},{"name":"193咏怀古迹群山万壑.mp3","href":"193%e5%92%8f%e6%80%80%e5%8f%a4%e8%bf%b9%e7%be%a4%e5%b1%b1%e4%b8%87%e5%a3%91.mp3"},{"name":"194咏怀古迹蜀主窥吴.mp3","href":"194%e5%92%8f%e6%80%80%e5%8f%a4%e8%bf%b9%e8%9c%80%e4%b8%bb%e7%aa%a5%e5%90%b4.mp3"},{"name":"195咏怀古迹诸葛大名.mp3","href":"195%e5%92%8f%e6%80%80%e5%8f%a4%e8%bf%b9%e8%af%b8%e8%91%9b%e5%a4%a7%e5%90%8d.mp3"},{"name":"196江州重别薛六柳八二员外.mp3","href":"196%e6%b1%9f%e5%b7%9e%e9%87%8d%e5%88%ab%e8%96%9b%e5%85%ad%e6%9f%b3%e5%85%ab%e4%ba%8c%e5%91%98%e5%a4%96.mp3"},{"name":"197长沙过贾谊宅.mp3","href":"197%e9%95%bf%e6%b2%99%e8%bf%87%e8%b4%be%e8%b0%8a%e5%ae%85.mp3"},{"name":"198自夏口至鹦鹉洲夕望岳阳寄源中丞.mp3","href":"198%e8%87%aa%e5%a4%8f%e5%8f%a3%e8%87%b3%e9%b9%a6%e9%b9%89%e6%b4%b2%e5%a4%95%e6%9c%9b%e5%b2%b3%e9%98%b3%e5%af%84%e6%ba%90%e4%b8%ad%e4%b8%9e.mp3"},{"name":"199赠阙下裴舍人_钱起.mp3","href":"199%e8%b5%a0%e9%98%99%e4%b8%8b%e8%a3%b4%e8%88%8d%e4%ba%ba_%e9%92%b1%e8%b5%b7.mp3"},{"name":"200寄李儋元锡.mp3","href":"200%e5%af%84%e6%9d%8e%e5%84%8b%e5%85%83%e9%94%a1.mp3"},{"name":"201同题仙游观.mp3","href":"201%e5%90%8c%e9%a2%98%e4%bb%99%e6%b8%b8%e8%a7%82.mp3"},{"name":"202春思.mp3","href":"202%e6%98%a5%e6%80%9d.mp3"},{"name":"203晚次鄂州.mp3","href":"203%e6%99%9a%e6%ac%a1%e9%84%82%e5%b7%9e.mp3"},{"name":"204登柳州城楼寄漳汀封连四州刺史.mp3","href":"204%e7%99%bb%e6%9f%b3%e5%b7%9e%e5%9f%8e%e6%a5%bc%e5%af%84%e6%bc%b3%e6%b1%80%e5%b0%81%e8%bf%9e%e5%9b%9b%e5%b7%9e%e5%88%ba%e5%8f%b2.mp3"},{"name":"205西塞山怀古.mp3","href":"205%e8%a5%bf%e5%a1%9e%e5%b1%b1%e6%80%80%e5%8f%a4.mp3"},{"name":"206遣悲怀之一.mp3","href":"206%e9%81%a3%e6%82%b2%e6%80%80%e4%b9%8b%e4%b8%80.mp3"},{"name":"207遣悲怀之二.mp3","href":"207%e9%81%a3%e6%82%b2%e6%80%80%e4%b9%8b%e4%ba%8c.mp3"},{"name":"208遣悲怀之三.mp3","href":"208%e9%81%a3%e6%82%b2%e6%80%80%e4%b9%8b%e4%b8%89.mp3"},{"name":"209自河南经乱.mp3","href":"209%e8%87%aa%e6%b2%b3%e5%8d%97%e7%bb%8f%e4%b9%b1.mp3"},{"name":"210锦瑟.mp3","href":"210%e9%94%a6%e7%91%9f.mp3"},{"name":"211无题(昨夜星辰).mp3","href":"211%e6%97%a0%e9%a2%98(%e6%98%a8%e5%a4%9c%e6%98%9f%e8%be%b0).mp3"},{"name":"212隋宫.mp3","href":"212%e9%9a%8b%e5%ae%ab.mp3"},{"name":"213无题(来是空言).mp3","href":"213%e6%97%a0%e9%a2%98(%e6%9d%a5%e6%98%af%e7%a9%ba%e8%a8%80).mp3"},{"name":"214无题（飒飒）.mp3","href":"214%e6%97%a0%e9%a2%98%ef%bc%88%e9%a3%92%e9%a3%92%ef%bc%89.mp3"},{"name":"215筹笔驿.mp3","href":"215%e7%ad%b9%e7%ac%94%e9%a9%bf.mp3"},{"name":"216无题（相见时难）.mp3","href":"216%e6%97%a0%e9%a2%98%ef%bc%88%e7%9b%b8%e8%a7%81%e6%97%b6%e9%9a%be%ef%bc%89.mp3"},{"name":"217春雨.mp3","href":"217%e6%98%a5%e9%9b%a8.mp3"},{"name":"218无题（凤尾香罗）.mp3","href":"218%e6%97%a0%e9%a2%98%ef%bc%88%e5%87%a4%e5%b0%be%e9%a6%99%e7%bd%97%ef%bc%89.mp3"},{"name":"219无题（重帷深下）.mp3","href":"219%e6%97%a0%e9%a2%98%ef%bc%88%e9%87%8d%e5%b8%b7%e6%b7%b1%e4%b8%8b%ef%bc%89.mp3"},{"name":"220利州南渡.mp3","href":"220%e5%88%a9%e5%b7%9e%e5%8d%97%e6%b8%a1.mp3"},{"name":"221苏武庙.mp3","href":"221%e8%8b%8f%e6%ad%a6%e5%ba%99.mp3"},{"name":"222宫词.mp3","href":"222%e5%ae%ab%e8%af%8d.mp3"},{"name":"223贫女.mp3","href":"223%e8%b4%ab%e5%a5%b3.mp3"},{"name":"224独不见.mp3","href":"224%e7%8b%ac%e4%b8%8d%e8%a7%81.mp3"},{"name":"225鹿柴.mp3","href":"225%e9%b9%bf%e6%9f%b4.mp3"},{"name":"226竹里馆.mp3","href":"226%e7%ab%b9%e9%87%8c%e9%a6%86.mp3"},{"name":"227送别（王维）.mp3","href":"227%e9%80%81%e5%88%ab%ef%bc%88%e7%8e%8b%e7%bb%b4%ef%bc%89.mp3"},{"name":"228相思.mp3","href":"228%e7%9b%b8%e6%80%9d.mp3"},{"name":"229杂诗.mp3","href":"229%e6%9d%82%e8%af%97.mp3"},{"name":"230送崔九.mp3","href":"230%e9%80%81%e5%b4%94%e4%b9%9d.mp3"},{"name":"231终南望馀雪.mp3","href":"231%e7%bb%88%e5%8d%97%e6%9c%9b%e9%a6%80%e9%9b%aa.mp3"},{"name":"232宿建德江.mp3","href":"232%e5%ae%bf%e5%bb%ba%e5%be%b7%e6%b1%9f.mp3"},{"name":"233春晓.mp3","href":"233%e6%98%a5%e6%99%93.mp3"},{"name":"234夜思.mp3","href":"234%e5%a4%9c%e6%80%9d.mp3"},{"name":"235怨情.mp3","href":"235%e6%80%a8%e6%83%85.mp3"},{"name":"236八阵图.mp3","href":"236%e5%85%ab%e9%98%b5%e5%9b%be.mp3"},{"name":"237登鹳鹊楼.mp3","href":"237%e7%99%bb%e9%b9%b3%e9%b9%8a%e6%a5%bc.mp3"},{"name":"238送灵澈.mp3","href":"238%e9%80%81%e7%81%b5%e6%be%88.mp3"},{"name":"239弹琴.mp3","href":"239%e5%bc%b9%e7%90%b4.mp3"},{"name":"240送上人.mp3","href":"240%e9%80%81%e4%b8%8a%e4%ba%ba.mp3"},{"name":"241秋夜寄丘员外.mp3","href":"241%e7%a7%8b%e5%a4%9c%e5%af%84%e4%b8%98%e5%91%98%e5%a4%96.mp3"},{"name":"242听筝.mp3","href":"242%e5%90%ac%e7%ad%9d.mp3"},{"name":"243新嫁娘词.mp3","href":"243%e6%96%b0%e5%ab%81%e5%a8%98%e8%af%8d.mp3"},{"name":"244玉台体.mp3","href":"244%e7%8e%89%e5%8f%b0%e4%bd%93.mp3"},{"name":"245江雪.mp3","href":"245%e6%b1%9f%e9%9b%aa.mp3"},{"name":"246行宫.mp3","href":"246%e8%a1%8c%e5%ae%ab.mp3"},{"name":"247问刘十九.mp3","href":"247%e9%97%ae%e5%88%98%e5%8d%81%e4%b9%9d.mp3"},{"name":"248何满子.mp3","href":"248%e4%bd%95%e6%bb%a1%e5%ad%90.mp3"},{"name":"249登乐游原.mp3","href":"249%e7%99%bb%e4%b9%90%e6%b8%b8%e5%8e%9f.mp3"},{"name":"250寻隐者不遇.mp3","href":"250%e5%af%bb%e9%9a%90%e8%80%85%e4%b8%8d%e9%81%87.mp3"},{"name":"251渡汉江.mp3","href":"251%e6%b8%a1%e6%b1%89%e6%b1%9f.mp3"},{"name":"252春怨.mp3","href":"252%e6%98%a5%e6%80%a8.mp3"},{"name":"253哥舒歌.mp3","href":"253%e5%93%a5%e8%88%92%e6%ad%8c.mp3"},{"name":"254长干行之一.mp3","href":"254%e9%95%bf%e5%b9%b2%e8%a1%8c%e4%b9%8b%e4%b8%80.mp3"},{"name":"255长干行之二.mp3","href":"255%e9%95%bf%e5%b9%b2%e8%a1%8c%e4%b9%8b%e4%ba%8c.mp3"},{"name":"256玉阶怨（李白）.mp3","href":"256%e7%8e%89%e9%98%b6%e6%80%a8%ef%bc%88%e6%9d%8e%e7%99%bd%ef%bc%89.mp3"},{"name":"257塞下曲之一.mp3","href":"257%e5%a1%9e%e4%b8%8b%e6%9b%b2%e4%b9%8b%e4%b8%80.mp3"},{"name":"258塞下曲之二_卢纶.mp3","href":"258%e5%a1%9e%e4%b8%8b%e6%9b%b2%e4%b9%8b%e4%ba%8c_%e5%8d%a2%e7%ba%b6.mp3"},{"name":"259塞下曲之三.mp3","href":"259%e5%a1%9e%e4%b8%8b%e6%9b%b2%e4%b9%8b%e4%b8%89.mp3"},{"name":"260塞下曲之四.mp3","href":"260%e5%a1%9e%e4%b8%8b%e6%9b%b2%e4%b9%8b%e5%9b%9b.mp3"},{"name":"261江南曲.mp3","href":"261%e6%b1%9f%e5%8d%97%e6%9b%b2.mp3"},{"name":"262回乡偶书.mp3","href":"262%e5%9b%9e%e4%b9%a1%e5%81%b6%e4%b9%a6.mp3"},{"name":"263桃花溪.mp3","href":"263%e6%a1%83%e8%8a%b1%e6%ba%aa.mp3"},{"name":"264九月九日忆山东兄弟.mp3","href":"264%e4%b9%9d%e6%9c%88%e4%b9%9d%e6%97%a5%e5%bf%86%e5%b1%b1%e4%b8%9c%e5%85%84%e5%bc%9f.mp3"},{"name":"265芙蓉楼送辛渐.mp3","href":"265%e8%8a%99%e8%93%89%e6%a5%bc%e9%80%81%e8%be%9b%e6%b8%90.mp3"},{"name":"266闺怨.mp3","href":"266%e9%97%ba%e6%80%a8.mp3"},{"name":"267春宫怨.mp3","href":"267%e6%98%a5%e5%ae%ab%e6%80%a8.mp3"},{"name":"268凉州曲（王翰）.mp3","href":"268%e5%87%89%e5%b7%9e%e6%9b%b2%ef%bc%88%e7%8e%8b%e7%bf%b0%ef%bc%89.mp3"},{"name":"269送孟浩然之广陵.mp3","href":"269%e9%80%81%e5%ad%9f%e6%b5%a9%e7%84%b6%e4%b9%8b%e5%b9%bf%e9%99%b5.mp3"},{"name":"270下江陵.mp3","href":"270%e4%b8%8b%e6%b1%9f%e9%99%b5.mp3"},{"name":"271逢入京使.mp3","href":"271%e9%80%a2%e5%85%a5%e4%ba%ac%e4%bd%bf.mp3"},{"name":"272江南逢李龟年.mp3","href":"272%e6%b1%9f%e5%8d%97%e9%80%a2%e6%9d%8e%e9%be%9f%e5%b9%b4.mp3"},{"name":"273滁州西涧.mp3","href":"273%e6%bb%81%e5%b7%9e%e8%a5%bf%e6%b6%a7.mp3"},{"name":"274枫桥夜泊.mp3","href":"274%e6%9e%ab%e6%a1%a5%e5%a4%9c%e6%b3%8a.mp3"},{"name":"275寒食.mp3","href":"275%e5%af%92%e9%a3%9f.mp3"},{"name":"276月夜.mp3","href":"276%e6%9c%88%e5%a4%9c.mp3"},{"name":"277春怨.mp3","href":"277%e6%98%a5%e6%80%a8.mp3"},{"name":"278征人怨.mp3","href":"278%e5%be%81%e4%ba%ba%e6%80%a8.mp3"},{"name":"279宫词_顾况.mp3","href":"279%e5%ae%ab%e8%af%8d_%e9%a1%be%e5%86%b5.mp3"},{"name":"280夜上受降城闻笛.mp3","href":"280%e5%a4%9c%e4%b8%8a%e5%8f%97%e9%99%8d%e5%9f%8e%e9%97%bb%e7%ac%9b.mp3"},{"name":"281乌衣巷.mp3","href":"281%e4%b9%8c%e8%a1%a3%e5%b7%b7.mp3"},{"name":"282春词.mp3","href":"282%e6%98%a5%e8%af%8d.mp3"},{"name":"283宫词（白居易）.mp3","href":"283%e5%ae%ab%e8%af%8d%ef%bc%88%e7%99%bd%e5%b1%85%e6%98%93%ef%bc%89.mp3"},{"name":"284赠内人.mp3","href":"284%e8%b5%a0%e5%86%85%e4%ba%ba.mp3"},{"name":"285集灵台之一（张祜）.mp3","href":"285%e9%9b%86%e7%81%b5%e5%8f%b0%e4%b9%8b%e4%b8%80%ef%bc%88%e5%bc%a0%e7%a5%9c%ef%bc%89.mp3"},{"name":"286集灵台之二（张祜）.mp3","href":"286%e9%9b%86%e7%81%b5%e5%8f%b0%e4%b9%8b%e4%ba%8c%ef%bc%88%e5%bc%a0%e7%a5%9c%ef%bc%89.mp3"},{"name":"287题金陵渡.mp3","href":"287%e9%a2%98%e9%87%91%e9%99%b5%e6%b8%a1.mp3"},{"name":"288宫中词_朱庆余.mp3","href":"288%e5%ae%ab%e4%b8%ad%e8%af%8d_%e6%9c%b1%e5%ba%86%e4%bd%99.mp3"},{"name":"289近试上张水部.mp3","href":"289%e8%bf%91%e8%af%95%e4%b8%8a%e5%bc%a0%e6%b0%b4%e9%83%a8.mp3"},{"name":"290将赴吴兴登乐游原.mp3","href":"290%e5%b0%86%e8%b5%b4%e5%90%b4%e5%85%b4%e7%99%bb%e4%b9%90%e6%b8%b8%e5%8e%9f.mp3"},{"name":"291赤壁.mp3","href":"291%e8%b5%a4%e5%a3%81.mp3"},{"name":"292泊秦淮.mp3","href":"292%e6%b3%8a%e7%a7%a6%e6%b7%ae.mp3"},{"name":"293寄扬州韩绰判官.mp3","href":"293%e5%af%84%e6%89%ac%e5%b7%9e%e9%9f%a9%e7%bb%b0%e5%88%a4%e5%ae%98.mp3"},{"name":"294遣怀（杜牧）.mp3","href":"294%e9%81%a3%e6%80%80%ef%bc%88%e6%9d%9c%e7%89%a7%ef%bc%89.mp3"},{"name":"295秋夕.mp3","href":"295%e7%a7%8b%e5%a4%95.mp3"},{"name":"296赠别（杜牧）.mp3","href":"296%e8%b5%a0%e5%88%ab%ef%bc%88%e6%9d%9c%e7%89%a7%ef%bc%89.mp3"},{"name":"297赠别（杜牧）.mp3","href":"297%e8%b5%a0%e5%88%ab%ef%bc%88%e6%9d%9c%e7%89%a7%ef%bc%89.mp3"},{"name":"298金谷园.mp3","href":"298%e9%87%91%e8%b0%b7%e5%9b%ad.mp3"},{"name":"299夜雨寄北.mp3","href":"299%e5%a4%9c%e9%9b%a8%e5%af%84%e5%8c%97.mp3"},{"name":"300寄令狐郎中.mp3","href":"300%e5%af%84%e4%bb%a4%e7%8b%90%e9%83%8e%e4%b8%ad.mp3"},{"name":"301为有（李商隐）.mp3","href":"301%e4%b8%ba%e6%9c%89%ef%bc%88%e6%9d%8e%e5%95%86%e9%9a%90%ef%bc%89.mp3"},{"name":"302隋宫.mp3","href":"302%e9%9a%8b%e5%ae%ab.mp3"},{"name":"303瑶池.mp3","href":"303%e7%91%b6%e6%b1%a0.mp3"},{"name":"304嫦娥.mp3","href":"304%e5%ab%a6%e5%a8%a5.mp3"},{"name":"305贾生.mp3","href":"305%e8%b4%be%e7%94%9f.mp3"},{"name":"306瑶瑟怨.mp3","href":"306%e7%91%b6%e7%91%9f%e6%80%a8.mp3"},{"name":"307马嵬坡.mp3","href":"307%e9%a9%ac%e5%b5%ac%e5%9d%a1.mp3"},{"name":"308己凉.mp3","href":"308%e5%b7%b1%e5%87%89.mp3"},{"name":"309金陵图庄韦.mp3","href":"309%e9%87%91%e9%99%b5%e5%9b%be%e5%ba%84%e9%9f%a6.mp3"},{"name":"310陇西行_陈陶.mp3","href":"310%e9%99%87%e8%a5%bf%e8%a1%8c_%e9%99%88%e9%99%b6.mp3"},{"name":"311寄人（张泌）.mp3","href":"311%e5%af%84%e4%ba%ba%ef%bc%88%e5%bc%a0%e6%b3%8c%ef%bc%89.mp3"},{"name":"312杂诗_无名士.mp3","href":"312%e6%9d%82%e8%af%97_%e6%97%a0%e5%90%8d%e5%a3%ab.mp3"},{"name":"313渭城曲.mp3","href":"313%e6%b8%ad%e5%9f%8e%e6%9b%b2.mp3"},{"name":"314秋夜曲.mp3","href":"314%e7%a7%8b%e5%a4%9c%e6%9b%b2.mp3"},{"name":"315长信怨.mp3","href":"315%e9%95%bf%e4%bf%a1%e6%80%a8.mp3"},{"name":"316出塞（王昌龄）.mp3","href":"316%e5%87%ba%e5%a1%9e%ef%bc%88%e7%8e%8b%e6%98%8c%e9%be%84%ef%bc%89.mp3"},{"name":"317清平调之一.mp3","href":"317%e6%b8%85%e5%b9%b3%e8%b0%83%e4%b9%8b%e4%b8%80.mp3"},{"name":"318清平调之二.mp3","href":"318%e6%b8%85%e5%b9%b3%e8%b0%83%e4%b9%8b%e4%ba%8c.mp3"},{"name":"319清平调之三.mp3","href":"319%e6%b8%85%e5%b9%b3%e8%b0%83%e4%b9%8b%e4%b8%89.mp3"},{"name":"320出塞（王之涣）.mp3","href":"320%e5%87%ba%e5%a1%9e%ef%bc%88%e7%8e%8b%e4%b9%8b%e6%b6%a3%ef%bc%89.mp3"},{"name":"321金缕衣.mp3","href":"321%e9%87%91%e7%bc%95%e8%a1%a3.mp3"},{"name":"33送杨氏女.mp3","href":"33%e9%80%81%e6%9d%a8%e6%b0%8f%e5%a5%b3.mp3"}];
		$("input[name=audio]").atwho({
			at:"h",
			data:data,
			limit:10,
			tpl: "<li data-value='http://theotherdoor-wp.stor.sinaapp.com/tangpoem-audio/${href}'>${name}</li>"
		});
	})();
</script>