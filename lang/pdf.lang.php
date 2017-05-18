<?php
return array(
'pdf_template'=>array(
	'tihuo'=>'<table width="635" border="0" align="center" bgcolor="#fff">
			<tr ><td>&nbsp;</td></tr>
			<tr >
				<td colspan="2" align="center"><h1>销售合同书</h1></td>
			</tr>
			<tr height="20"><td>&nbsp;</td></tr>
			<tr align="left">
				<td width="440" height="20">供方（甲方）：%s</td>
				<td height="20">合同编号：%s</td>
			</tr>
			<tr  align="left">
				<td  width="440" height="20">需方（乙方）：%s</td>
				<td height="20">签约地点：上海 虹口</td>
			</tr>
			<tr  align="left">
				<td height="20"></td>
				<td height="20">签约时间：%s</td>
			</tr>
			<tr align="left">
				<td colspan="2" height="20" width="600">甲、乙双方经协商，根据《中华人民共和国合同法》相关规定，就以下产品的购销等有关事宜，签订本合同</td>
			</tr>
		</table>
		<table width="635" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666">
			<tr>
				<td colspan="7" bgcolor="#FFFFFF" height="20" style="line-height:20px;">&nbsp;一、产品名称、型号（规格）、产地、数量、单价、金额：</td>
			</tr>
			%s
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30"  height="20" style="line-height:20px;">二、</td>
				<td width="100"  height="20" style="line-height:20px;">质量标准：</td>
				<td  width="465"  height="20" style="line-height:20px;">以生产厂家该牌号质量标准为准</td>
			</tr>
			<tr>
				<td></td>
				<td  height="20" style="line-height:20px;">包装标准：</td>
				<td  height="20" style="line-height:20px;">以生产厂家的包装标准为准</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30"  height="20" style="line-height:20px;">三、</td>
				<td width="100" align="left"  height="20" style="line-height:20px;">%s地点：</td>
				<td>%s</td>
			</tr>
			  <tr>
				<td></td>
				<td  height="20" style="line-height:20px;">%s日期：</td>
				<td>%s左右</td>
			</tr>
			<tr>
				<td></td>
				<td  width="100" height="20" style="line-height:20px;">运输方式及运费：</td>
				<td width="440"  height="20" style="line-height:20px;">运输方式按照以下第_%s_种。 1.乙方自提。2.甲方送货。3.甲方代办托运。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">四、</td>
				<td width="100" height="20" style="line-height:20px;">验收标准方式：</td>
				<td width="465" height="20" style="line-height:20px;">1、验收标准：按照本合同第一条、第二条标准。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;" ></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、验收方式：对于数量/包装有异议乙方须在到货当日以书面形式向甲方提出。对于质量或其他有异议的乙方须在到货之日起三日内，以书面形式向甲方提出。否则视为验收合格。
				</td>
			</tr>
			<tr>
					<td height="20" style="line-height:20px;"></td>
					<td height="20" style="line-height:20px;"></td>
					<td width="505" height="20" style="line-height:20px;">3、合理损耗为总量的千分之三，凡在合理损耗范围内短缺，甲方不负责赔偿。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">六、</td>
				<td width="100" height="20" style="line-height:20px;">付款方式：</td>
				<td width="505" height="20" style="line-height:20px;">%s</td>
			</tr>
			 <tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;">付款日期：</td>
				<td width="505" height="20" style="line-height:20px;">%s，%s</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">六、</td>
				<td width="100" height="20" style="line-height:20px;">所有权归属：</td>
				<td width="505" height="20" style="line-height:20px;">
					乙方付清全部货款前，无论货物是否交付，全部货物所有权归属甲方。乙方付清全部货款后，提货之日起货物所有权归属乙方，乙方付清全部货款后，提货之日起货物毁损灭失等风险由乙方承担。
				</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			 <tr>
				<td width="30" height="20" style="line-height:20px;">七、</td>
				<td width="100" height="20" style="line-height:20px;">违约责任：</td>
				<td width="505" height="20" style="line-height:20px;">1、甲方承担赔偿责任的， 仅限于乙方直接损失，且以相应货物价值为限。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、乙方逾期付款的，应向甲方支付违约金，违约金按照本合同总金额每日千分之八计算。因此产生的运输费、仓储费、诉讼费、保全费、律师费、差旅费、货物价格波动损失及其他损失由乙方承担。逾期付款超过三日的，甲方有权解除合同。
				</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">3、乙方逾期提货的，应向甲方支付违约金，违约金计算参照本条第2款。因此产生仓储费用等及其他损失由乙方承担。逾期提货超过三日的，甲方有权解除合同。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="465" height="20" style="line-height:20px;">4、乙方股东及法定代表人对上述违约责任承担连带责任。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">八、</td>
				<td width="100" height="20" style="line-height:20px;">争议解决：</td>
				<td width="505" height="20" style="line-height:20px;">双方合同履行过程中产生纠纷，应协商解决，协商不成的，应向甲方住所地人民法院起诉。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">九、</td>
				<td width="100" height="20" style="line-height:20px;">其他约定：</td>
				<td width="505" height="20" style="line-height:20px;">1、乙方付清全部货款后，甲方开始给乙方百分之十七的增值税发票。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">2、乙方于合同签订当日下午4:30前盖章回传有效，超过上述时间回传的无效。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">3、本合同自双方盖章后生效，一式两份，双方各执一份。传真件具有同等法律效力。</td>
			</tr>
			%s
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border-bottom:2px solid #000;">
			<tr>
				<td colspan="4" height="2" style="line-height:2px;"></td>
			</tr>
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border:2px solid #000;">
			<tr>
				<td colspan="4" height="20" style="line-height:20px;"></td>
			</tr>
			%s
		</table>',
	'zhuanyi'=>'<table width="635" border="0" align="center" bgcolor="#fff">
			<tr ><td>&nbsp;</td></tr>
			<tr >
				<td colspan="2" align="center"><h1>代购合同书</h1></td>
			</tr>
			<tr height="20"><td>&nbsp;</td></tr>
			<tr align="left">
				<td width="440" height="20">供方（甲方）：%s</td>
				<td height="20">合同编号：%s</td>
			</tr>
			<tr  align="left">
				<td  width="440" height="20">需方（乙方）：%s</td>
				<td height="20">签约地点：上海 虹口</td>
			</tr>
			<tr  align="left">
				<td height="20"></td>
				<td height="20">签约时间：%s</td>
			</tr>
			<tr align="left">
				<td colspan="2" height="20" width="600">甲、乙双方经协商，根据《中华人民共和国合同法》相关规定，就以下产品的购销等有关事宜，签订本合同</td>
			</tr>
		</table>
		<table width="635" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666">
			<tr>
				<td colspan="7" bgcolor="#FFFFFF" height="20" style="line-height:20px;">&nbsp;一、产品名称、型号（规格）、产地、数量、单价、金额：</td>
			</tr>
			%s
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30"  height="20" style="line-height:20px;">二、</td>
				<td width="70"  height="20" style="line-height:20px;">质量标准：</td>
				<td  width="265"  height="20" style="line-height:20px;">以生产厂家该牌号质量标准为准 </td>
				<td width="70"  height="20" style="line-height:20px;">包装标准：</td>
				<td width="170"  height="20" style="line-height:20px;">以生产厂家的包装标准为准</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30"  height="20" style="line-height:20px;">三、</td>
				<td width="70" align="left"  height="20" style="line-height:20px;">%s地点：</td>
				<td width="265"  height="20" style="line-height:20px;">%s</td>
				<td width="70" align="left"  height="20" style="line-height:20px;">%s日期：</td>
				<td width="170"  height="20" style="line-height:20px;">%s左右</td>
			</tr>
			  <tr>
				<td></td>
				<td  height="20" style="line-height:20px;" width="100">运输方式及运费：</td>
				<td width="440"  height="20" style="line-height:20px;">运输方式按照以下第_%s_种。 1.乙方自提。2.甲方送货。3.甲方代办托运。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">四、</td>
				<td width="100" height="20" style="line-height:20px;">验收标准方式：</td>
				<td width="465" height="20" style="line-height:20px;">1、验收标准：按照本合同第一条、第二条标准。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;" ></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、验收方式：对于数量、包装有异议乙方须在到货当日以书面形式向甲方提出。对于质量或其他有异议的乙方须在到货之日起三日内，以书面形式向甲方提出。否则视为验收合格。
				</td>
			</tr>
			<tr>
					<td height="20" style="line-height:20px;"></td>
					<td height="20" style="line-height:20px;"></td>
					<td width="505" height="20" style="line-height:20px;">3、合理损耗为总量的千分之三，凡在合理损耗范围内短缺，甲方不负责赔偿。</td>
			</tr>
		</table>
		%s
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">五、</td>
				<td width="70" height="20" style="line-height:20px;">付款方式：</td>
				<td width="265" height="20" style="line-height:20px;">%s</td>
				<td height="20" width="70" style="line-height:20px;">付款日期：</td>
				<td width="170" height="20" style="line-height:20px;">%s，%s</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">七、</td>
				<td width="100" height="20" style="line-height:20px;">所有权归属：</td>
				<td width="505" height="20" style="line-height:20px;">
					乙方付清全部货款前，无论货物是否交付，全部货物所有权归属甲方。乙方付清全部货款后，提货之日起货物所有权归属乙方，乙方付清全部货款后，提货之日起货物毁损灭失等风险由乙方承担。
				</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			 <tr>
				<td width="30" height="20" style="line-height:20px;">八、</td>
				<td width="100" height="20" style="line-height:20px;">违约责任：</td>
				<td width="505" height="20" style="line-height:20px;">1、甲方承担赔偿责任的， 仅限于乙方直接损失，且以相应货物价值为限。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、乙方逾期付款的，应向甲方支付违约金，违约金按照本合同总金额每日千分之八计算。因此产生的运输费、仓储费、诉讼费、保全费、律师费、差旅费、货物价格波动损失及其他损失由乙方承担。逾期付款超过三日的，甲方有权解除合同。
				</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">3、乙方逾期提货的，应向甲方支付违约金，违约金计算参照本条第2款。因此产生仓储费用等及其他损失由乙方承担。逾期提货超过三日的，甲方有权解除合同。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="465" height="20" style="line-height:20px;">4、乙方股东及法定代表人对上述违约责任承担连带责任。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">九、</td>
				<td width="100" height="20" style="line-height:20px;">争议解决：</td>
				<td width="505" height="20" style="line-height:20px;">双方合同履行过程中产生纠纷，应协商解决，协商不成的，应向甲方住所地人民法院起诉。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">十、</td>
				<td width="100" height="20" style="line-height:20px;">其他约定：</td>
				<td width="505" height="20" style="line-height:20px;">1、乙方付清全部货款后，甲方应向乙方开具百分之十七之的增值税发票。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">2、乙方于合同签订当日下午4:30前盖章回传有效，超过上述时间回传的无效。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">3、本合同自双方盖章后生效，一式两份，双方各执一份。传真件具有同等法律效力。</td>
			</tr>
			%s
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border-bottom:2px solid #000;">
			<tr>
				<td colspan="4" height="2" style="line-height:2px;"></td>
			</tr>
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border:2px solid #000;">
			<tr>
				<td colspan="4" height="20" style="line-height:20px;"></td>
			</tr>
			%s
		</table>',
	'weituo'=>'<table width="635" border="0" align="center" bgcolor="#fff">
			<tr ><td>&nbsp;</td></tr>
			<tr >
				<td colspan="2" align="center"><h1>采购合同书</h1></td>
			</tr>
			<tr height="20"><td>&nbsp;</td></tr>
			<tr align="left">
				<td width="440" height="20">供方（甲方）：%s</td>
				<td height="20">合同编号：%s</td>
			</tr>
			<tr  align="left">
				<td  width="440" height="20">需方（乙方）：%s</td>
				<td height="20">签约地点：上海 虹口</td>
			</tr>
			<tr  align="left">
				<td height="20"></td>
				<td height="20">签约时间：%s</td>
			</tr>
			<tr align="left">
				<td colspan="2" height="20" width="600">甲、乙双方经协商，根据《中华人民共和国合同法》相关规定，就以下产品的购销等有关事宜，签订本合同</td>
			</tr>
		</table>
		<table width="635" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#666">
			<tr>
				<td colspan="7" bgcolor="#FFFFFF" height="20" style="line-height:20px;">&nbsp;一、产品名称、型号（规格）、产地、数量、单价、金额：</td>
			</tr>
			%s
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30"  height="20" style="line-height:20px;">二、</td>
				<td width="100"  height="20" style="line-height:20px;">质量标准：</td>
				<td  width="465"  height="20" style="line-height:20px;">以生产厂家该牌号质量标准为准</td>
			</tr>
			<tr>
				<td></td>
				<td  height="20" style="line-height:20px;">包装标准：</td>
				<td  height="20" style="line-height:20px;">以生产厂家的包装标准为准</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			  <tr>
				<td width="30"  height="20" style="line-height:20px;">三、</td>
				<td width="100" align="left"  height="20" style="line-height:20px;">%s地点：</td>
				<td>%s</td>
			</tr>
			  <tr>
				<td></td>
				<td  height="20" style="line-height:20px;">%s日期：</td>
				<td>%s</td>
			</tr>
			  <tr>
				<td></td>
				<td  height="20" style="line-height:20px;">运输方式及运费：</td>
				<td width="440"  height="20" style="line-height:20px;">运输方式按照以下第_%s_种。 1.乙方自提。2.甲方送货。3.甲方代办托运。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">四、</td>
				<td width="100" height="20" style="line-height:20px;">验收标准方式：</td>
				<td width="465" height="20" style="line-height:20px;">1、验收标准：按照本合同第一条、第二条标准。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;" ></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、验收方式：对于数量、包装有异议，乙方须在到货当日以书面形式向甲方提出。对于质量或其他有异议的，乙方须在到货之日起三日内，以书面形式向甲方提出。否则，视为验收合格。
				</td>
			</tr>
			<tr>
					<td height="20" style="line-height:20px;"></td>
					<td height="20" style="line-height:20px;"></td>
					<td width="505" height="20" style="line-height:20px;">3、合理损耗为总量的千分之三，凡在合理损耗范围内短缺，甲方不负责赔偿。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">五、</td>
				<td width="100" height="20" style="line-height:20px;">付款方式：</td>
				<td width="505" height="20" style="line-height:20px;">%s</td>
			</tr>
			 <tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;">付款日期：</td>
				<td width="505" height="20" style="line-height:20px;">%s，%s</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">六、</td>
				<td width="100" height="20" style="line-height:20px;">所有权归属：</td>
				<td width="505" height="20" style="line-height:20px;">
					乙方付清全部货款后，相应货物所有权归属乙方。乙方收/提到全部货物之前，甲方负有保管义务，承担仓储费及其风险。
				</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			 <tr>
				<td width="30" height="20" style="line-height:20px;">七、</td>
				<td width="100" height="20" style="line-height:20px;">违约责任：</td>
				<td width="505" height="20" style="line-height:20px;">1、甲方逾期交货的，应向乙方支付违约金，违约金按照本合同总金额每日千分之八计算。因此产生的运输费、仓储费、诉讼费、保全费、律师费、差旅费、货物价格波动损失及其他损失由甲方承担。逾期交货超过三日的，乙方有权解除合同。</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">
					2、乙方承担赔偿责任的， 仅限于甲方直接损失，且以相应货物价值为限。
				</td>
			</tr>
			<tr>
				<td height="20" style="line-height:20px;"></td>
				<td height="20" style="line-height:20px;"></td>
				<td width="505" height="20" style="line-height:20px;">3、甲方股东及法定代表人对上述违约责任承担连带责任。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">八、</td>
				<td width="100" height="20" style="line-height:20px;">争议解决：</td>
				<td width="505" height="20" style="line-height:20px;">双方合同履行过程中产生纠纷，应协商解决，协商不成的，应向乙方住所地人民法院起诉。</td>
			</tr>
		</table>
		<table width="635" align="left" bgcolor="#fff">
			<tr>
				<td width="30" height="20" style="line-height:20px;">九、</td>
				<td width="100" height="20" style="line-height:20px;">其他约定：</td>
				<td width="505" height="20" style="line-height:20px;">1、乙方付清全部货款后，甲方开始给乙方百分之十七的增值税发票。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">2、乙方于合同签订当日下午4:30前盖章回传有效，超过上述时间回传的无效。</td>
			</tr>
			<tr>
				<td></td>
				<td></td>
				<td width="505" height="20" style="line-height:20px;">3、本合同自双方盖章后生效，一式两份，双方各执一份。传真件具有同等法律效力。</td>
			</tr>
			%s
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border-bottom:2px solid #000;">
			<tr>
				<td colspan="4" height="2" style="line-height:2px;"></td>
			</tr>
		</table>
		<table width="635" align="center" bgcolor="#fff" style="border:2px solid #000;">
			<tr>
				<td colspan="4" height="20" style="line-height:20px;"></td>
			</tr>
			%s
		</table>',
	),
);