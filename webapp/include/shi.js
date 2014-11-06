/**
 * url: http://www.diyifanwen.com/sicijianshang/tangshisanbaishou/
 * 抓取唐诗三百首
 * 2014-09-18 by lcs
 */

var data = [];
var p = {};
$.each($(".IndexDl").children() , function(){
	if( this.tagName == 'DT' ){
		p = {cate:this.innerText , p : []};
		data.push(p);
	}else{
		var $this = $(this).clone() , $a = $this.find("a").remove();
		p.p.push({ title: $a.text() , url: $a.prop("href") , author:$this.text().replace("：","")  });
	}
});


var getData = function(html){
	var $ps = $(html.match(/<body[\s\S]+<\/body>/)[0]).find("#ArtContent p");
	var poem = {content:[] , rhymed:[] , note:[] , comment:[]};
	var key = 'content';
	$ps.each( function( ){
		var text = $(this).text().trim().replace(new RegExp(String.fromCharCode(59249) , "g"),"");
		var type = text.match(/【.{2}】/);
		
		if( !text )return;

		if( type ){
			type = type[0].replace(/[【】]/g,'').trim();
			if( type == "韵译" ){
				key = "rhymed";
			}else if( type == "注解" ){
				key = "note";
			}else if( type == "评析" ){
				key = "comment"; 
			}
		}
		poem[key].push(text);
	} );
	return poem;
};

var notDone = 0;
$.each(data, function(index, list) {
	$.each(list.p, function(index, p) {
		notDone++;
		$.get(p.url , function(html){
			notDone--;
			$.extend(p , getData(html));
			p.content.shift();
			
			//韵译
			//注解
			//评析
			 
			if( !notDone ){
				$("<textarea>").css({width:"100%",height:"500px"}).val(JSON.stringify(data)).appendTo("body");
			}
		},'html');
	});	
});