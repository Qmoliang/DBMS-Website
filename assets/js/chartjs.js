var randomColorGenerator = function () { 
    return '#' + (Math.random().toString(16) + '0000000').slice(2, 8); 
};


function random_rgba() {
    var o = Math.round, r = Math.random, s = 255;
    return 'rgba(' + o(r()*s) + ',' + o(r()*s) + ',' + o(r()*s) + ',' +'0.2' + ')';
}


function linec(c_num) {
  var ctx, myLineChart, options;
  Chart.defaults.global.responsive = true;
  //ctx = $('#line-chart').get(0).getContext('2d');
  ctx = document.getElementById('line-chart'+c_num).getContext('2d');
  options = {
	tooltips: {
					mode: 'index',
					intersect: false
				},
	scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Month'
						}
					}],
					yAxes: [{
						display: true,
						//stacked: true,
						scaleLabel: {
							display: true,
							labelString: 'Value'
						}
					}]
				},
	legend: {
					display: true,
					position: 'bottom'
					},
    /*scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: false,
    bezierCurveTension: 0.4,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
	*/
  };
  
  if(istacked==100)
  {
	  options.scales.yAxes[0].stacked=true;
  }
  
  options.scales.yAxes[0].scaleLabel.labelString=ylabelString;
  
  if(c_num==1&&q5c1==1){
  options.scales.xAxes[0].scaleLabel.labelString="day";}
  
    if(q6c1==100){
  options.scales.xAxes[0].scaleLabel.labelString="Top 10 products";}
  
  
  if(line_num==1)
  {
	  console.log(line_num);
	var str1= ' labels: ["hahaha", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],\
    datasets: [\
      {\
        label: "My First dataset",\
        backgroundColor: "rgba(32,176,44,0.2)",\
        borderColor: "#1ABC9C",\
        data: [765, 59, 80, 81, 56, 55, 40]\
      }\
    ]';
	console.log(str1);
	eval('data = {' + str1 + '}');
  }
  else
  {
	  console.log(line_num);
	  var str1= ' labels: ["hahaha", "Feb", "Mar", "Apr", "May", "Jun", "Jul"],\
    datasets: [\
      {\
        label: "My First dataset",\
        backgroundColor: "rgba(32,176,44,0.2)",\
        borderColor: "#1ABC9C",\
        data: [765, 59, 80, 81, 56, 55, 40]\
      }';
	  
	  for (line_i = 0; line_i < line_num-1; line_i++) 
	  { 
	  str1=str1+', {\
        label: "My Second dataset",\
        backgroundColor: "rgba(34, 167, 240,0.2)",\
        borderColor: "#22A7F0",\
        data: [328, 48, 40, 19, 86, 27, 90]\
      }';
	  }
	  eval('data = {' + str1 + ']}');
	  
	  
	  
  }
	
	
	/*
	从页面中接收表格的各项值
	*/
	data.labels = pj_labels[c_num];
	data.datasets[0].data = pj_datasets_0_data[c_num][0];   //第0条的数据等于php传入的第0张图第0条线的数据
	data.datasets[0].label = pj_datasets_0_label[c_num][0];
	
	console.log(data.datasets[0].data);
	console.log(data.datasets[0].label);
	
	var colorArray = [["#FF4000", false], ["#81BEF7", false], ["#5882FA", false], 
                 ["#04B404", false], ["#A901DB", false],["#A901DB", false],["#3451DB", false],["#2331DB", false],["#A123DB", false], ["#F5A9BC", false]];   //新线备选颜色
	
	
	
	 for (line_i = 1; line_i < line_num; line_i++) 
	  { 
		data.datasets[line_i].data=pj_datasets_0_data[c_num][line_i];   //  第line_i条线的数据，等于从php传入的第0张图第line_i条线的数据。e.g.,第4条线的数据等于php传入的第0张图第4条的线的数据
		data.datasets[line_i].label = pj_datasets_0_label[c_num][line_i];
		
		var rcolor;   //随机选择不重复颜色
		while (true) {
									var test = colorArray[parseInt(Math.random() * 6)];
									if (!test[1]) {
															rcolor = test[0];
															colorArray[colorArray.indexOf(test)][1] = true;
															break;
															}
									}
		data.datasets[line_i].backgroundColor=random_rgba();
        data.datasets[line_i].borderColor=rcolor;
		console.log(data.datasets[line_i].borderColor);
  
		//data.datasets[1].data = JSON.parse(JSON.stringify(data.datasets[0].data));
		//data.datasets[1].data[0]='144423';
		//console.log(data.datasets[1].data[0]);
		//console.log(data.datasets[0].data[0]);
	  }
	
  /*data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: "My First dataset",
        fillColor: "rgba(26, 188, 156,0.2)",
        strokeColor: "#1ABC9C",
        pointColor: "#1ABC9C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        data: [365, 59, 80, 81, 56, 55, 40]
      }, {
        label: "My Second dataset",
        fillColor: "rgba(34, 167, 240,0.2)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [328, 48, 40, 19, 86, 27, 90]
      }
    ]
  };*/
  
  var nconfig=
  {
	type: 'line',
	data:{},
	options:{}
  }
  

  nconfig.data=data;
  nconfig.options=options;
  
      if(q6c1==100){
  nconfig.type="horizontalBar";}
  
  
  myLineChart = new Chart(ctx,nconfig);
  
  //myLineChart = new Chart(ctx).Line(data, options);
  console.log(data.datasets[0].label);
  //console.log(data.datasets[1].label);
}


$(function() 
{
	for(var cnum=0;cnum<chart_num;cnum++)
	{
	linec(cnum);
	}
});


/*
$(function() {
  var ctx, data, myBarChart, option_bars;
  Chart.defaults.global.responsive = true;
  ctx = $('#bar-chart').get(0).getContext('2d');
  option_bars = {
    scaleBeginAtZero: true,
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: false,
    barShowStroke: true,
    barStrokeWidth: 1,
    barValueSpacing: 5,
    barDatasetSpacing: 3,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: "My First dataset",
        fillColor: "rgba(26, 188, 156,0.6)",
        strokeColor: "#1ABC9C",
        pointColor: "#1ABC9C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        data: [265, 59, 80, 81, 56, 55, 40]
      }, {
        label: "My Second dataset",
        fillColor: "rgba(34, 167, 240,0.6)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [228, 48, 40, 19, 86, 27, 90]
      }
    ]
  };
  myBarChart = new Chart(ctx).Bar(data, option_bars);
});



$(function() {
  var ctx, data, myBarChart, option_bars;
  Chart.defaults.global.responsive = true;
  ctx = $('#radar-chart').get(0).getContext('2d');
  option_bars = {
    scaleBeginAtZero: true,
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: false,
    barShowStroke: false,
    barStrokeWidth: 0,
    barValueSpacing: 5,
    barDatasetSpacing: 1,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: "My First dataset",
        fillColor: "rgba(26, 188, 156,0.2)",
        strokeColor: "#1ABC9C",
        pointColor: "#1ABC9C",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#1ABC9C",
        data: [165, 59, 80, 81, 56, 55, 40]
      }, {
        label: "My Second dataset",
        fillColor: "rgba(34, 167, 240,0.2)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [128, 48, 40, 19, 86, 27, 90]
      }
    ]
  };
  myBarChart = new Chart(ctx).Radar(data, option_bars);
});

$(function() {
  var ctx, data, myPolarAreaChart, option_bars;
  Chart.defaults.global.responsive = true;
  ctx = $('#polar-area-chart').get(0).getContext('2d');
  option_bars = {
    scaleShowLabelBackdrop: true,
    scaleBackdropColor: "rgba(255,255,255,0.75)",
    scaleBeginAtZero: true,
    scaleBackdropPaddingY: 2,
    scaleBackdropPaddingX: 2,
    scaleShowLine: true,
    segmentShowStroke: true,
    segmentStrokeColor: "#fff",
    segmentStrokeWidth: 2,
    animationSteps: 100,
    animationEasing: "easeOutBounce",
    animateRotate: true,
    animateScale: false,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>"
  };
  data = [
    {
      value: 300,
      color: "#FA2A00",
      highlight: "#FA2A00",
      label: "Red"
    }, {
      value: 50,
      color: "#1ABC9C",
      highlight: "#1ABC9C",
      label: "Green"
    }, {
      value: 100,
      color: "#FABE28",
      highlight: "#FABE28",
      label: "Yellow"
    }, {
      value: 40,
      color: "#999",
      highlight: "#999",
      label: "Grey"
    }, {
      value: 120,
      color: "#22A7F0",
      highlight: "#22A7F0",
      label: "Blue"
    }
  ];
  myPolarAreaChart = new Chart(ctx).PolarArea(data, option_bars);
});

$(function() {
  var ctx, data, myLineChart, options;
  Chart.defaults.global.responsive = true;
  ctx = $('#pie-chart').get(0).getContext('2d');
  options = {
    showScale: false,
    scaleShowGridLines: false,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 0,
    scaleShowHorizontalLines: false,
    scaleShowVerticalLines: false,
    bezierCurve: false,
    bezierCurveTension: 0.4,
    pointDot: false,
    pointDotRadius: 0,
    pointDotStrokeWidth: 2,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 4,
    datasetFill: true,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = [
    {
      value: 300,
      color: "#FA2A00",
      highlight: "#FA2A00",
      label: "Red"
    }, {
      value: 50,
      color: "#1ABC9C",
      highlight: "#1ABC9C",
      label: "Green"
    }, {
      value: 100,
      color: "#FABE28",
      highlight: "#FABE28",
      label: "Yellow"
    }
  ];
  myLineChart = new Chart(ctx).Pie(data, options);
});

$(function() {
  var ctx, data, myLineChart, options;
  Chart.defaults.global.responsive = true;
  ctx = $('#jumbotron-line-chart').get(0).getContext('2d');
  options = {
    showScale: false,
    scaleShowGridLines: true,
    scaleGridLineColor: "rgba(0,0,0,.05)",
    scaleGridLineWidth: 1,
    scaleShowHorizontalLines: true,
    scaleShowVerticalLines: true,
    bezierCurve: false,
    bezierCurveTension: 0.4,
    pointDot: true,
    pointDotRadius: 4,
    pointDotStrokeWidth: 1,
    pointHitDetectionRadius: 20,
    datasetStroke: true,
    datasetStrokeWidth: 2,
    datasetFill: true,
    //legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].strokeColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
  };
  data = {
    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
    datasets: [
      {
        label: "My Second dataset",
        fillColor: "rgba(34, 167, 240,0.2)",
        strokeColor: "#22A7F0",
        pointColor: "#22A7F0",
        pointStrokeColor: "#fff",
        pointHighlightFill: "#fff",
        pointHighlightStroke: "#22A7F0",
        data: [28, 48, 40, 45, 76, 65, 90]
      }
    ]
  };
  myLineChart = new Chart(ctx).Line(data, options);
});


*/