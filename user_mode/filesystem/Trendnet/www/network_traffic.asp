<html>
<head>
	<title>Flot Examples</title>
	<script language="javascript" type="text/javascript" src="./js/jquery-1.3.2.min.js"></script>
	<script language="javascript" type="text/javascript" src="./js/jquery.flot.js"></script>
	<style>
		.tickLabelx {
			-webkit-transform: rotate(270deg);
			-webkit-transform-origin: middle bottom;
			-moz-transform: rotate(270deg);
			-moz-transform-origin: right bottom;
			-ms-transform: rotate(270deg);
			-ms-transform-origin: right bottom;
			-o-transform: rotate(270deg);
			-o-transform-origin: right bottom;
			transform: rotate(270deg);
			transform-origin: right bottom;
			white-space:nowrap; 
			/*border: 1px solid black*/
		}
	</style>
	<script>
		/**
		 * class define
		 */
		function NetworkTrafficGraph() {
			this.target;
			this.plotData;
		}
		
		function caculate_y_axis(num, base) {
			var n = (num/base)%10;
			var fractionDigits = 0;
			if (n > 0)
				fractionDigits = 1;
			return (parseInt((num/base) * Math.pow(10, fractionDigits) + 0.5)/Math.pow(10, fractionDigits).toString());
		}
		
		NetworkTrafficGraph.prototype = {
			check: function(type, iface, target) {
				if (type == null) {
					console.log('NetworkTrafficGraph:type is null');
					return false;
				}
				if (iface == null) {
					console.log('NetworkTrafficGraph:iface is null');
					return false;
				}
				if (target == null) {
					console.log('NetworkTrafficGraph:target is null');
					return false;
				}
				return true;
			},
			
			
			suffixFormatter: function(val, axis) {
				if (val >= 1000000000)
					return caculate_y_axis(val, 1000000000) + "GB";
				else if (val >= 1000000)
					return caculate_y_axis(val, 1000000) + "MB";
				else if (val >= 1000)
					return caculate_y_axis(val, 1000) + "KB";
				else
					return val.toFixed(0) + "B";
			},

			getData: function(type, iface, target, itvl) {
				var obj = this;
				var ajax_param = {
					type: 	"POST",
					async:	true,
					url: 	"network_traffic.ccp",
					data: 	'type=' + type + '&iface=' + iface,
					timeout: itvl, 
					success: function(data) {
						var yAxis = { minY: 0, maxY: 100 };
						var xAxis = { interval: 1 };
						var minX = 0;
						var maxX = 0;
						var options = {
							series: { shadowSize: 0 }, // drawing is faster without shadows
						};
						this.plotData = [];
						
						if ($(data).find('result').text() != 'OK')
						{
							console.log('no OK message from web server, response='+$(data).text());
							this.plotData.push(0, 0);
							return;
						}
						
						if ($(data).find('interval').text() != "")
						{
							xAxis['interval'] = $(data).find('interval').text();
						}
						
						if ($(data).find('currTime').text() != "")
						{
							xAxis['currTime'] = $(data).find('currTime').text();
						}
						
						var data = {
							'point_rx': 	$(data).find('rec_rx').text().split(','),
							'point_tx': 	$(data).find('rec_tx').text().split(',')
						};
						
						if (data == null || data['point_rx'] == null || data['point_tx'] == null)
							return;
						
						// zip the generated y values with the x values
						var now = new Date().getTime();
						minX = new Date(now-itvl-(data['point_rx'].length*itvl)).getTime();
						maxX = new Date(now-itvl).getTime();
						var tmpX = minX;
						
						// for rx
						var data_rx = {'label': 'RX', 'data': []};
						var data_tx = {'label': 'TX', 'data': []};
						for (var i = 0; i < data['point_rx'].length; ++i)
						{
							var int_rx = parseInt(data['point_rx'][i]);
							var int_tx = parseInt(data['point_tx'][i]);
							if (yAxis != null) {
								yAxis['maxY'] = (int_rx > yAxis['maxY'] ? int_rx : yAxis['maxY']);
								yAxis['minY'] = (int_rx < yAxis['minY'] ? int_rx : yAxis['minY']);
								yAxis['maxY'] = (int_tx > yAxis['maxY'] ? int_tx : yAxis['maxY']);
								yAxis['minY'] = (int_tx < yAxis['minY'] ? int_tx : yAxis['minY']);
							}
							tmpX = new Date(tmpX+itvl).getTime();
							data_rx['data'].push([tmpX, int_rx]);
							data_tx['data'].push([tmpX, int_tx]);
						}
						this.plotData.push(data_rx);
						this.plotData.push(data_tx);
						
						if (this.plotData != null && this.plotData.length > 0) {	
							options['xaxis']= {
								mode: "time",
								minTickSize: [1, "minute"],
								max: maxX,
								min: minX
							};
						
							options['yaxis'] = {
								min: yAxis['minY'], 
								max: yAxis['maxY'],
								tickFormatter: obj.suffixFormatter
							};
						
							options['grid'] = { 
								labelMarginX: 40
							};
						
							plot = $.plot($(target), this.plotData, options);
							setTimeout(function() {
								obj.grab_draw(type, iface, target, itvl);}, xAxis['interval']*1000);
						}
						/*
						// for tx
						minX = new Date(now-itvl-(data['point_tx'].length*itvl)).getTime();
						maxX = new Date(now-itvl).getTime();
						tmpX = minX;
						for (var i = 0; i < data['point_tx'].length; ++i)
						{
							if (yAxis != null) {
								yAxis['maxY'] = data['point_tx'][i] > yAxis['maxY'] ? data['point_tx'][i] : yAxis['maxY'];
								yAxis['minY'] = data['point_tx'][i] < yAxis['minY'] ? data['point_tx'][i] : yAxis['minY'];
							}
							this.plotData.push([tmpX, data['point_tx'][i]]);
							tmpX = new Date(tmpX+itvl).getTime();
						}
						
						if (this.plotData != null && this.plotData.length > 0) {	
							options['xaxis']= {
								mode: "time",
								minTickSize: [1, "minute"],
								max: maxX,
								min: minX
							};
						
							options['yaxis'] = {
								min: yAxis['minY'], 
								max: yAxis['maxY'],
								tickFormatter: obj.suffixFormatter
							};
						
							options['grid'] = { 
								labelMarginX: 40
							};
						
							plot = $.plot($(target), [ this.plotData ], options);
							//setTimeout(function() {
							//	obj.grab_draw(type, iface, target, itvl);}, xAxis['interval']*1000);
						}
						*/
					}
				};
				$.ajax(ajax_param);
			},
			
			grab_draw: function(type, iface, target, updateItvl) {
				if (this.check(type, iface, target) == false) {
					return;
				}
				this.getData(type, iface, target, updateItvl);
			}
		}

	</script>
	<script>
		$(document).ready(function() {
			var g1 = new NetworkTrafficGraph().grab_draw('NT_TYPE_REALTIME', 'NT_IFACE_WAN', '#placeholder1', 3000);
			var g2 = new NetworkTrafficGraph().grab_draw('NT_TYPE_REALTIME', 'NT_IFACE_LAN', '#placeholder2', 3000);
			var g3 = new NetworkTrafficGraph().grab_draw('NT_TYPE_2DAYS', 'NT_IFACE_WAN', '#placeholder3', 3000);
			var g4 = new NetworkTrafficGraph().grab_draw('NT_TYPE_2DAYS', 'NT_IFACE_LAN', '#placeholder4', 3000);
			var g5 = new NetworkTrafficGraph().grab_draw('NT_TYPE_30DAYS', 'NT_IFACE_WAN', '#placeholder5', 3000);
			var g6 = new NetworkTrafficGraph().grab_draw('NT_TYPE_30DAYS', 'NT_IFACE_LAN', '#placeholder6', 3000);
			//var g3 = new NetworkTrafficGraph().grab_draw('NT_TYPE_REALTIME', 'NT_IFACE_24G', '#placeholder3', 3000);
			//var g4 = new NetworkTrafficGraph().grab_draw('NT_TYPE_REALTIME', 'NT_IFACE_5G',  '#placeholder4', 3000);
			//var g5 = new NetworkTrafficGraph().grab_draw('NT_TYPE_REALTIME', 'NT_IFACE_CPURAM', '#placeholder5', 3000);
		});
	</script>
</head>
<body>
	<div id="placeholder1" style="width:600px;height:300px;"></div><br><br>
	<div id="placeholder2" style="width:600px;height:300px;"></div><br><br>
	<div id="placeholder3" style="width:600px;height:300px;"></div><br><br>
	<div id="placeholder4" style="width:600px;height:300px;"></div><br><br>
	<div id="placeholder5" style="width:600px;height:300px;"></div><br><br>
	<div id="placeholder6" style="width:600px;height:300px;"></div><br><br>
	<input type=text id=watch_panel readonly>
</body>
</html>
