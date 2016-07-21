/*设置折线图开始*/
//路径配置
require.config({
  paths: {
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
	var myChart1 = ec.init(document.getElementById('oil-blt-con'));
	var myChart2 = ec.init(document.getElementById('oil-wti-con'));

	var conTaba1 = ec.init(document.getElementById('con-taba-1'));
	var conTaba2 = ec.init(document.getElementById('con-taba-2'));
	var conTaba3 = ec.init(document.getElementById('con-taba-3'));
	var conTaba4 = ec.init(document.getElementById('con-taba-4'));
	var conTaba5 = ec.init(document.getElementById('con-taba-5'));
	var conTaba5 = ec.init(document.getElementById('con-taba-6'));


	 /*********布伦特油开始*******/
	 option1 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'30px',width:'100%',heigth:'200px'},  //设置折线图左上角的起点位置,以及宽高
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
				  		margin: 15,
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
	  /*********布伦特油结束*******/
	  /*********WTI开始*******/
	 option2 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'30px',width:'100%',heigth:'200px'},  //设置折线图左上角的起点位置,以及宽高
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
				  	    margin: 15,
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
	  /*********WTI结束*******/

	  /********HDPE开始*******/
	  option3 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
				  		margin: 15,
				  		textStyle:{
							color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  /********HDPE结束*******/

	   /********LDPE开始*******/
	  option4 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
				  		margin: 15,
				  		textStyle:{
							color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  /********LDPE结束*******/

	   /********LLDPE开始*******/
	  option5 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
				  		margin: 15,
				  		textStyle:{
							color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  /********LLDPE结束*******/

	   /********均聚PP开始*******/
	  option6 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
				  		margin: 15,
				  		textStyle:{
							color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  /********PP结束*******/

	   /********PVC开始*******/
	  option7 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
				  		margin: 15,
				  		textStyle:{
							color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  /********PVC结束*******/
	  ///********共聚PP开始*******/
	  option8 = {
		  tooltip : {
			  trigger: 'axis'
		  },
		  grid:{x:0,y:'4px',width:'100%',heigth:'149px'},  //设置折线图左上角的起点位置,以及宽高
		  calculable : true,
		  xAxis : [
			  {
				  splitLine:{show: true,lineStyle:{color:['#e4e4e4']}},//显示网格
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
					  margin: 15,
					  textStyle:{
						  color:'#fc6621'
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
				  symbol:'', //去掉折线上的圆点
				  itemStyle: {
					  normal: {
						  lineStyle:{ color:'#fc6621',width:1}
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
	  };
	  ///********共聚PP结束*******/


	  //为echarts对象加载数据
	  myChart1.setOption(option1);
	  myChart2.setOption(option2);
	  conTaba1.setOption(option3);
	  conTaba2.setOption(option4);
	  conTaba3.setOption(option5);
	  conTaba4.setOption(option6);
	  conTaba5.setOption(option7);
	  conTaba5.setOption(option8);
	  
  }
);
/*设置折线图结束*/