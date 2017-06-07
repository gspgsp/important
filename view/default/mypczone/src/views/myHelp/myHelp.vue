<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back">帮助</div>
            <!--back end-->
            <div class="blank"></div>
            <!--help begin-->
            <div class="help"><img src="http://pic.myplas.com/mypc/img/my1.png" width="520" height="2464"/></div>
        </div>
    </div>
</template>
<script>
import Leftmodel from "../../components/leftmodel";
import myView from "../../components/myView";
export default{
    components: {
        'Leftmodel':Leftmodel,
        'myView': myView
    }
}

$(function(){
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
            //设置右侧的宽度
            function setW ( gid1, gid2, elem, index ) {
                index = index || 0;
                var w = ( index === 1 ) ? $( document ).width() - gid1.width() : $( document ).width() - gid1.width() - gid2.width();
                elem.width( w );
            };
} );
</script>

