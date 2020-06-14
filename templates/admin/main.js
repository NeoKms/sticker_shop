document.addEventListener('DOMContentLoaded', function () {
	var get = $.get( "index.php?path=admin/goodsListAjax&asAjax=1", function(data) {
		console.log(JSON.parse(data));
		data=JSON.parse(data);
		var myChart = Highcharts.chart('container', {
			chart: {
				type: 'bar'
			},
			title: {
				text: 'Количество проданных товаров'
			},
			xAxis: {
				categories: data.names
			},
			yAxis: {
				title: {
					text: ''
				}
			},
			series: [{
				name: 'Количество',
				data: data.cols
			}]
		});
	});
});

