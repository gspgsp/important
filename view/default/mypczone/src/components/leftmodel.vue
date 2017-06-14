<template>
  <div class="leftmodel">
    <!--left begin-->
    <div class="left flt">
    	<!--pic begin-->
        <div class="pic">
            <img v-bind:src="leftpi"onerror="this.src='http://pic.myplas.com/mypc/img/female.jpg'">
            <div class="authen no">V</div>
        </div>
        <!--pic end-->
        <ul>
            <li id="left1" class=""><a href="/mypczone/index"><span class="menu1"></span>通讯录</a></li>
            <li id="left2" class=""><a href="/mypczone/index/supplybuy"><span class="menu2"></span>供求</a></li>
            <li id="left3" class=""><a href="/mypczone/index/find"><span class="menu3"></span>发现</a></li>
            <li id="left4" class=""><a href="/mypczone/index/my"><span class="menu4"></span>我的</a></li>
            <!--<li id="left5" class=""><a href="/mypczone/index/login">登录</a></li>-->
        </ul>
    </div>
    <!--left end-->
  </div>
</template>
<script src="__MYPC__/js/jquery-1.8.3.min.js"></script>
<script>
$( function () {
	var left = $( ".left" ),
		left2 = $( "#left2" ),
		center = $( ".center" ),
		right = $( ".right" ),
		html = $( "html" ),
		index = 0,
		h = $( window ).height();
	
	//设置各个部分的高度
	left.height( h );
	center.height( h );
	right.height( h );
	html.height( h );

	//设置右侧的宽度
	setW( left, center, right );
	
	$( window ).resize( function () {
		setW( left, center, right );
	} );
	
	
	//点击左侧菜单
	left.find( "li" ).bind( "click", function () {
		var _this = $(this);
		_this.addClass( "hover" ).siblings().removeClass( "hover" );
		index = _this.index();
		
		//index === 0,点击通讯录
		//index === 1,点击供求
		//index === 2,点击发现
		//index === 3,点击我的
		
		gid( center, index );
		setW( left, center, right, index );
		if( index === 0 ){
			center.load( "center.html" );
			right.load( "right.html" );
		} else if ( index === 1 ) {
			right.load( "buy-sell.html" );
		} else if ( index === 2 ) {
			center.load( "center2.html" );
			right.load( "right.html" );
		} else if ( index === 3 ) {
			center.load( "center3.html" );
			right.load( "right.html" );
		}

	} );
	
	//登录
	$( "#left5" ).bind( "click", function () {
		getLogin();
	} );
	
	//设置布局
	var gid = function ( elem, index ) {
		if ( index === 1 ) {
			elem.hide();
		} else {
			elem.show();
		}
	}
	
	//跳转到登录
	function getLogin () {
		gid( center, 1 );
		setW( left, center, right, 1 );
		right.load( "login.html" );
	}
	
	//设置右侧的宽度
	function setW ( gid1, gid2, elem, index ) {
		index = index || 0;
		var w = ( index === 1 ) ? $( document ).width() - gid1.width() : $( document ).width() - gid1.width() - gid2.width();
		elem.width( w );
	};
} );
	
export default {
  name: 'leftmodel',
  data () {
    return {
      leftpi: window.localStorage.getItem("leftpi")
    }
  },
  mounted: function() {
		var _this = this;
		var url = window.location.pathname;
	    var uri = url.substring(url.lastIndexOf('/')+1, url.length);
		switch(uri) {
			case 'index':
			case 'indexinfo':
			case 'infobuy':
			case 'infosale':
			case 'mypczone':			
				document.getElementById( "left1" ).className = "hover"; 
				break;
			case 'supplybuy':
                                                document.getElementById( "left2" ).className = "hover";
				break;
			case 'find':
			case 'headline':
			case 'checkSelf':
			case 'checkOther':
				document.getElementById( "left3" ).className = "hover";

				break;
			case 'my':
			case 'mySupply':
			case 'myIntro':
			case 'myComment':
			case 'mySudou':
			case 'myHelp':
			case 'myEdit':
				document.getElementById( "left4" ).className = "hover";
				break;
			case 'login':
			case 'register':
			case 'findpwd':
			case 'agreement':			
				document.getElementById( "left5" ).className = "hover";
				break;
		}
	}
}
</script>


