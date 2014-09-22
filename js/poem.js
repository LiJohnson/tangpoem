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

	
	MyPlayer.prototype.playFrom = function(start , end){
		if(!start)return;
		
		if( end && end > start ){
			this.on("process" , function(data){
				data.cur > end && this.pause();
			});
		}

		this.play();
		this.audio.currentTime = start;
	};
})();