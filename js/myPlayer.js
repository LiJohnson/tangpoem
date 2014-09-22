/*!
 * MyPlayer v0.1 (https://github.com/LiJohnson/MyPlayer)
 */
(function(){
	'use strict';

	var extend = function(v1,v2){
		v1 = v1 || {};
		v2 = v2 || {};
		for( var i in v2 ){
			v1[i] = v2[i];
		}
		return v1;
	};

	var Analyser = (function(){
		var requestAnimationFrame = window.requestAnimationFrame || window.webkitRequestAnimationFrame;
		var cancelAnimationFrame = window.cancelAnimationFrame || window.webkitCancelAnimationFrame;
		var AudioContext = window.AudioContext || window.webkitAudioContext;
		if( !requestAnimationFrame || !cancelAnimationFrame || !AudioContext ){
			console.log("not suport Analyser ( requestAnimationFrame , cancelAnimationFrame ,AudioContext )");
			return function(){this.turnOn = this.turnOff = this.onFrequency = function(){}; };
		}
		return function(audio){
			var $this = this;
			var audioContext = this.audioContext = new  AudioContext();
			var analyser = audioContext.createAnalyser();
			var source = this.source = audioContext.createMediaElementSource(audio);
			
			source.connect(analyser);
			analyser.connect(audioContext.destination);
			
			var id = 0;
			var frequency = function(){
				id = requestAnimationFrame(frequency);
				var freqByteData = new Uint8Array(analyser.frequencyBinCount*1);

				analyser.getByteFrequencyData(freqByteData);
				//analyser.getByteTimeDomainData(freqByteData);
				//analyser.getFloatFrequencyData(freqByteData);
				$this.onFrequency(freqByteData);
			};
			
			this.turnOn = function(){
				cancelAnimationFrame(id);
				frequency();
			};
			this.turnOff = function(){
				cancelAnimationFrame(id);
			};

			this.onFrequency = function(){};
		};
	})();

	var MyPlayer = function(){
		if( !window.Audio )throw("need html5 suport");

		var audio = this.audio = new Audio();
		var analyser = this.analyser = new Analyser(audio);
		audio.style.display = 'none';
		//audio.controls = 1;
		document.body.appendChild(audio);

		var $this = this;
		var events = {};
		var list = [];

		this.PLAY_MODE = {
			repeat:1,
			readom:2,
			order:3,
			one:4//只放一次
		};

		analyser.onFrequency = function(data){
			trigger("analyser",data);
		};

		var trigger = function(){
			var type = arguments[0] , arg = [];
			if( !type || ! events[type] )return;
			for( var i = 1 ; i < arguments.length ; i++ ){
				arg.push(arguments[i]);
			}

			for( var i = 0 ; i < events[type].length ; i++ ){
				var res = events[type][i].apply($this,arg);
				if( res === false )return;
			}
		};

		var formatSong = (function(){
			var defaultValue = {
				title:"unknow",
				picture:false,
				artist:"unknow"

			};
			return function(song){
				if( typeof song == "string" ){
					song = extend( extend( {} , defaultValue ) , {url:song} );
				}else if( typeof song == "object" ){
					song = song.formated ? song :  extend( extend( {formated:true} , defaultValue ) ,song );
				}else{
					throw("no song");
				}

				//修正相对路径的地址
				if( !/^(https?:|blob:|data:)/i.test(song.url) ){
					var a = new Audio();
					a.src = song.url;
					song.url = a.src;
				}
				return song;
			}
		})();

		var currentSong = (function(){
			var cur = false;
			return function(song){
				if( song == void 0 ){
					return cur;
				}else{
					cur = song;
				}
			}
		})();

		var getSongIndex = function(song){
			if(!song)return -1;
			for( var i = 0 , s ; s = list[i] ; i++ ){
				if( song.url == s.url )return i;
			}
			return 0;
		}

		"ended error playing".split(" ").forEach(function(type){
			audio.addEventListener(type,function(e){
				trigger(type, currentSong(),e);
			});
		});

		this.isPlaying = function(song){
			song = song == void 0 ? currentSong() : song;
			song = formatSong(song);
			return song.url == audio.src && !audio.paused
		};
		this.play = function(song){
			song = song == void 0 ? currentSong() : song;
			song = formatSong(song);
			if( this.isPlaying(song) )return;

			audio.src != song.url && (audio.src = song.url);
			audio.play();
			currentSong( song );
			trigger("play",song);
		};

		this.pause = function(){
			audio.pause();
			trigger("pause",currentSong( ));
		};

		this.next = function(offset){
			offset = offset || 1;
			var song = false;
			switch( this.playMode() ){
				case this.PLAY_MODE.repeat:
					song = currentSong();
				break;
				case this.PLAY_MODE.random:
					song = list[ Math.floor( Math.random(0,list.length) ) ] || currentSong();
					
				break;
				case this.PLAY_MODE.one:
					this.pause();
				break;
				case this.PLAY_MODE.order:
				default:
					song = currentSong();
					if( list.length ){
						var index = getSongIndex( song );
						index = index == -1 ? 0 : index;
						song = list[( index + offset + list.length )% list.length ] || currentSong();	
					}
				break;
			}
			if( song ){
				trigger( index == -1 ? 'prve' : 'next' , song);
				return this.play(song);
			}
		};

		this.prev = function(){
			return this.next(-1);
		};

		this.add = function(song){
			song = formatSong(song);
			list.push(song);
			trigger("add" , song , list);
		};

		this.remove = function(song){
			song = formatSong(song);
			var index = getSongIndex(song);
			if( index != -1 ){
				for( var i = index , len = list.length -1; i < len ; i++ ){
					list[i] = list[i+1];
				}
				list.pop();
				trigger("remove",song,list);
			}
			return index;

		};

		this.clear = function(){
			list = [];
			trigger("clear");
		};

		this.playMode = (function($this){
			var playMode = $this.PLAY_MODE.order;
			return function(mode){
				if( mode == void 0 ){
					return playMode
				}else{
					playMode = mode;
					trigger("playMode",mode);
				}
				return this;
			};
		})(this);

		this.volume = function(val){
			if( val == void 0 ){
				return audio.volume*100;
			}else{
				val = val*1 || 0;
				val = val < 0 ? 0 : val;
				val = val > 100 ? 100 : val;
				audio.volume = val/100;
				trigger("volume",this.volume());
				return this;
			}
		};

		this.progress = function(progress){
			if( progress == void 0 ){
				return audio.currentTime / audio.duration;
			}if( progress > 1 ){
				progress *= 1;
			}else{
				progress = Math.floor(progress * audio.duration) || 0;
			}
			progress = progress < 0 ? 0 : progress;
			progress = progress > audio.duration ? audio.duration : progress;
			audio.currentTime = progress;
			return this;
		};

		this.mute = (function(){
			var val = 0;
			return function(){
				if( this.volume() == 0 ){
					this.volume(val);
				}else{
					val = this.volume();
					this.volume(0);
				}
				trigger("mute" , this.volume() == 0);
				return this;
			};
		})();

		this.on = function(type,cb){
			String(type).split(" ").forEach(function(type){
				events[type] = events[type] || [];
				events[type].push(cb);
			});
			return this;
		};

		this.on("ended",function(){
			$this.next();
		});

		//analyser
		(function($this){
			var timeId = 0;
			$this.on("ended pause error",function(){
				timeId && clearTimeout(timeId);
				timeId = setTimeout(function(){
					analyser.turnOff();
				}, 3000);
				
			}).on("playing",function(){
				timeId && clearTimeout(timeId);
				timeId = 0;
				analyser.turnOn();
			});
		})(this);

		//progress
		(function($this){
			var timeId = 0;
			var triggerProgress = function(){
				var data = {
					cur:audio.currentTime,
					length:audio.duration
				};
				data.progress = data.cur/data.length;
				trigger("progress",data);
			};
			$this.on("ended pause error",function(){
				timeId && clearInterval(timeId);
			}).on("playing",function(){
				timeId && clearTimeout(timeId);

				timeId = setInterval(function(){
					triggerProgress();
				},100);

				triggerProgress();
			});
		})(this);
	};

	window.MyPlayer = MyPlayer;
})();

//player UI
(function(){
	'use strict';
	/**
	 * localStorage
	 * @param  storage could be window.sessionStorage/window.localStorage
	 * @param  String key     
	 */
	var Storage = function( key , storage  ){
		storage = storage || window.localStorage || {};
		var data = eval("("+storage[key]+")") || {};

		/**
		 * get a value
		 */
		this.get = function(key , defaultValue){
			return data[key] == void 0 ? defaultValue : data[key] ;
		};

		/**
		 * set a value
		 */
		this.set = function( k , v){
			if( arguments.length == 1 ){
				data = k;
			}else{
				data[k] = v;	
			}
			storage[key] = JSON.stringify(data);
		};

		/**
		 * delete a key
		 */
		this.delete = function(k){
			delete data[k];
			storage[key] = JSON.stringify(data);
		};

		/**
		 * destory this storage
		 */
		this.destory = function(){
			if( storage.removeItem ){
				storage.removeItem(key);
			}else{
				storage[key] = null;
			}
		};
	};

	var getPlayerHhtml = function(){
		var html = '<div class="my-player" style="margin:5px ;" >	<div class="disk stop" ><img></div>	<div class="btn play"><span></span></div>	<div class="btn next"><span></span></div>	<div class="btn prev"><span></span></div>	<div class="btn vol-up"><span></span></div>	<div class="btn vol-down"><span></span></div>	<div class="vol-num"></div>	<div class="btn time" ></div>	<div class="tick" >		<div class="progress" ></div>	</div>	<div class="control">		<div class="touch-panel">			<div class="touch-1" ></div>			<div class="touch-2" >				<div class="touch-3" >					<div class="light" ></div>				</div>			</div>		</div>		<div class="light" ></div>	</div>	<div class="btn file" >		<input type="file" multiple accept="audio/*,video/*"/>	</div>	<canvas></canvas></div>';
		return $(html);
	};
	var MyPlayerUI = function(){
		var $this = this;

		var player = this.player =  new MyPlayer(); 
		var $player = this.$player = getPlayerHhtml();

		var canvas = this.$player.find("canvas")[0];
		var context = canvas.getContext("2d");
		var stor = new Storage("myPlayer");

		canvas.width = this.$player.width();
		canvas.height = this.$player.height();

		player.on("playing",function(song){
			$player.find(".disk").removeClass("stop");
			$player.find(".btn.play").addClass("pause");
			$player.addClass("on").removeClass("off");
			if(song.picture){
				$player.find(".disk").addClass("picture").css("background-image","url('"+song.picture+"')").find("img").prop("src",song.picture);
			}else{
				$player.find(".disk").removeClass("picture").css("background-image","none");
			}
		}).on("pause",function(song){
			$player.find(".disk").addClass("stop");
			$player.find(".btn.play").removeClass("pause");
			$player.addClass("off").removeClass("on");
		}).on("analyser",(function(){
			var max = [];
			var maxHeight = canvas.height * 0.3;
			var gradient = context.createLinearGradient(0, canvas.height -maxHeight , 0, canvas.height);
			//gradient.addColorStop(0, 'rgb(80, 80, 80)');
			gradient.addColorStop(0, 'rgb(175, 175, 175)');
			gradient.addColorStop(1, 'rgb(80, 80, 80)');
			context.fillStyle = gradient;
			
			return function(data){
				context.clearRect(0,0,canvas.width , canvas.height);
				for( var i = 0 ; i < canvas.width ; i++ ){
					var h = data[ Math.floor( data.length*0.7 * i / canvas.width ) ];
					max[i] = max[i] || 0;
					max[i] = max[i] < h ? h : max[i] ;

					h = maxHeight * h / 255;
					
					context.fillRect(i,canvas.height , 1 , -h);

					h = maxHeight * max[i] / 255;
					//context.fillStyle = "red";
					context.fillRect( i ,canvas.height - h -1  , 1 , 1);
					max[i]--;
				}
			};
		})()).on("progress",(function(){
			var format = function(num){
				num = Math.floor(num)||0;
				if( num == 0 )return "00";
				if( num < 10 )return "0"+num;
				return num;
			};
			return function(data){
				var curTime = Math.floor(data.cur/60);
				$player.find(".time").text( format( data.cur/60 ) + ":" + format( data.cur%60 ) );
				
			};
		})()).on("volume",(function(){
			var timeId = 0;
			stor.get("volume") && player.volume(stor.get("volume"));
			return function(volume){
				volume = Math.floor(volume);
				stor.set("volume",volume);
				$player.find(".vol-num").stop().fadeIn().text(volume);

				timeId && clearTimeout(timeId);
				timeId = setTimeout(function(){
					$player.find(".vol-num").stop().fadeOut();
				},1000);
			}
		})()).on("mute",function(mute){
			$player.toggleClass('mute',mute);
		});

		$player.on("click",".btn.play",function(){
			player.isPlaying() ? player.pause() : player.play();
		}).on("click",".btn.next",function(){
			player.next();
		}).on("click",".btn.prev",function(){
			player.prev();
		}).on("change","input[type=file]",function(){
			if(!this.files)return;
			for( var i = 0 , f ; f = this.files[i]  ; i++){
				var song = { url:URL.createObjectURL(f), title:f.name};
				try{
					new ID3(f).getData(function(list,map){
						song.picture = (map["APIC"]||{}).text;
						player.add(song);
					});
				}catch(e){
					player.add(song);
				}
			}
			this.value="";
		});

		//volume
		(function(){
			var timeId = 0;
			var updateVolume = function( offset ){
				offset = offset || 1;
				clearInterval(timeId);
				var speed = 1;
				timeId = setInterval(function(){
					player.volume(player.volume()+offset * speed );
					speed += speed < 3 ? 0.1 : 0;
				},200);
				//防止静音时的操作
				player.volume() && player.volume(player.volume()+offset);
			};
			$player.on("dblclick",".btn.vol-up , .btn.vol-down",function(){
				clearInterval(timeId);
				player.mute();
			}).on("mousedown",".btn.vol-up",function(){
				updateVolume(1);
			}).on("mousedown",".btn.vol-down",function(){
				updateVolume(-1);
			}).add(window).on("mouseup",function(){
				clearInterval(timeId);
			});
		})();

		//progress( todo : page zoom ?)
		(function(){
			var lock = false;
			var stopTick = false;
			var width = $player.width() * ( $player.css("zoom") || 1 );
			var height = $player.height() * ( $player.css("zoom") || 1 ) ;
			//var 
			var getDeg = function(x,y){
				x = x - width * 0.5;
				y = y - height * 0.5;
				var deg = 180*Math.atan(y/x)/Math.PI;
				
				deg +=  ( x > 0 ? 90 : 270 );

				return deg;
			};
			var timeId = 0 ;
			player.on("progress",function(data){
				timeId && clearTimeout(timeId);
				timeId = setTimeout(function(){
					timeId = 0;				
					!stopTick && $player.find(".tick").css("transform","rotate("+(data.progress*360)+"deg)");
					$player.find(".control").css("transform","rotate("+(-10 + data.progress*25)+"deg)");
				},11);
			});

			$player.add($(window)).on("mouseup",function(){
				lock = false;
				$player.find(".time").removeClass("show");
			});

			$player.on("mousedown",".progress",function(){
				lock = true;
				$player.find(".time").addClass("show");
			}).on("mousemove",function(e){
				if(!lock)return;
				var offset = $player.offset();
				var x = e.pageX - offset.left;
				var y = e.pageY - offset.top;
				var deg = getDeg(x,y,offset,e);
				player.progress(deg/360);
			});
			$player.find(".progress").hover(function(){
				stopTick = true;
			},function(){
				stopTick = false;
			});
		})();
	};
	window.MyPlayerUI = MyPlayerUI;
})();
