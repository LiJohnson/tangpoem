<script src="http://code.highcharts.com/highcharts.js"></script>
<div id="stat">
</div>
<script>
	var DATA = <?=json_encode($data)?>;
	$(function(){
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
			statData.push([v.name.join(",").substr(0,10),v.count]);
		});

		statData.sort(function(a,b){return b[1]-a[1]});

		$('#stat').highcharts({
        chart: {
            type: 'bar'
        },
        title: {
            text: '各汉字出现频率'
        },
        xAxis: { 
        	type: 'category'
            
        },
        yAxis: {
            min: 0,
            title: {
                text: '出现频率/次'
            }
        },
        legend: {
            reversed: true
        },
        plotOptions: {
            series: {
                stacking: 'normal'
            }
        },
        tooltip: {
            pointFormat: '<b>{point.y}次</b>'
        },
        series: [{
            data: statData
        }]
    });
	});
</script>