/*设置折线图开始*/
//路径配置
require.config({
  paths: {
	  echarts: 'http://statics.myplas.com/js/home/dist'
	  }
});
//使用
require(
  [
	  'echarts',
	  'echarts/chart/line'   // 按需加载所需图表，如需动态类型切换功能，别忘了同时加载相应图表
  ],
  function (ec) {
	  // 基于准备好的dom，初始化echarts图表
	var conTaba2 = ec.init(document.getElementById('con-taba-2'));   //WTI
	var conTaba3 = ec.init(document.getElementById('con-taba-3'));   //布油
	//var conTaba4 = ec.init(document.getElementById('con-taba-4'));   //LDPE
	//var conTaba5 = ec.init(document.getElementById('con-taba-5'));   //LLDPE
	//var conTaba6 = ec.init(document.getElementById('con-taba-6'));   //PP
	//var conTaba7 = ec.init(document.getElementById('con-taba-7'));   //PVC
	//var conTaba8 = ec.init(document.getElementById('con-taba-8'));   //HDPE

	/*********WTI开始*******/
	 option1 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'200px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  	    margin: 0,
				 		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : oil1.oil.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max:""+oil1.oil.max+"",
				  min:""+oil1.oil.min+"",
				  type : 'value',
				  splitNumber:4,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -38,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:oil1.oil.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };
	  /*********WTI结束*******/

	 /*********布伦特油开始*******/
	 option2 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'200px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : oil2.oil.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max:""+oil2.oil.max+"",
				  min:""+oil2.oil.min+"",
				  type : 'value',
				  splitNumber:4,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -38,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:oil2.oil.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };
	  /*********布伦特油结束*******/

	  /********HDPE开始*******/
	  /*option3 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : quotation.HDPE.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.HDPE.max+"",
				  min: ""+quotation.HDPE.min+"",
				  type : 'value',
				  splitNumber:5,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.HDPE.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  /********HDPE结束*******/

	   /********LDPE开始*******/
	  /*option4 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show:false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : quotation.LDPE.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.LDPE.max+"",
				  min: ""+quotation.LDPE.min+"",
				  type : 'value',
				  splitNumber:4,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.LDPE.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  /********LDPE结束*******/

	   /********LLDPE开始*******/
	  /*option5 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : quotation.LLDPE.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.LLDPE.max+"",
				  min: ""+quotation.LLDPE.min+"",
				  type : 'value',
				  splitNumber:5,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.LLDPE.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  /********LLDPE结束*******/

	   /********均聚PP开始*******/
	  /*option6 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show:false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : quotation.均聚PP.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.均聚PP.max+"",
				  min: ""+quotation.均聚PP.min+"",
				  type : 'value',
				  splitNumber:5,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.均聚PP.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  /********PP结束*******/

	   /********PVC开始*******/
	  /*option7 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show:false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
				  		margin: 0,
				  		textStyle:{
							color:'#2d76a0'
						}
				  },
				  data : quotation.PVC.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.PVC.max+"",
				  min: ""+quotation.PVC.min+"",
				  type : 'value',
				  splitNumber:5,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.PVC.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  /********PVC结束*******/

	  /********共聚pp开始*******/
	  /*option8 = {
		  tooltip : {
		  	  show:false,
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'20px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show:false,lineStyle:{color:['#e4e4e4']}},//去掉网格
				  type : 'category',
				  axisTick:{ show:false}, //隐藏轴标记
				  boundaryGap : false,
				  axisLine: {  // 坐标轴线
					  show: false, // 默认显示，属性show控制显示与否
					  lineStyle: { // 属性lineStyle控制线条样式
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin:0,
					  textStyle:{
						  color:'#2d76a0'
					  }
				  },
				  data : quotation.共聚PP.date
			  }
		  ],
		  yAxis : [
			  {
				  splitLine:{
					  lineStyle:{color:['#e4e4e4']}
				  },
				  max: ""+quotation.共聚PP.max+"",
				  min: ""+quotation.共聚PP.min+"",
				  type : 'value',
				  splitNumber:5,
				  axisLine: {
					  show: false,
					  lineStyle: {
						  color: '#F00',
						  width: 1,
						  type: 'soild'
					  }
				  },
				  axisLabel : {
					  margin: -48,
					  formatter: '{value}'
				  }
			  }
		  ],
		  series : [
			  {
				  name:'价格',
				  type:'line',
				  symbol:'none', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#007aff',width:1}
					  }
				  },
				  data:quotation.共聚PP.price,
				  markPoint : {
					  data : [
						  //{name : '周最低', value : -2, xAxis: 1, yAxis: -1.5}
					  ]
				  },
				  markLine : {
					  data : [
						  //{type : 'average', name : '平均值'}
					  ]
				  }
			  }
		  ]
	  };*/
	  ///********共聚PP结束*******/


	  //为echarts对象加载数据
	  /*myChart1.setOption(option1);
	  myChart2.setOption(option2);*/
	  conTaba2.setOption(option1);      //WTI
	  conTaba3.setOption(option2);      //布油
	  //conTaba4.setOption(option4);      //LDPE
	  //conTaba5.setOption(option5);      //LLDPE
	  //conTaba6.setOption(option6);      //PP
	  //conTaba7.setOption(option7);      //PVC
	  //conTaba8.setOption(option3);      //HDPE
  }
);
/*设置折线图结束*/