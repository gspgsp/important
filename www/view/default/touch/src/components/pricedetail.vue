<template>
	<div class="buyWrap">
    <header id="bigCustomerHeader">
    	<a class="back" href="javascript:window.history.back();"></a>
        	实时成交价格
    </header>
    <div id="container" style=" height: 500px;"></div>
    <footer id="footer">
    	<div class="releaseSale">
    		<textarea placeholder="写下您真实的需求,包括牌号，吨数等，收到后我们会立即给您电话确认，剩下的交给我们吧"></textarea>
    	</div>
    	<div class="releaseBtn">免费<br>委托<br>发布</div>
    	<div class="border-bottom"></div>
        <ul>
            <li><a v-link="{name:'buy'}"><i class="foot"></i><br>我要买货</a></li>
            <li><a v-link="{name:'sale'}"><i class="foot2"></i><br>我要卖货</a></li>
            <li><a v-link="{name:'price'}" class="footerOn"><i class="foot3"></i><br>实时价格</a></li>
            <li><a v-link="{name:'todayindex'}"><i class="foot4"></i><br>行情指数</a></li>
        </ul>
    </footer>
    </div>
</template>
<script>
	module.exports={
        el:"#app",
        data:function () {
            return {
                largrBid:[],
                choseData:[],
                open:false,
                itemIndex:-1
            }
        },
        methods:{
            itemsActive:function (index) {
                this.itemIndex=index ? index : 0;
            },
            toggle:function () {

            }
        },
        ready:function () {
        	$('#container').highcharts({
		        chart: {
		            type: 'line'
		        },
		        title: {
		            text: 'HDPE 7000F 伊朗国家石化'
		        },
		        subtitle: {
		            text: '2016-07-06 2016-07-06'
		        },
		        xAxis: {
		            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May']
		        },
		        yAxis: {
		            title: {
		                text: ''
		            }
		        },
		        plotOptions: {
		            line: {
		                dataLabels: {
		                    enabled: true
		                },
		                enableMouseTracking: true
		            }
		        },
		        series: [{
		            name: 'Tokyo',
		            data: [7.0, 6.9, 9.5, 14.5, 18.4]
		        }]
    		});
        }
    
	}
</script>