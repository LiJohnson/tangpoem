(function(){
	var TanngPoem = function (poemInfo){
		poemInfo = poemInfo.audioIndex || [];
		var player = new MyPlayer();
		var stop = 0;

		player.on("process",function(data){
			if( stop > 0 && data.cur > stop){
				stop = 0;
				player.pause();
			}
		});
		this.read = function( start ){

			var index = poemInfo.audioIndex.indexOf(start);
			var stop = index != -1 && (poemInfo.audioIndex[index+1] || 0);
		}
	};

	
	MyPlayer.prototype.playFrom = (function(){
		var pauseIndex = 0;
		var hasInit = false;
		var init = function(p){
			if(hasInit)return;
			hasInit = true;
			p.on("progress",function(data){
				pauseIndex && data.cur > pauseIndex && p.pause();
			});
		};
		return function(start , end){
			if(start == void 0)return;
			init(this);
			if( end && end > start ){
				pauseIndex = end;
			}else{
				pauseIndex = 0;
			}

			this.play();
			this.audio.currentTime = start;
		};
	})();
	
	MyPlayer.prototype.set = function(song){
		this.play(song);
		this.pause();
	}
})();

$(function(){
	$(document).on("click","[click-feedback]",function(){
		var $form =  $("form.feedback").clone().removeClass('hide');
		var $box = $.box({
			title:"意见反馈",
			html:$form,
			ok:function(){
				var $this = $(this);
				if( !$form.check(function($input,result){
					$input.parents(".form-group").toggleClass('has-error',!result);
					return false;
				}))return false;

				$.back("<i class='ion-loading-b'></i>").css("zIndex",9999);
				$form.postData(siteUrl+"/?action=feedback",{url:location.href},function(data){
					$this.remove();
					$.back("close");
					$box.find(".modal-body").html("<h3 class='text-center' >反馈成功,THX!</h3>");
				});
				return false;
			},
			cancel:function(){}
		});
	});

	//search form
	(function($form){
		$form.find('.glyphicon-search').click(function() {
			$form.submit();
		});
		$form.find('input[name=key]').focus(function() {
			$form.addClass('focus');
		}).blur(function() {
			$form.toggleClass('focus' , !!$(this).val());
		});
	})($("form.poem-search"));
});