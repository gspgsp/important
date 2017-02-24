<template>
<div class="vote-person2">
	<img src="http://pic.myplas.com/myapp/img/votetitlebg2.jpg">
	<div class="vote-wrap2">
		<div class="vote-box2">
			<h3>打分规则：一颗星代表1分，满分5分</h3>
			<ul class="vote-ul">
				<!--<li v-for="p in program">{{p.id}}.{{p.show_name.split(" ")[0]}}<br>{{p.show_name.split(" ")[1]}}
				<span>{{opt}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt>0}" v-on:click="chs(1)"></li>
					<li v-bind:class="{on:opt>1}" v-on:click="chs(2)"></li>
					<li v-bind:class="{on:opt>2}" v-on:click="chs(3)"></li>
					<li v-bind:class="{on:opt>3}" v-on:click="chs(4)"></li>
					<li v-bind:class="{on:opt>4}" v-on:click="chs(5)"></li>
				</ul>
				</li>-->
				<li>1.临沂<br>舞蹈《屯儿》
				<span>{{opt2}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt>0}" v-on:click="chs(1)"></li>
					<li v-bind:class="{on:opt>1}" v-on:click="chs(2)"></li>
					<li v-bind:class="{on:opt>2}" v-on:click="chs(3)"></li>
					<li v-bind:class="{on:opt>3}" v-on:click="chs(4)"></li>
					<li v-bind:class="{on:opt>4}" v-on:click="chs(5)"></li>
				</ul>
				</li>
				<li>2.淄博<br>舞蹈串烧
				<span>{{opt2}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt2>0}" v-on:click="chs2(1)"></li>
					<li v-bind:class="{on:opt2>1}" v-on:click="chs2(2)"></li>
					<li v-bind:class="{on:opt2>2}" v-on:click="chs2(3)"></li>
					<li v-bind:class="{on:opt2>3}" v-on:click="chs2(4)"></li>
					<li v-bind:class="{on:opt2>4}" v-on:click="chs2(5)"></li>
				</ul>
				</li>
				<li>3.火焰战队+超人战队<br>《中晨演员之武侠剧》
				<span>{{opt3}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt3>0}" v-on:click="chs3(1)"></li>
					<li v-bind:class="{on:opt3>1}" v-on:click="chs3(2)"></li>
					<li v-bind:class="{on:opt3>2}" v-on:click="chs3(3)"></li>
					<li v-bind:class="{on:opt3>3}" v-on:click="chs3(4)"></li>
					<li v-bind:class="{on:opt3>4}" v-on:click="chs3(5)"></li>
				</ul>
				</li>
				<li>4.财务部<br>《激光舞》
				<span>{{opt4}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt4>0}" v-on:click="chs4(1)"></li>
					<li v-bind:class="{on:opt4>1}" v-on:click="chs4(2)"></li>
					<li v-bind:class="{on:opt4>2}" v-on:click="chs4(3)"></li>
					<li v-bind:class="{on:opt4>3}" v-on:click="chs4(4)"></li>
					<li v-bind:class="{on:opt4>4}" v-on:click="chs4(5)"></li>
				</ul>
				</li>
				<li>5.雄狮战队<br>合唱《好兄弟》
				<span>{{opt5}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt5>0}" v-on:click="chs5(1)"></li>
					<li v-bind:class="{on:opt5>1}" v-on:click="chs5(2)"></li>
					<li v-bind:class="{on:opt5>2}" v-on:click="chs5(3)"></li>
					<li v-bind:class="{on:opt5>3}" v-on:click="chs5(4)"></li>
					<li v-bind:class="{on:opt5>4}" v-on:click="chs5(5)"></li>
				</ul>
				</li>
				<li>6.雷霆战队<br>舞蹈《社会摇》
				<span>{{opt6}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt6>0}" v-on:click="chs6(1)"></li>
					<li v-bind:class="{on:opt6>1}" v-on:click="chs6(2)"></li>
					<li v-bind:class="{on:opt6>2}" v-on:click="chs6(3)"></li>
					<li v-bind:class="{on:opt6>3}" v-on:click="chs6(4)"></li>
					<li v-bind:class="{on:opt6>4}" v-on:click="chs6(5)"></li>
				</ul>
				</li>
				<li>7.常州<br>独唱《老男孩》
				<span>{{opt7}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt7>0}" v-on:click="chs7(1)"></li>
					<li v-bind:class="{on:opt7>1}" v-on:click="chs7(2)"></li>
					<li v-bind:class="{on:opt7>2}" v-on:click="chs7(3)"></li>
					<li v-bind:class="{on:opt7>3}" v-on:click="chs7(4)"></li>
					<li v-bind:class="{on:opt7>4}" v-on:click="chs7(5)"></li>
				</ul>
				</li>
				<li>8.人事+物流<br>舞蹈《浪漫樱花》
				<span>{{opt8}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt8>0}" v-on:click="chs8(1)"></li>
					<li v-bind:class="{on:opt8>1}" v-on:click="chs8(2)"></li>
					<li v-bind:class="{on:opt8>2}" v-on:click="chs8(3)"></li>
					<li v-bind:class="{on:opt8>3}" v-on:click="chs8(4)"></li>
					<li v-bind:class="{on:opt8>4}" v-on:click="chs8(5)"></li>
				</ul>
				</li>
				<li>9.木塑部<br>小品《人才难得》
				<span>{{opt9}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt9>0}" v-on:click="chs9(1)"></li>
					<li v-bind:class="{on:opt9>1}" v-on:click="chs9(2)"></li>
					<li v-bind:class="{on:opt9>2}" v-on:click="chs9(3)"></li>
					<li v-bind:class="{on:opt9>3}" v-on:click="chs9(4)"></li>
					<li v-bind:class="{on:opt9>4}" v-on:click="chs9(5)"></li>
				</ul>
				</li>
				<li>10.余姚<br>舞蹈《黑白舞》
				<span>{{opt10}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt10>0}" v-on:click="chs10(1)"></li>
					<li v-bind:class="{on:opt10>1}" v-on:click="chs10(2)"></li>
					<li v-bind:class="{on:opt10>2}" v-on:click="chs10(3)"></li>
					<li v-bind:class="{on:opt10>3}" v-on:click="chs10(4)"></li>
					<li v-bind:class="{on:opt10>4}" v-on:click="chs10(5)"></li>
				</ul>
				</li>
				<li>11.战狼战队  徐胜<br>独唱《风继续吹》
				<span>{{opt11}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt11>0}" v-on:click="chs11(1)"></li>
					<li v-bind:class="{on:opt11>1}" v-on:click="chs11(2)"></li>
					<li v-bind:class="{on:opt11>2}" v-on:click="chs11(3)"></li>
					<li v-bind:class="{on:opt11>3}" v-on:click="chs11(4)"></li>
					<li v-bind:class="{on:opt11>4}" v-on:click="chs11(5)"></li>
				</ul>
				</li>
				<li>12.电商部<br>歌舞《青春修炼手册》
				<span>{{opt12}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt12>0}" v-on:click="chs12(1)"></li>
					<li v-bind:class="{on:opt12>1}" v-on:click="chs12(2)"></li>
					<li v-bind:class="{on:opt12>2}" v-on:click="chs12(3)"></li>
					<li v-bind:class="{on:opt12>3}" v-on:click="chs12(4)"></li>
					<li v-bind:class="{on:opt12>4}" v-on:click="chs12(5)"></li>
				</ul>
				</li>
				<li>13.电商部 李厂<br>独唱《海阔天空》
				<span>{{opt13}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt13>0}" v-on:click="chs13(1)"></li>
					<li v-bind:class="{on:opt13>1}" v-on:click="chs13(2)"></li>
					<li v-bind:class="{on:opt13>2}" v-on:click="chs13(3)"></li>
					<li v-bind:class="{on:opt13>3}" v-on:click="chs13(4)"></li>
					<li v-bind:class="{on:opt13>4}" v-on:click="chs13(5)"></li>
				</ul>
				</li>
				<li>14.敢死队<br>相声《歪批山海经》
				<span>{{opt14}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt14>0}" v-on:click="chs14(1)"></li>
					<li v-bind:class="{on:opt14>1}" v-on:click="chs14(2)"></li>
					<li v-bind:class="{on:opt14>2}" v-on:click="chs14(3)"></li>
					<li v-bind:class="{on:opt14>3}" v-on:click="chs14(4)"></li>
					<li v-bind:class="{on:opt14>4}" v-on:click="chs14(5)"></li>
				</ul>
				</li>
				<li>15.盘锦 舞蹈<br>《大王叫我来巡山》
				<span>{{opt15}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt15>0}" v-on:click="chs15(1)"></li>
					<li v-bind:class="{on:opt15>1}" v-on:click="chs15(2)"></li>
					<li v-bind:class="{on:opt15>2}" v-on:click="chs15(3)"></li>
					<li v-bind:class="{on:opt15>3}" v-on:click="chs15(4)"></li>
					<li v-bind:class="{on:opt15>4}" v-on:click="chs15(5)"></li>
				</ul>
				</li>
				<li>16.孙伟+甘沛文<br>《如此照心》
				<span>{{opt16}}分</span>
				<ul class="star">
					<li v-bind:class="{on:opt16>0}" v-on:click="chs16(1)"></li>
					<li v-bind:class="{on:opt16>1}" v-on:click="chs16(2)"></li>
					<li v-bind:class="{on:opt16>2}" v-on:click="chs16(3)"></li>
					<li v-bind:class="{on:opt16>3}" v-on:click="chs16(4)"></li>
					<li v-bind:class="{on:opt16>4}" v-on:click="chs16(5)"></li>
				</ul>
				</li>
			</ul>
		</div>
		<div class="vote-btn2" v-on:click="toSubmit">
			提交
		</div>
	<div class="login2" v-show="loginShow">
		<input class="weui-input mobile" maxlength="11" v-model="mobile" type="tel" placeholder="请输入您的手机号" />
		<input class="weui-input code" type="tel" v-model="code" placeholder="验证码" /><span class="sendcode" v-on:click="sendCode">{{validCode}}</span>
		<div class="tovote" v-on:click="toVote">马上去打分</div>
	</div>
	<div class="layer" v-show="loginShow"></div>
	</div>
</div>
</template>
<script>
module.exports = {
	data: function() {
		return {
			mobile:"",
			code:"",
			times: 60,
			validCode: "点击获取验证码",
			loginShow:true,
			userid:"",
			voteShow:[],
			opt:0,
			opt2:0,
			opt3:0,
			opt4:0,
			opt5:0,
			opt6:0,
			opt7:0,
			opt8:0,
			opt9:0,
			opt10:0,
			opt11:0,
			opt12:0,
			opt13:0,
			opt14:0,
			opt15:0,
			opt16:0
		}
	},
	methods: {
		chs:function(i){
			this.opt=i;
		},
		chs2:function(i){
			this.opt2=i;
		},
		chs3:function(i){
			this.opt3=i;
		},
		chs4:function(i){
			this.opt4=i;
		},
		chs5:function(i){
			this.opt5=i;
		},
		chs6:function(i){
			this.opt6=i;
		},
		chs7:function(i){
			this.opt7=i;
		},
		chs8:function(i){
			this.opt8=i;
		},
		chs9:function(i){
			this.opt9=i;
		},
		chs10:function(i){
			this.opt10=i;
		},
		chs11:function(i){
			this.opt11=i;
		},
		chs12:function(i){
			this.opt12=i;
		},
		chs13:function(i){
			this.opt13=i;
		},
		chs14:function(i){
			this.opt14=i;
		},
		chs15:function(i){
			this.opt15=i;
		},
		chs16:function(i){
			this.opt16=i;
		},
		sendCode: function() {
			var _this = this;
		    if (this.mobile) {	
        		$.ajax({
					url: '/vote/index/sendmsg',
					type: 'get',
					data: {
						mobile:_this.mobile
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						var countStart=setInterval(function(){
							_this.validCode=_this.times-- +'秒后重发';
							console.log(">>>",_this.times);
							if(_this.times<0){
								clearInterval(countStart);
								_this.validCode="获取验证码";
								_this.times=60;
							}
						},1000);
					} else if(res.err==1){
						weui.alert(res.msg, function(){ 
							
						});
					}
				}, function() {

				}); 			
    		} else{
				weui.alert('请填写手机号', function(){
					
				});    		
    		}
		},
		toVote:function(){
			var _this=this;
			if(this.mobile&&this.code){
        		$.ajax({
					url: '/vote/index/checkPrizeUser',
					type: 'get',
					data: {
						mobile:_this.mobile,
						code:_this.code,
						type:1
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						_this.userid=res.userid;
						weui.alert(res.msg, function(){ 
							_this.loginShow=false;
						});
					} else if(res.err==1){
						weui.alert(res.msg, function(){ 
						
						});
					}
				}, function() {

				});				
			}else{
				weui.alert('请填写手机号和验证码', function(){
					
				});    						
			}
		},
		toSubmit:function(){
			var _this=this;
			if(this.opt&&this.opt2&&this.opt3&&this.opt4&&this.opt5&&this.opt6&&this.opt7&&this.opt8&&this.opt9&&this.opt10&&this.opt11&&this.opt12&&this.opt13&&this.opt14&&this.opt15&&this.opt16){
        		$.ajax({
					url: '/vote/index/saveAnswer',
					type: 'get',
					data: {
						type:2,
						userid:_this.userid,
						vote_show:JSON.stringify([_this.opt,_this.opt2,_this.opt3,_this.opt4,_this.opt5,_this.opt6,_this.opt7,_this.opt8,_this.opt9,_this.opt10,_this.opt11,_this.opt12,_this.opt13,_this.opt14,_this.opt15,_this.opt16])
					},
					dataType: 'JSON'
				}).then(function(res) {
					if(res.err == 0) {
						weui.alert(res.msg, function(){ 
							
						});
					} else if(res.err==2){
						weui.alert(res.msg, function(){ 
						
						});
					}
				}, function() {

				});				
				
			}else{
				weui.alert('还有节目没评分', function(){
					
				});    										
			}
		}
	},
	mounted: function() {

	}
}
</script>