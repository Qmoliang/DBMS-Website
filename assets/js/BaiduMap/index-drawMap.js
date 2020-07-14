var map;
var fusionLayerInfoWindow;
function drawMap_Baidu() {

	map = new BMap.Map("map_canvas", {}); // 创建Map实例
	map.centerAndZoom(new BMap.Point(-76.609, 39.395), 9); // 初始化地图,设置中心点坐标和地图级别
	map.enableScrollWheelZoom(); // 启用滚轮放大缩小
	if (document.createElement('canvas').getContext) { // 判断当前浏览器是否支持绘制海量点
		var points = []; // 添加海量点数据
		for ( var i = 0; i < data.data.length; i++) {
			var p = new BMap.Point(data.data[i].lng, data.data[i].lat);
			p.sId = data.data[i].sId;
			p.siteName = data.data[i].siteName;
			p.traffic = data.data[i].traffic;
			p.voltage = data.data[i].voltage;
			p.sendTime = data.data[i].sendTime;
			p.remoteAddress = data.data[i].remoteAddress;
			points.push(p);
		}
		console.log(points)
		var options = {
			size : BMAP_POINT_SIZE_BIG,
			shape : BMAP_POINT_SHAPE_STAR,
			color : '#ff0000'
		}
		var pointCollection = new BMap.PointCollection(points, options); // 初始化PointCollection
		pointCollection.addEventListener('click', function(e) {

			// 从event中获取所点击站点的信息
			var sId = String(e.point.sId);
			var siteName = e.point.siteName;
			var traffic = e.point.traffic;
			var voltage = e.point.voltage;
			var sendTime = e.point.sendTime;
			var remoteAddress = e.point.remoteAddress;

		
			fusionLayerInfoWindow = new BMap.InfoWindow("<div class='googft-info-window'>" +
                    "<b>遥测站名称:</b><a href='search/remoteInfo.html'>" + siteName + "</a></b><br>" +
                    "<b>蓄水口瞬时流量: </b>" + traffic + "<br>" +
                    "<b>电源电压: </b>" + voltage + "<br>" +
                    "<b>观测时间: </b>" + sendTime + "<br>" +
                    "<b>遥测站地址: </b>" + remoteAddress + "<br>" +
                    "</div>"); // 创建信息窗口对象
			
			map.openInfoWindow(fusionLayerInfoWindow, e.point); // 开启信息窗口


		});
		map.addOverlay(pointCollection); // 添加Overlay
		
	} else {
		alert('请在chrome、safari、IE8+以上浏览器查看本示例');
	}
}