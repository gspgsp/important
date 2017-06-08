<template>
    <div id="app">
        <leftmodel></leftmodel>
        <myView></myView>
        <div class="right flt">
        	<div class="back"><a href="javascript:window.history.back();"></a>充值塑豆</div>
            <!--back end-->
            <div class="blank"></div>
            <!--recharge begin-->
            <div class="recharge"><img src="http://pic.myplas.com/mypc/img/my4.png" width="520" height="1159"/></div>
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
</script>

