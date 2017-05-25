<?php
/**
 * pdf合同文档
*/
return array(
	//站内信模板
	'pdf_template'=>array(
		//注册成功
		'buy'=>'<style>
		h1 {
			font-size:20px;
			 text-align:center;
			}
		div.test{
			font-size:12px;
		}
		.first,tr{
		line-height: 19px;
		}
		.first,td{
		border:1px solid black;
		border-collapse:collapse;
		text-align:center;
		 }
		 .secend{
			border:1px solid black;
		 }
	</style>
	<h1>销售合同书(%s)</h1>
	<p><span>供方(甲方):&nbsp;%s</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>合同编号:&nbsp;%s</span></p>
	<p><span>需方(乙方):&nbsp;%s</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>签约地点:&nbsp;%s&nbsp;&nbsp;虹口</span></p>
	<p><span>签约时间:&nbsp;%s</span></p>
	<p>甲、乙双方经协商，就以下产品的购销等有关事宜，签订本合同</p>
	<span>一、产品名称、型号(规格)、产地、数量、单价、金额:</span><br/>
	<table class="first">
	%s
	</table>
	<p><span>二、质量标准要求:</span>&nbsp;&nbsp;<span>以生产厂家该牌号质量标准为准</span><p>
	<p><span>三、提货地点:</span>&nbsp;&nbsp;<span>%s</span><p>
	<p><span>四、提货时间:</span>&nbsp;&nbsp;<span>%s</span><p>
	<p><span>五、运输方式:</span>&nbsp;&nbsp;<span>%s</span><p>
	<p><span>六、包装标准:</span>&nbsp;&nbsp;<span>按生产企业的包装标准为准</span><p>
	<p><span>七、验收标准方式:&nbsp;&nbsp;按第一、第二条款验收期限:自到货之日期三天内验收。需方若有异议须在到货之日起三天内以书面形式向供方提出，否则，视为验收合格。合理损耗为总量的千分之三，凡在合理损耗范围内短缺，供方不负责赔偿。</span><p>
	<p><span>八、付款方式:</span>&nbsp;&nbsp;<span>%s</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>付款日期:</span>&nbsp;&nbsp;<span>%s</span></p>
	<p><span>九、货款结算方式:</span>&nbsp;&nbsp;<span>%s</span><p>
	<p><span>十、其它约定:</span>&nbsp;&nbsp;<span>1.需方付清全款后，供方开给需方17%%的增值税发票</span><br/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span>&nbsp;2.需方于合同签订当日下午16:30前盖章回传有效</span><p>
	<p><span>十一、违约责任:除了不可抗拒因素外(自然灾害，战争等)合同其它违约事项遵照《中华人民共和国合同法》承担违约责任。双方任何一方违反合同的任一条款，均视为违约。违约方需向对方支付本合同日千分之八的违约金。</span><p>
	<p><span>十二、本合同未尽事项，经双方友好协商作出补充规定的，补充规定与本合同具有同等的法律效力。</span><p>
	<p><span>十三、本合同如以传真方式确认，传真件与正本合同具有同等的法律效力。</span><p>
	<p><span>十四、甲、乙双方合同执行过程中产生纠纷，双方应本着友好合作的原则，协商解决，协商不成的，任何一方可依<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;据有关法律向人民法院起诉。</span><p>
	<p><span>十五、本合同一式两份，甲、乙双方各执一份，自签订合同之日起生效。</span><p>
	<table class="secend" border="1px solid" color="black" >
	%s
	<table>'
	),

);
?>
