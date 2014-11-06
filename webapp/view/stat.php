<script src="http://code.highcharts.com/highcharts.js"></script>
<div id="stat">
</div>
<hr>
<div class="row" >
	<h3>不重复共出现<b class="text-info"><?=count($text)?></b>个字:</h3>
	<p class="text-danger">
		<?=join($text,'')?>
	</p>
</div>
<textarea name="text" class="hide"><?=$text?></textarea>
<script>
	var DATA = <?=json_encode($data)?>;
	var TEXT = $("textarea[name=text]").val();
	$(function(){

		var statData = (function(){
			var data = {};
			$.each(DATA,function(i,c){
				if( data[c[1]] ){
					data[c[1]].name.push(c[0]);
				}else{
					data[c[1]]={
						name:[c[0]],count:c[1]
					}				
				}
			});
			var statData = [];
			$.each(data,function(i,v){
				statData.push({name:v.name.join(",").substr(0,10),y:v.count,title:v.name.join(",")});
			});

			statData = statData.sort(function(a,b){return b.y-a.y});
			statData = statData.filter(function(a){return a.y>=10;});
			return statData;
		})();

		$('#stat').highcharts({
		chart: {
			type: 'bar',
			height:statData.length*30,
			options3d: {
				//enabled: true,
				//alpha: 15,
				//beta: 15,
				//depth: 500,
				//viewDistance: 25
			}
		},
		title: {
			text: '各汉字出现频率（仅显示大于等于10次）'
		},
		xAxis: { 
			type: 'category',
			 labels: {
			   // rotation: -45
			},
			title: {
				text: null
			}
			
		},
		yAxis: {
			min: 0,
			title: {
				text: '出现频率/次'
			}
		},
		legend: {
		   enabled: false
		},
		plotOptions: {
			series: {
				stacking: 'normal'
			}
		},
		tooltip: {
			pointFormat: '<b>{point.y}次,{point.title}</b>'
		},
		series: [{
			data: statData
		}]
	});
	});
</script>