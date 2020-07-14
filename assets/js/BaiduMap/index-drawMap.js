var map;
var fusionLayerInfoWindow;
function drawMap_Baidu() {

	map = new BMap.Map("map_canvas", {}); // ����Mapʵ��
	map.centerAndZoom(new BMap.Point(-76.609, 39.395), 9); // ��ʼ����ͼ,�������ĵ�����͵�ͼ����
	map.enableScrollWheelZoom(); // ���ù��ַŴ���С
	if (document.createElement('canvas').getContext) { // �жϵ�ǰ������Ƿ�֧�ֻ��ƺ�����
		var points = []; // ��Ӻ���������
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
		var pointCollection = new BMap.PointCollection(points, options); // ��ʼ��PointCollection
		pointCollection.addEventListener('click', function(e) {

			// ��event�л�ȡ�����վ�����Ϣ
			var sId = String(e.point.sId);
			var siteName = e.point.siteName;
			var traffic = e.point.traffic;
			var voltage = e.point.voltage;
			var sendTime = e.point.sendTime;
			var remoteAddress = e.point.remoteAddress;

		
			fusionLayerInfoWindow = new BMap.InfoWindow("<div class='googft-info-window'>" +
                    "<b>ң��վ����:</b><a href='search/remoteInfo.html'>" + siteName + "</a></b><br>" +
                    "<b>��ˮ��˲ʱ����: </b>" + traffic + "<br>" +
                    "<b>��Դ��ѹ: </b>" + voltage + "<br>" +
                    "<b>�۲�ʱ��: </b>" + sendTime + "<br>" +
                    "<b>ң��վ��ַ: </b>" + remoteAddress + "<br>" +
                    "</div>"); // ������Ϣ���ڶ���
			
			map.openInfoWindow(fusionLayerInfoWindow, e.point); // ������Ϣ����


		});
		map.addOverlay(pointCollection); // ���Overlay
		
	} else {
		alert('����chrome��safari��IE8+����������鿴��ʾ��');
	}
}