<template>
<div class="vote-person">
	<img src="http://pic.myplas.com/myapp/img/votetitlebg.jpg">
	<div class="vote-wrap">
		<div class="vote-box">
			<h3>1、2016年度优秀员工</h3>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in ots_employee" class="weui-cell vote weui-check__label" v-bind:for="'employee'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="employee" name="checkbox1" v-bind:id="'employee'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.name}}<span><b>得票数:{{b.v_score}}票</b></span></p>
                </div>
            </label>
        </div>
		</div>
		<div class="vote-box">
			<h3>2、2016年度最佳优秀团队<span>(销售团队和销售支持各投一个)</span></h3>
			<div class="vote-team"></div>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in bst_team" v-if="b.team_type==1" class="weui-cell vote weui-check__label" v-bind:for="'team'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="team" name="team" v-bind:id="'team'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.team_name}}<span><b>得票数:{{b.vote_count}}票</b></span></p>
                </div>
            </label>
        </div>
        	<div class="vote-team2"></div>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in bst_team" v-if="b.team_type==2" class="weui-cell vote weui-check__label" v-bind:for="'team2'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="team2" name="team2" v-bind:id="'team2'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.team_name}}<span><b>得票数:{{b.vote_count}}票</b></span></p>
                </div>
            </label>
        </div>
		</div>
		<div class="vote-box">
			<h3>3、最佳销售团队负责人</h3>
			<h3>分公司</h3>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in sal_director" v-if="b.bel_sh==2" class="weui-cell vote weui-check__label" v-bind:for="'director'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="director" name="director" v-bind:id="'director'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.name}}<span><b>得票数:{{b.v_score}}票</b></span></p>
                </div>
            </label>
        </div>
        	<h3>上海</h3>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in sal_director" v-if="b.bel_sh==1" class="weui-cell vote weui-check__label" v-bind:for="'director2'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="director2" name="director2" v-bind:id="'director2'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.name}}<span><b>得票数:{{b.v_score}}票</b></span></p>
                </div>
            </label>
        </div>
		</div>
		<div class="vote-box">
			<h3>4、2016年度最佳部门经理奖</h3>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in dep_meneger" class="weui-cell vote weui-check__label" v-bind:for="'meneger'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="meneger" name="meneger" v-bind:id="'meneger'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.name}}<span><b>得票数:{{b.v_score}}票</b></span></p>
                </div>
            </label>
        </div>
		</div>
		<div class="vote-box">
			<h3>5、2016年度最佳新人</h3>
			<div class="weui-cells weui-cells_checkbox vote">
            <label v-for="b in bst_ncomer" class="weui-cell vote weui-check__label" v-bind:for="'ncomer'+b.id">
                <div class="weui-cell__hd">
                    <input type="radio" class="weui-check" v-bind:value="b.id" v-model="ncomer" name="ncomer" v-bind:id="'ncomer'+b.id">
                    <i class="weui-icon-checked"></i>
                </div>
                <div class="weui-cell__bd">
                    <p>{{b.name}}<span><b>得票数:{{b.v_score}}票</b></span></p>
                </div>
            </label>
        </div>
		</div>
		<div class="vote-btn" v-on:click="toSubmit">提交</div>
	<div class="login" v-show="loginShow">
		<input class="weui-input mobile" maxlength="11" v-model="mobile" type="tel" placeholder="请输入您的手机号" />
		<input class="weui-input code" type="tel" v-model="code" placeholder="验证码" /><span class="sendcode" v-on:click="sendCode">{{validCode}}</span>
		<div class="tovote" v-on:click="toVote">马上去投票</div>
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
			ncomer:"",
			meneger:"",
			director2:"",
			director:"",
			team2:"",
			team:"",
			employee:"",
			bst_ncomer:[],
			bst_team:[],
			dep_meneger:[],
			ots_employee:[],
			sal_director:[],
			disable:false
		}
	},
	methods: {
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
						code:_this.code
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
			if(this.ncomer&&this.meneger&&this.director2&&this.director&&this.team2&&this.team&&this.employee){
        		$.ajax({
					url: '/vote/index/saveAnswer',
					type: 'get',
					data: {
						type:1,
						userid:_this.userid,
						ots_employee:{
							id:_this.employee
						},
						bst_team:{
							id:_this.team
						},
						bst_sup:{
							id:_this.team2
						},
						sal_dir_sh:{
							id:_this.director
						},
						sal_dir_ot:{
							id:_this.director2
						},
						dep_meneger:{
							id:_this.meneger
						},
						bst_ncomer:{
							id:_this.ncomer
						}
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
				weui.alert('还有选项没选', function(){
					
				});    										
			}
		}
	},
	mounted: function() {
		var _this=this;
		$.ajax({
			type:"get",
			url:"/vote/index/getFirstData",
			data:{},
			dataType:'JSON'
		}).then(function(res){
			if(res.err==0){
				_this.bst_ncomer=res.data.bst_ncomer;
				_this.bst_team=res.data.bst_team;
				_this.dep_meneger=res.data.dep_meneger;
				_this.ots_employee=res.data.ots_employee;
				_this.sal_director=res.data.sal_director;	
			}
		},function(){
			
		});
	}
}
</script>