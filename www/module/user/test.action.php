<?php

namespace Home\Controller;

use Think\Controller;
use Common\Controller\CommonController;
use Think\Exception;

class IndexController extends CommonController
{
	public function index()
	{

		// $this->CheckSecondLevelPwd();
		// 绑定钱包类型下拉框
		$ClientID = getLoginClient();


		// 推广链接

		$href = "http://" . $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . U('Login/register', '', '') . "/RecommendID/" . session('LoginClientID');

		$this->assign("myhref", $href);

		$this->display("index");
	}

	/*
	 * 会员签到
	 */
	public function ClientSign()
	{
		$fucka = strtotime(date("Y-m-d 08:30:00"));
		$fuckb = time();

		if ($fuckb < $fucka) {
			$this->ajaxReturn("请您在08:30之后再进行签到！");
		}


		$ClientID = getLoginClient();
		$time = getTime();
		// 先查询此会员今天是否已经签到
		$Client = M('client');
		$SignData = $Client->query("
				SELECT  T.SignSort,
						T.SignDate
				
				FROM CLIENT T
				WHERE T.ClientID = '$ClientID'
				AND DATE_FORMAT(T.SignDate,'%Y-%m-%d') =  DATE_FORMAT('{$time}','%Y-%m-%d') ");
		$SignData = $SignData [0];
		if (!empty ($SignData ['SignSort'])) {
			$this->ajaxReturn("您今天已经签到成功！");
		}

		// 查找今天已经
		$MaxSignSortData = $Client->query("
				SELECT IFNULL(MAX(T.SignSort),0) MaxSignSort,
				T.SignDate
		
				FROM CLIENT T
				WHERE 1 = 1
				AND DATE_FORMAT(T.SignDate,'%Y-%m-%d') =  DATE_FORMAT('{$time}','%Y-%m-%d') ");

		$MaxSignSortData = $MaxSignSortData [0];

		$Client->startTrans();
		$result = true;
		$sort = 0;
		if (empty ($MaxSignSortData ['MaxSignSort']) || $MaxSignSortData ['MaxSignSort'] == 0) {
			$sort = 1;
		} else {
			$sort = $MaxSignSortData ['MaxSignSort'] + 1;
		}

		$updatedata = null;
		$updatedata ['SignSort'] = $sort;
		$updatedata ['SignDate'] = getTime();
		$result = $Client->where("ClientID = '%s'", $ClientID)->save($updatedata);

		if (false !== $result && 0 !== $result) {
			if ($sort <= C('SYSTEM_RULE_CONFIG.HYQD')['MC']) {
				$jltb = C('SYSTEM_RULE_CONFIG.HYQD')['TB'];

				// 插入财务明细表

				$Accountinfo = M('Accountinfo');
				$AccountInfoData = $Accountinfo->where("ClientID = '%s'", array(
					$ClientID
				))->find();

				$AccountInfoUpdateData = null;
				$AccountInfoUpdateData ['TMoneyWallet'] = $AccountInfoData ['TMoneyWallet'] + $jltb;
				$AccountInfoUpdateData ['TotalTMoneyWallet'] = $AccountInfoData ['TotalTMoneyWallet'] + $jltb;
				$AccountInfoUpdateData ['UpdateUser'] = $ClientID;
				$AccountInfoUpdateData ['UpdateTime'] = getTime();

				if (false !== $result && 0 !== $result) {
					$result = $Accountinfo->where("ClientID = '%s'", array(
						$ClientID
					))->save($AccountInfoUpdateData);
				}
				$Accountdetail = M('Accountdetail');
				// 插入财务明细表
				$AccountdetailData ['ClientID'] = $ClientID;
				$AccountdetailData ['BusinessType'] = "";
				$AccountdetailData ['BusinessID'] = "";
				$AccountdetailData ['FinanceType'] = C('FinanceType.QDZSTB');
				$AccountdetailData ['IncomePayFlag'] = 0;
				$AccountdetailData ['PlanMoney'] = $jltb;
				$AccountdetailData ['Money'] = $jltb;
				$AccountdetailData ['WalletType'] = C('WalletType.TB');
				$AccountdetailData ['LastBalance'] = $AccountInfoUpdateData ['TMoneyWallet'];

				$AccountdetailData ['InAccountStatus'] = C('InAccountStatus.YRZ');
				$AccountdetailData ['InAccountTime'] = getTime();
				$AccountdetailData ['DeleteFlag'] = '0';
				$AccountdetailData ['UpdateUser'] = $ClientID;
				$AccountdetailData ['UpdateTime'] = getTime();
				$AccountdetailData ['CreateUser'] = $ClientID;
				$AccountdetailData ['CreateTime'] = getTime();

				if (false !== $result && 0 !== $result) {
					$result = $Accountdetail->lock(true)->add($AccountdetailData);
				}
			} else {
				$Client->rollback();
				$this->ajaxReturn("今天签到已满！");
			}
		}

		if (false !== $result && 0 !== $result) {
			$Client->commit();

			$this->ajaxReturn("签到成功！");
		} else {
			$Client->rollback();
			$this->ajaxReturn($Client->getError());
		}
	}

	/*
	 * 查看新闻详细信息
	 */
	public function NewsInfo($newsNo)
	{
		$message = M('news');
		$message->where(" DeleteFlag = 0 and NewsNo = '%s' ", array(
			$newsNo
		));
		$data = $message->find();

		$this->assign('data', $data);
		$this->display("index:newsinfo");
	}
	/*
	 * 接受援助
	 */
	function PlainReceiveSupportList()
	{
		$ClientID = getLoginClient();

		//判断排单币是否充足
		$AccountInfo = M('Accountinfo');
		$AccountInfoData = $AccountInfo->query(" SELECT T.GiveCoinNum
				FROM AccountInfo T
					
				WHERE T.DeleteFlag = 0
				AND T.ClientID = '{$ClientID}' ");
		$AccountInfoData = $AccountInfoData [0];
		if ($AccountInfoData['GiveCoinNum'] <= 0) {
			$this->error("您的排单币不足", U('Home/Index/index'));
		}
		$where [] = array(
			"T.DeleteFlag" => '0'
		);
		$Product = M('Product');
		$receivelist = $Product->query("SELECT t.ProductCode,
						t.MinPrice,
						t.MaxPrice,
						CEIL(t.MinPrice/100) MinTB,
						CEIL(t.MaxPrice/100) MaxTB,
						t.BespokeSwitch
						
					FROM product t
					WHERE t.DeleteFlag = 0");
		if (count($receivelist) > 0) {
			$relist = $Product->query("SELECT  rs.Money
	
FROM receivesupport rs
LEFT JOIN CLIENT cl
ON rs.ClientID = cl.ClientID
WHERE rs.DeleteFlag = 0
AND cl.DeleteFlag = 0
AND cl.EnableFlag = 0
AND cl.Activation = 0
AND rs.ReceiveSupportStatus = '01' ");
			foreach ($receivelist as &$pvo) {
				$pvo['TXCount'] = 0;
			}
			foreach ($relist as $rvo) {
				foreach ($receivelist as &$pvo) {
					if ($rvo['Money'] >= $pvo['MinPrice'] &&
						$rvo['Money'] <= $pvo['MaxPrice']
					) {
						$pvo['TXCount'] += 1;
						break;
					}
				}
			}
		}
		$this->assign('list', $receivelist);
		$this->display("plainbusiness");
	}

	/*
	 * 购买订单
	 */
	public function BuyOrder()
	{
		if (!IS_POST) {
			return;
		}

		try {
			$ClientID = getLoginClient();
			$ProductCode = I('post.ProductCode');


			// 查看是否有成功预约过此商品类型的订单
			$ReceiveSupport = M('Receivesupport');
			$ProductTypeData = $ReceiveSupport->query("select p.PurchasStartTime,p.PurchasEndTime,p.MinPrice,
						CEIL(p.MinPrice/100) MinTB,
						CEIL(p.MaxPrice/100) MaxTB
					
					from product p
					where p.ProductCode = '{$ProductCode}'");

			$ProductTypeData = $ProductTypeData [0];

			// 判断是否是该商品的抢购时间
			$NowH = date('H', time());


			$stime = $ProductTypeData ['PurchasStartTime'];
			$stime = substr($stime, 11);
			$stime = date("Y-m-d ") . $stime;
			$stime = strtotime($stime);
			$dtime = $ProductTypeData ['PurchasEndTime'];
			$dtime = substr($dtime, 11);
			$dtime = date("Y-m-d ") . $dtime;
			$dtime = strtotime($dtime);

			$ntime = time();


			$aa = substr($ProductTypeData ['PurchasStartTime'], 11);
			$bb = substr($ProductTypeData ['PurchasEndTime'], 11);

			if ($ntime >= $stime && $ntime <= $dtime) {
			} else {

				$this->ajaxReturn("该类商品还没有到达抢购时间，抢购时间：{$aa } —— {$bb}");
			}
			//判断排单币是否充足
			$givesupportdata = $ReceiveSupport->query(" SELECT T.GiveCoinNum
					FROM AccountInfo T
					
					WHERE T.DeleteFlag = 0
					AND T.ClientID = '{$ClientID}' ");

			$givesupportdata = $givesupportdata [0];


			if ($givesupportdata['GiveCoinNum'] < $ProductTypeData['MaxTB']) {
				$this->ajaxReturn("您的排单币不足，此区间要求" . $ProductTypeData['MinTB'] . '-' . $ProductTypeData['MaxTB'] . "个排单币");
			}


			//判断否有未完成的单子
			$pdwwcdata = $ReceiveSupport->query("SELECT T.ID
								FROM givesupport T
								WHERE T.DeleteFlag = 0
								AND T.GiveSupportStatus IN ('02','03')
								AND T.ClientID = '{$ClientID}' ");

			$pdwwcdata = $pdwwcdata [0];

			if (!empty($pdwwcdata['ID'])) {
				$this->ajaxReturn("您当前有未完成的单子，暂时不能抢购");
			}


			// 查询状态为匹配中的且剩余配对余额大于0的数据集
			$ReceiveSupport = M('Receivesupport');


			$ReceiveSupportData = $ReceiveSupport->query(" SELECT T.ClientID,T.Money
													FROM ReceiveSupport T
													LEFT JOIN CLIENT cl
													ON T.ClientID = cl.ClientID
													WHERE 1=1
													AND T.ReceiveSupportStatus = '01'
													AND T.DeleteFlag = '0'
													AND T.ClientID <> '{$ClientID}' 
													AND cl.Activation = 0
													AND cl.DeleteFlag = 0
													AND cl.EnableFlag = 0
													AND T.Money >= (SELECT MinPrice FROM product WHERE productcode = '{$ProductCode}')
													AND T.Money <= (SELECT MaxPrice FROM product WHERE productcode = '{$ProductCode}')
													ORDER BY T.ApplyTime  ");

			$KYQGCount = count($ReceiveSupportData);


			// 统计抢单人数
			$ClientBuyList = M('Clientbuylist');

			$ClientBuyListData = $ClientBuyList->query("select count(1) CCount from clientbuylist
					where DeleteFlag = 0
					and DATE_FORMAT(CreateTime,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')
					and ClientID = '{$ClientID}' ");

			$ClientBuyListData = $ClientBuyListData[0];

			if ($ClientBuyListData['CCount'] <= 0) {
				$ClientBuyListUpdateData = null;
				$ClientBuyListUpdateData['ClientID'] = $ClientID;
				$ClientBuyListUpdateData['HandleResultFlag'] = 0;
				$ClientBuyListUpdateData['DeleteFlag'] = 0;
				$ClientBuyListUpdateData['UpdateUser'] = $ClientID;
				$ClientBuyListUpdateData['UpdateTime'] = getTime();
				$ClientBuyListUpdateData['CreateUser'] = $ClientID;
				$ClientBuyListUpdateData['CreateTime'] = getTime();

				if (false !== $result && 0 !== $result) {
					$result = $ClientBuyList->lock(true)->add($ClientBuyListUpdateData);
				}
			}


			if ($KYQGCount <= 0) {

				$this->ajaxReturn("抢单栏暂无此单，请稍后再试！");
			}

			$this->ajaxReturn('1');
		} catch (Exception $e) {


		}

		/*
		 * 接受援助匹配列表
		 */
		function GetReceiveSupportList()
		{
			$ClientID = getLoginClient();
			$ProductCode = I('get.ProductCode');
			$where [] = array(
				"T.DeleteFlag" => '0'
			);

			$ReceiveSupport = M('Receivesupport');
			$ProductTypeData = $ReceiveSupport->query("select p.MaxPrice,p.MinPrice
					
					from product p
					where p.ProductCode = '{$ProductCode}'");
			$ProductTypeData = $ProductTypeData[0];
			$relist = $ReceiveSupport->query("SELECT  rs.ReceiveSupportNo,rs.Money
	
		FROM receivesupport rs
		LEFT JOIN CLIENT cl
		ON rs.ClientID = cl.ClientID
		WHERE rs.DeleteFlag = 0
		AND cl.DeleteFlag = 0
		AND cl.EnableFlag = 0
		AND cl.Activation = 0
		AND rs.ReceiveSupportStatus = '01' 
		AND rs.Money >= '{$ProductTypeData['MinPrice']}'
		AND rs.Money <= '{$ProductTypeData['MaxPrice']}'
		ORDER BY rs.BuySort,rs.ApplyTime 
		LIMIT 1 ");

			foreach ($relist as &$vo) {
				$vo['ProductCode'] = $ProductCode;
			}
			$this->assign('list', $relist);

			$this->display("seniorbusiness");
		}

		/*
		 * 手动匹配援助订单处理函数
		 */
		function GiveSupportMarryDo()
		{
			if (!IS_POST) {
				$this->ajaxReturn('E参数错误');
			}
			$ReceiveSupportNo = I('post.ReceiveSupportNo');
			$ClientID = getLoginClient();
			$ProductCode = I('post.ProductCode');


			$where [] = array(
				"DeleteFlag" => '0',
				"ReceiveSupportNo" => $ReceiveSupportNo
			);

			$Receivesupport = M('Receivesupport');
			$givesupport = M('Givesupport');

			$ReceivesupportData = $Receivesupport->where($where)->find();

			$CCountFlag = $Receivesupport->query(" SELECT COUNT(1) CCount
				FROM supporplaymoneymap sm
				WHERE sm.DeleteFlag = 0
				AND sm.ReceiveSupportNo = '{$ReceiveSupportNo}' ");

			$CCountFlag = $CCountFlag[0];

			if ($CCountFlag['CCount'] != 0) {
				$this->ajaxReturn('该提现已经被匹配，请尝试刷新后操作！');
			}

			// 判断当前登录人今天累计抢购金额
			$MaxPriceData = $Receivesupport->query("SELECT MAX(T.MaxPrice) MaxPrice
			FROM product T
			WHERE T.DeleteFlag = 0
			AND T.BespokeSwitch = '0' ");
			$MaxPriceData = $MaxPriceData[0];

			$SumMoneyData = $Receivesupport->query("SELECT IFNULL(SUM(T.Money),0) SumMoney
		FROM supporplaymoneymap T
		WHERE T.DeleteFlag = 0
		AND T.PlayMoneyClientID = '{$ClientID}'
		AND DATE_FORMAT(T.CreateTime,'%Y-%m-%d') = DATE_FORMAT(NOW(),'%Y-%m-%d')  ");
			$SumMoneyData = $SumMoneyData[0];


			if (($SumMoneyData['SumMoney'] + $ReceivesupportData['Money']) > $MaxPriceData['MaxPrice']) {
				$this->ajaxReturn("您今天抢购金额已累计{$SumMoneyData['SumMoney']},不能大于当前开放的最大金额");
			}


			$givesupport->startTrans();
			$givesupportData ['GiveSupportNo'] = $this->GetGivesupportSerialNumHouTai();
			$givesupportData ['ClientID'] = $ClientID;
			$givesupportData ['ProductCode'] = $ProductCode;
			$givesupportData ['Num'] = 1;
			$givesupportData ['InPrice'] = $ReceivesupportData['Money'];
			$givesupportData ['TotalAmount'] = $ReceivesupportData['Money'];
			//$givesupportData ['RevalueScale'] = C('SYSTEM_RULE_CONFIG.LX')['PDQ'] / 100;
			//$givesupportData ['RevalueMoney'] =  ($ReceivesupportData ['Money'] * $givesupportData ['RevalueScale']);
			$givesupportData ['GiveSupportStatus'] = C('GiveSupportStatus.DKZ');
			$givesupportData ['PurchaseTime'] = getTime();
			$givesupportData ['DeleteFlag'] = "0";
			$givesupportData ['UpdateUser'] = $ClientID;
			$givesupportData ['UpdateTime'] = getTime();
			$givesupportData ['CreateUser'] = $ClientID;
			$givesupportData ['CreateTime'] = getTime();

			$result = $givesupport->lock(true)->add($givesupportData);

			// 修改接收援助表 状态 匹配完成时间

			$receivesupportUpdateData = null;
			$receivesupportUpdateData ['ReceiveSupportStatus'] = C('ReceiveSupportStatus.DKZ');
			$receivesupportUpdateData ['MarryFinishTime'] = getTime();
			$receivesupportUpdateData ['UpdateUser'] = $ClientID;
			$receivesupportUpdateData ['UpdateTime'] = getTime();

			if (false !== $result && 0 !== $result) {
				$result = $Receivesupport->where("ReceiveSupportNo = '%s'", array(
					$ReceiveSupportNo
				))->save($receivesupportUpdateData);
			}


			// 插入援助打款映射表
			$Supporplaymoneymap = M('Supporplaymoneymap');

			$SupporplaymoneymapData ['GiveSupportNo'] = $givesupportData ['GiveSupportNo'];
			$SupporplaymoneymapData ['ReceiveSupportNo'] = $ReceiveSupportNo;
			$SupporplaymoneymapData ['PlayMoneyClientID'] = $ClientID;
			$SupporplaymoneymapData ['ReceiveMoneyClientID'] = $ReceivesupportData ['ClientID'];
			$SupporplaymoneymapData ['Money'] = $ReceivesupportData ['Money'];
			$SupporplaymoneymapData ['PayStatus'] = C('PayStatus.WDK'); // 未支付
			$SupporplaymoneymapData ['DeleteFlag'] = '0';
			$SupporplaymoneymapData ['UpdateUser'] = $ClientID;
			$SupporplaymoneymapData ['UpdateTime'] = getTime();
			$SupporplaymoneymapData ['CreateUser'] = $ClientID;
			$SupporplaymoneymapData ['CreateTime'] = getTime();


			if (false !== $result && 0 !== $result) {
				$result = $Supporplaymoneymap->lock(true)->add($SupporplaymoneymapData);
			}


			// 抢购成功需要扣除排单币
			// 修改财务信息表
			$Accountinfo = M('Accountinfo');

			$AccountinfoData = $Accountinfo->where("ClientID = '%s'", array(
				$ClientID
			))->find();
			$AccountinfoUpdateData = null;
			$BespeakConsumeTB = ceil($ReceivesupportData ['Money'] / 100);
			if ($AccountinfoData ['GiveCoinNum'] < $BespeakConsumeTB) {
				$givesupport->rollback();
				$this->ajaxReturn('您的排单币不足');
			}
			$AccountinfoUpdateData ['GiveCoinNum'] = $AccountinfoData ['GiveCoinNum'] - $BespeakConsumeTB;
			$AccountinfoUpdateData ['UpdateUser'] = $ClientID;
			$AccountinfoUpdateData ['UpdateTime'] = getTime();

			if (false !== $result && 0 !== $result) {
				$result = $Accountinfo->where("ClientID = '%s'", array(
					$ClientID
				))->save($AccountinfoUpdateData);
			}


			// 插入财务明细表 排单币扣除
			$Accountdetail = M('Accountdetail');
			$AccountdetailData = null;
			$AccountdetailData ['ClientID'] = $ClientID;
			$AccountdetailData ['BusinessType'] = C('BusinessType.TGYZ');
			$AccountdetailData ['BusinessCode'] = $givesupportData ['GiveSupportNo'];
			$AccountdetailData ['FinanceType'] = C('FinanceType.PDBKC');
			$AccountdetailData ['IncomePayFlag'] = 1;
			$AccountdetailData ['PlanMoney'] = $BespeakConsumeTB;
			$AccountdetailData ['WalletType'] = "";
			$AccountdetailData ['DeleteFlag'] = '0';
			$AccountdetailData ['UpdateUser'] = $ClientID;
			$AccountdetailData ['UpdateTime'] = getTime();
			$AccountdetailData ['CreateUser'] = $ClientID;
			$AccountdetailData ['CreateTime'] = getTime();
			$AccountdetailData ['InAccountStatus'] = C('InAccountStatus.YRZ');
			$AccountdetailData ['Money'] = $BespeakConsumeTB; // 实际入账
			$AccountdetailData ['InAccountTime'] = getTime(); // 入账时间
			$AccountdetailData ['LastBalance'] = $AccountinfoUpdateData ['GiveCoinNum']; // 入账后钱包余额
			$AccountdetailData ['Remark'] = "购买订单编号：" . $givesupportData ['GiveSupportNo'];

			if (false !== $result && 0 !== $result) {
				$result = $Accountdetail->lock(true)->add($AccountdetailData);
			}


			if (false !== $result && 0 !== $result) {
				$givesupport->commit();

				if (C('SYSTEM_CONFIG.MarrySuccessMsg')) {

					$Product = M('product');
					$ProductData = $Product->where("ProductCode = '%s'", $ProductCode)->field("ProductName")->find();


					// 向收款人发送接受援助配对成功短信 不考虑短信成功与否
					$sendMsg = C('SendMsgTemplet.MarrySuccessToReceive');
					$sendMsg = str_replace("#money#", $ReceivesupportData ['Money'], $sendMsg);
					SendSMSToMsg($sendMsg, $ReceivesupportData ['ClientID']);

					// 向打款人发送接受援助配对成功短信 不考虑短信成功与否
					$sendMsg = C('SendMsgTemplet.MarrySuccessToPlay');
					$sendMsg = str_replace("#product#", $ProductData['ProductName'], $sendMsg);
					$sendMsg = str_replace("#money#", $ReceivesupportData ['Money'], $sendMsg);
					SendSMSToMsg($sendMsg, $ClientID);
				}

				$this->ajaxReturn('1');
			} else {
				$givesupport->rollback();
				$this->ajaxReturn('E' . $givesupport->getError());
			}
		}
	}

	/*
	 * 获取抢单结果
	 */

	public function GetBuyListResult()
	{
		try {

			set_time_limit(0);

			$ClientID = getLoginClient();
			$ProductCode = I('post.ProductCode');

			$result = $this->GetBuyListResultHoutai($ProductCode, $ClientID);
			$this->ajaxReturn($result);

		} catch (Excetion $e) {
			$this->ajaxReturn("获取抢购结果失败，请到我的物品中查看或以短信为准");
		}

	}

	private  function GetBuyListResultHoutai($ProductCode, $ClientID)
	{
		try {


			$ReceiveSupport = M('Receivesupport');

			$givesupportdata = $ReceiveSupport->query("SELECT T.HandleResultFlag
					FROM ClientBuyList T
					WHERE T.DeleteFlag = 0
					AND T.ProductCode = '{$ProductCode}'
					AND T.ClientID = '{$ClientID}'  ");

			$givesupportdata = $givesupportdata [0];

			if ($givesupportdata ['HandleResultFlag'] == '0') {
				return "恭喜：抢购成功，请及时打款";
			} else {
				return "抱歉：抢购失败，请再接再厉";
			}

		} catch (Excetion $e) {
			return "获取抢购结果失败，请以短信为准";
		}
	}

	/*
	 * 生成新的提供援助订单号
	 */
	function GetGivesupportSerialNumHouTai()
	{
		$givesupport = M('givesupport');
		$SerialNum = 'P';
		$arr = array();
		for ($i = 0; $i < 12; $i++) {
			$arr [] = rand(0, 9);
		}
		$SerialNum .= implode("", $arr);

		$data = $givesupport->query("select GiveSupportNo
				from givesupport
				where GiveSupportNo = '{$SerialNum}'");

		while (isset ($data [0]) && !empty ($data [0])) {
			$SerialNum = 'P';
			$arr = array();
			for ($i = 0; $i < 12; $i++) {
				$arr [] = rand(0, 9);
			}
			$SerialNum .= implode("", $arr);

			$data = $givesupport->query("select GiveSupportNo
							from givesupport
							where GiveSupportNo = '{$SerialNum}')");
		}

		return $SerialNum;
	}


	/*
	 * 上传付款凭证
	 */
	public function UploadsPayVoucher()
	{
		$config = array(
			'maxSize' => 3145728,
			'savePath' => './PayVoucher/',
			'saveName' => time() . '_' . mt_rand(),
			'exts' => array(
				'jpg',
				'gif',
				'png',
				'jpeg'
			),
			'autoSub' => true,
			'subName' => array(
				'date',
				'Ymd'
			)
		);
		$upload = new \Think\Upload ($config); // 实例化上传类
		$info = $upload->uploadOne($_FILES ['PayVoucher']);
		// 如果没有错误信息 即代表上传成功
		if ($upload->getError()) {
			$this->ajaxReturn("0" . $upload->getError());
		} else {
			$SaveName = $info ['savepath'] . $info ['savename'] . $info ['exts'];
			$this->ajaxReturn("1" . $SaveName);
		}
	}

	function GiveSupportList()
	{

		$where = array();
		if (IS_POST) {
			$GiveSupportStatus = I('post.GiveSupportStatus');
			if (isset ($GiveSupportStatus) && !empty ($GiveSupportStatus)) {
				$where ['GiveSupportStatus'] = $GiveSupportStatus;
				$this->assign('GiveSupportStatus', $GiveSupportStatus);
			}
			$StartApplyTime = I('post.StartApplyTime');
			$arr1 = array();
			if (isset ($StartApplyTime) && !empty ($StartApplyTime)) {
				$arr1 = array(
					'EGT',
					date('Y-m-d H:i:s', strtotime($StartApplyTime))
				);
				$this->assign('StartApplyTime', $StartApplyTime);
			}
			$EndApplyTime = I('post.EndApplyTime');
			$arr2 = array();
			if (isset ($EndApplyTime) && !empty ($EndApplyTime)) {
				$arr2 = array(
					'ELT',
					date('Y-m-d H:i:s', strtotime("$EndApplyTime +1 day"))
				);
				$this->assign('EndApplyTime', $EndApplyTime);
			}
			if (!empty ($arr2) && !empty ($arr1)) {
				$where ['PurchaseTime'] = array(
					'between',
					array(
						date('Y-m-d H:i:s', strtotime($StartApplyTime)),
						date('Y-m-d H:i:s', strtotime("$EndApplyTime +1 day"))
					)
				);
			} else if (!empty ($arr2)) {
				$where ['PurchaseTime'] = $arr2;
			} else if (!empty ($arr1)) {
				$where ['PurchaseTime'] = $arr1;
			}
		} else {
		}

		$where [] = array(
			"T.DeleteFlag" => '0',
			"T.ClientID" => getLoginClient()
		);

		$givesupport = M('givesupport');
		$count = $givesupport->alias('T')->where($where)->count();
		$Page = $this->GetPageStyle($count);
		$show = $Page->show(); // 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$givesupport->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows);
		$list = $givesupport->alias('T')
			->join("left join dbcommondata dbg on T.GiveSupportStatus = dbg.Code and dbg.CodeKey = 'GiveSupportStatus'")
			->join("left join
				(select sum(T1.PlanMoney) SumMoney,T1.BusinessCode
				from AccountDetail T1
				where T1.DeleteFlag = '0'
				and T1.BusinessType = '01'
				and T1.FinanceType IN ('01')  #日分红收益
				and T1.IncomePayFlag = '0'
				group by T1.BusinessCode ) TT1
				on TT1.BusinessCode = T.GiveSupportNo
				")
			->join("left join supporplaymoneymap sm
		on T.GiveSupportNo = sm.GiveSupportNo
		and sm.PayStatus = '02' ")->field("T.*,dbg.CodeValue GiveSupportStatusName,
				IFNULL(TT1.SumMoney,'0.00') LineMoney,TIME_FORMAT(TIMEDIFF(NOW(), sm.ConfirmPlayMoneyTime),'%H') JLDKHour,(UNIX_TIMESTAMP(NOW()) - UNIX_TIMESTAMP(sm.ConfirmPlayMoneyTime)) JLDKSecond")
			->select();


		$TXXZHour = C('SYSTEM_RULE_CONFIG.TX')['QRHOUR'];

		foreach ($list as &$vo) {
			//$vo['JLDKSecond']=round($vo['JLDKSecond']/3600,0).' 小时';
			$vo['JLDKSecond'] = Sec2Time($vo['JLDKSecond']);

			// 满足提现条件 01
			if ($vo['JLDKHour'] >= $TXXZHour) {
				$vo['JLDKHour'] = '01';
			} else {
				$vo['JLDKHour'] = '00';
			}
		}


		$this->assign('list', $list); // 赋值数据集
		$this->assign('page', $show); // 赋值分页输出

		$this->display("givesupportlist");
	}


	/*
	 * 查看提供援助详情
	 */
	function GiveSupportInfo($GiveSupportNo)
	{
		if (empty ($GiveSupportNo)) {
			halt('参数错误');
		}

		$Supporplaymoneymap = M('supporplaymoneymap');
		$QuerySql = "SELECT T.GiveSupportNo,
					T.ReceiveSupportNo,
					T.PlayMoneyClientID,
					T.ReceiveMoneyClientID,
					T.Money,
					T.PayStatus,
					T.PlayMoneyTime,
					T.ConfirmPlayMoneyTime,
					T.CreateTime MCreateTime,
					GSSDB.CodeValue GiveSupportStatusName,
					GS.*,
					DKC.FullName DKCFullName,
					SKC.FullName SKCFullName,
					RKC.FullName RKCFullName,
					T.ID MapID,
					P.ProductName,
					(GS.RevalueScale*100) RevalueScaleA,
					P.MinPrice,
					P.MaxPrice
					
				
				
					FROM supporplaymoneymap T
					LEFT JOIN GiveSupport GS
					ON T.GiveSupportNo = GS.GiveSupportNo
					LEFT JOIN dbcommondata GSSDB
					ON GS.GiveSupportStatus = GSSDB.Code
					AND GSSDB.CodeKey = 'GiveSupportStatus'
					LEFT JOIN receivesupport RS
					ON T.ReceiveSupportNo = RS.ReceiveSupportNo
					LEFT JOIN CLIENT DKC
					ON T.PlayMoneyClientID = DKC.ClientID
					LEFT JOIN CLIENT SKC
					ON T.ReceiveMoneyClientID = SKC.ClientID
					LEFT JOIN CLIENT RKC
					ON DKC.RecommendID = RKC.ClientID
					LEFT JOIN Product P
					ON GS.ProductCode = P.ProductCode
					
					WHERE T.DeleteFlag = 0
					AND T.GiveSupportNo = '{$GiveSupportNo}' ";

		$SupporplaymoneymapData = $Supporplaymoneymap->query($QuerySql);
		foreach ($SupporplaymoneymapData as &$value) {
			// 计算打款倒计时
			if ($value ['PayStatus'] === C('PayStatus.WDK')) {
				// 如果是打款期 则计算打款倒计时
				if ($value ['GiveSupportStatus'] === C('GiveSupportStatus.DKZ')) {

					$cztime = time() - strtotime($value ['PurchaseTime']);
					$ydhours = C('SYSTEM_RULE_CONFIG.PPCG')['DK'];
					$ydseconds = $ydhours * 3600;

					$rtime = $ydseconds - $cztime;

					$value ['CountDown'] = $rtime;
				}
			}
			// 计算确认收款倒计时
			// 无论是诚信金还是援助款 倒计时都是以打款时间为计算基准
			if (empty ($value ['ConfirmPlayMoneyTime'])) { // 前提是还未确认收款
				if (!empty ($value ['PlayMoneyTime'])) {
					$cztime = time() - strtotime($value ['PlayMoneyTime']);
					$ydhours = C('SYSTEM_RULE_CONFIG.PPCG')['QR'];
					$ydseconds = $ydhours * 3600;

					$rtime = $ydseconds - $cztime;

					$value ['CountDownQR'] = $rtime;
				}
			}
		}

		if (empty ($SupporplaymoneymapData)) {
			$SupporplaymoneymapData = $Supporplaymoneymap->query("SELECT GS.*,
												DKC.FullName DKCFullName,
												GSSDB.CodeValue GiveSupportStatusName,
												P.ProductName,
												(GS.RevalueScale*100) RevalueScaleA
												
												FROM GiveSupport GS
												LEFT JOIN dbcommondata GSSDB
												ON GS.GiveSupportStatus = GSSDB.Code
												AND GSSDB.CodeKey = 'GiveSupportStatus'
												LEFT JOIN CLIENT DKC
												ON GS.ClientID = DKC.ClientID
												LEFT JOIN Product P
												ON GS.ProductCode = P.ProductCode
												
												WHERE GS.DeleteFlag = 0
												AND GS.GiveSupportNo = '{$GiveSupportNo}' ");
		}

		$this->assign('data', $SupporplaymoneymapData);
		$this->display("Index:givesupportinfo");
	}

	/*
	 * 提供援助打款处理函数
	 */
	public
	function PlayMoneyToGiveSupportDo()
	{
		if (!IS_POST) {
			halt('参数错误');
		}
		$GiveSupportNo = I('post.GiveSupportNo');
		$FileName = I('post.FileName');

		$supporplaymoneymap = M("supporplaymoneymap");

		$PayStatusData = $supporplaymoneymap->query("SELECT  T.ID,
											T.PayStatus,
											T.ReceiveSupportNo,
											T.PlayMoneyClientID,
											T.ReceiveMoneyClientID,
											T.Money
											
										FROM supporplaymoneymap T
										LEFT JOIN receivesupport RS
										ON T.ReceiveSupportNo = RS.ReceiveSupportNo
										WHERE T.DeleteFlag = 0
										AND T.GiveSupportNo = '{$GiveSupportNo}' ");

		$PayStatusData = $PayStatusData [0];

		if (C('PayStatus.YDK') == $PayStatusData ['PayStatus']) {
			$this->ajaxReturn("此订单已经打款");
		}

		$givesupport = M('givesupport');

		$givesupport->startTrans();
		$result = true;
		// 修改提供援助信息表

		$result = $givesupport->where("GiveSupportNo = '%s'", array(
			$GiveSupportNo
		))->lock(true)->setField("GiveSupportStatus", C('GiveSupportStatus.QRSKZ'));

		// 修改接收援助信息表
		$receivesupport = M('receivesupport');
		if (false !== $result && 0 !== $result) {
			$result = $receivesupport->where("ReceiveSupportNo = '%s'", $PayStatusData ['ReceiveSupportNo'])->setField("ReceiveSupportStatus", C('ReceiveSupportStatus.QRSKZ'));
		}
		if ($result <= 0) {
			$result = 1;
		}

		// 修改援助打款映射表

		$supporplaymoneymap = M("supporplaymoneymap");
		$supporplaymoneymapData ['PayStatus'] = C('PayStatus.YDK');
		$supporplaymoneymapData ['PlayMoneyTime'] = date('Y-m-d H:i:s');
		$supporplaymoneymapData ['PayContent'] = I('post.PayContent');
		$supporplaymoneymapData ['PayVoucher'] = $FileName;

		if (false !== $result && 0 !== $result) {
			$result = $supporplaymoneymap->where("ID = '%s' ", $PayStatusData ['ID'])->save($supporplaymoneymapData);
		}

		if (false !== $result && 0 !== $result) {
			$givesupport->commit();

			// 向收款人发送收款确认短信短信 不考虑短信成功与否
			if (C('SYSTEM_CONFIG.ReceiveMoneyMsg')) {
				$sendMsg = C('SendMsgTemplet.ReceiveMoney');

				//

				$sendMsg = str_replace("#money#", $PayStatusData ['Money'], $sendMsg);
				SendSMSToMsg($sendMsg, $PayStatusData ['ReceiveMoneyClientID']);
			}
			$this->ajaxReturn("1");
		} else {
			$givesupport->rollback();
			$this->ajaxReturn("打款失败");
		}
	}


	/*
	 * 申请提现提交处理函数
	 */
	function SQTXDo()
	{
		if (!IS_POST) {
			halt('参数错误');
		}
		$GiveSupportNo = I('post.GiveSupportNo');

		$GiveSupport = M('Givesupport');
		$GiveSupportData = $GiveSupport->where("GiveSupportNo = '%s'", $GiveSupportNo)->find();

		if ($GiveSupportData['GiveSupportStatus'] != C('GiveSupportStatus.SZZ')) {
			$this->ajaxReturn("该订单不满足提现条件，或已经提现，请刷新后尝试");
		}
		// 修改提供援助状态  结束升值期

		$GiveSupport->startTrans();

		$GiveSupportUpdateData = null;
		$GiveSupportUpdateData ['GiveSupportStatus'] = C('GiveSupportStatus.YWC'); // 已完成
		$GiveSupportUpdateData ['PurchaseEndTime'] = getTime();
		$GiveSupportUpdateData ['UpdateUser'] = "system";
		$GiveSupportUpdateData ['UpdateTime'] = getTime();

		$result = $GiveSupport->where("ID = '%s'", array(
			$GiveSupportData ['ID']
		))->save($GiveSupportUpdateData);

		$Accountinfo = M('Accountinfo');
		// 插入财务明细表
		$Accountdetail = M('Accountdetail');

		// 解冻日分红利息
		$LXData = $Accountdetail->query("SELECT T.ID,T.PlanMoney
				FROM accountdetail T
				WHERE T.DeleteFlag = 0
				AND T.BusinessType = '01'
				AND T.FinanceType = '01'
				AND T.InAccountStatus = '01'
				AND T.BusinessCode = '{$GiveSupportNo}' ");

		$lxmoney = 0;
		foreach ($LXData as $lxvo) {
			$lxmoney += $lxvo['PlanMoney'];
		}

		$AccountinfoData = $Accountinfo->where("ClientID = '%s'", array(
			$GiveSupportData ['ClientID']
		))->find();

		// 修改财务信息表
		$AccountinfoUpdateData = null;
		$AccountinfoUpdateData ['StaticsWallet'] = $AccountinfoData ['StaticsWallet'] + $lxmoney;
		$AccountinfoUpdateData ['TotalStaticsWallet'] = $AccountinfoData ['TotalStaticsWallet'] + $lxmoney;
		$AccountinfoUpdateData ['UpdateUser'] = $GiveSupportData['ClientID'];
		$AccountinfoUpdateData ['UpdateTime'] = getTime();


		if (false !== $result && 0 !== $result) {
			$result = $Accountinfo->where("ClientID = '%s'", array(
				$GiveSupportData['ClientID']
			))->save($AccountinfoUpdateData);
		}


		// 修改财务明细表
		$TempMoney = $AccountinfoData ['StaticsWallet'];
		foreach ($LXData as $lxvo) {
			$TempMoney = $TempMoney + $lxvo['PlanMoney'];

			$AccountdetailData = null;
			$AccountdetailData ['UpdateUser'] = $GiveSupportData['ClientID'];
			$AccountdetailData ['UpdateTime'] = getTime();
			$AccountdetailData ['InAccountStatus'] = C('InAccountStatus.YRZ');
			$AccountdetailData ['Money'] = $lxvo['PlanMoney']; // 实际入账
			$AccountdetailData ['InAccountTime'] = getTime(); // 入账时间
			$AccountdetailData ['LastBalance'] = $TempMoney; // 入账后钱包余额

			if (false !== $result && 0 !== $result) {
				$result = $Accountdetail->where("ID = '%s'", $lxvo['ID'])->save($AccountdetailData);
			}
		}

		// 回归本金
		// 修改财务信息表
		$AccountinfoData = $Accountinfo->where("ClientID = '%s'", array(
			$GiveSupportData ['ClientID']
		))->find();

		// 修改财务信息表
		$AccountinfoUpdateData = null;

		$AccountinfoUpdateData ['StaticsWallet'] = $AccountinfoData ['StaticsWallet'] + $GiveSupportData ['TotalAmount'];
		$AccountinfoUpdateData ['TotalStaticsWallet'] = $AccountinfoData ['TotalStaticsWallet'] + $GiveSupportData ['TotalAmount'];
		$AccountinfoUpdateData ['UpdateUser'] = "system";
		$AccountinfoUpdateData ['UpdateTime'] = getTime();

		if (false !== $result && 0 !== $result) {
			$result = $Accountinfo->where("ClientID = '%s'", array(
				$GiveSupportData ['ClientID']
			))->save($AccountinfoUpdateData);
		}


		$AccountdetailData = null;
		$AccountdetailData ['ClientID'] = $GiveSupportData ['ClientID'];
		$AccountdetailData ['BusinessType'] = C('BusinessType.TGYZ');
		$AccountdetailData ['BusinessCode'] = $GiveSupportData['GiveSupportNo'];
		$AccountdetailData ['FinanceType'] = C('FinanceType.BJSY');
		$AccountdetailData ['IncomePayFlag'] = 0;
		$AccountdetailData ['PlanMoney'] = $GiveSupportData ['TotalAmount'];
		$AccountdetailData ['WalletType'] = C('WalletType.JTQB');
		$AccountdetailData ['DeleteFlag'] = '0';
		$AccountdetailData ['UpdateUser'] = $GiveSupportData['ClientID'];
		$AccountdetailData ['UpdateTime'] = getTime();
		$AccountdetailData ['CreateUser'] = $GiveSupportData['ClientID'];
		$AccountdetailData ['CreateTime'] = getTime();
		$AccountdetailData ['InAccountStatus'] = C('InAccountStatus.YRZ');
		$AccountdetailData ['Money'] = $GiveSupportData ['TotalAmount']; // 实际入账
		$AccountdetailData ['InAccountTime'] = getTime(); // 入账时间
		$AccountdetailData ['LastBalance'] = $AccountinfoUpdateData ['StaticsWallet']; // 入账后钱包余额

		if (false !== $result && 0 !== $result) {
			$result = $Accountdetail->lock(true)->add($AccountdetailData);
		}


		if (false !== $result && 0 !== $result) {
			$GiveSupport->commit();
			$this->ajaxReturn("1");
		} else {
			$GiveSupport->rollback();
			$this->ajaxReturn($GiveSupport->getError());
		}
	}

	/*
	 * 申请接受援助提交处理函数
	 */
	function ReceiveSupportDo()
	{
		if (!IS_POST) {
			halt('参数错误');
		}


		$ClientID = getLoginClient();
		$Money = I('post.Money');
		$WalletType = I('post.WalletType');
		$UpdateField = "";


		$Receivesupport = M('Receivesupport');

		/*
		// 判断今天是否有过提现记录 只能提一次
		$TXCSData = $Receivesupport->query("SELECT COUNT(1) CCount
				FROM receivesupport T
				WHERE T.DeleteFlag = 0
				AND T.ClientID = '{$ClientID}'
				AND DATE_FORMAT(T.CreateTime,'%Y-%m-%d') = DATE_FORMAT(SYSDATE(),'%Y-%m-%d')");

		$TXCSData=$TXCSData[0];
		if($TXCSData['CCount']>0){
			$this->ajaxReturn ( "您今天已经提现过一次，不能再次提现" );
		}
		*/

		$MoneyArr = explode(";", $Money);


		foreach ($MoneyArr as $vo) {

			//获取当前用户账户信息

			$AccountInfo = M('Accountinfo');
			$AccountInfoData = $AccountInfo->where("ClientID = '%s'", $ClientID)->find();


			//静态
			if ($WalletType == "01") {
				$zsb = C('SYSTEM_RULE_CONFIG.TX')['JTZSB'];
				if ($vo % $zsb != 0) {
					//$this->ajaxReturn ( "提现金额必须以{$zsb}的整数倍递增" );
				}
				$UpdateField = "StaticsWallet";
				if ($vo > $AccountInfoData['StaticsWallet']) {
					$this->ajaxReturn("静态余额不足 请刷新页面后再次尝试");
				}
			} //动态
			elseif ($WalletType == "02") {

				$zsb = C('SYSTEM_RULE_CONFIG.TX')['DTZSB'];
				if ($vo % $zsb != 0) {
					//$this->ajaxReturn ( "提现金额必须以{$zsb}的整数倍递增" );
				}
				if ($vo > $AccountInfoData['BonusWallet']) {
					$this->ajaxReturn("动态余额不足 请刷新页面后再次尝试");
				}
				$UpdateField = "BonusWallet";
			}

			// 提现金额不能大于当前开放的最大金额
			$MaxPriceData = $AccountInfo->query("SELECT MAX(T.MaxPrice) MaxPrice
			FROM product T
			WHERE T.DeleteFlag = 0
			AND T.BespokeSwitch = '0' ");
			$MaxPriceData = $MaxPriceData[0];

			if ($vo > $MaxPriceData['MaxPrice']) {
				$this->ajaxReturn("提现金额不能大于当前开放的最大金额");
			}

			$ReceivesupportData = null;
			$ReceivesupportData['ReceiveSupportNo'] = $this->GetReceiveSupportSerialNumHoutai();
			$ReceivesupportData['ClientID'] = $ClientID;
			$ReceivesupportData['Money'] = $vo;
			$ReceivesupportData['WalletType'] = $WalletType;
			$ReceivesupportData['ReceiveSupportStatus'] = C('ReceiveSupportStatus.PPZ');
			$ReceivesupportData['ApplyTime'] = getTime();
			$ReceivesupportData['DeleteFlag'] = 0;
			$ReceivesupportData['UpdateUser'] = $ClientID;
			$ReceivesupportData['UpdateTime'] = getTime();
			$ReceivesupportData['CreateUser'] = $ClientID;
			$ReceivesupportData['CreateTime'] = getTime();


			$AccountInfo->startTrans();

			// 插入接受援助信息表
			$result = $Receivesupport->lock(true)->add($ReceivesupportData);
			// 插入新的账户明细表
			if (false !== $result && 0 !== $result) {
				$Accountdetail = D('Accountdetail');

				$AccountdetailData ['ClientID'] = $ClientID;
				$AccountdetailData ['BusinessType'] = C('BusinessType.JSYZ');
				$AccountdetailData ['BusinessCode'] = $ReceivesupportData['ReceiveSupportNo'];
				$AccountdetailData ['FinanceType'] = C('FinanceType.TXKC');
				$AccountdetailData ['IncomePayFlag'] = 1;      //标志为支出
				$AccountdetailData ['Money'] = $vo;
				$AccountdetailData['PlanMoney'] = $vo;
				$AccountdetailData['InAccountStatus'] = C('InAccountStatus.YRZ');  //支出默认为已入账
				$AccountdetailData['InAccountTime'] = getTime();
				$AccountdetailData ['WalletType'] = $WalletType;
				$AccountdetailData ['LastBalance'] = ($AccountInfoData [$UpdateField] - $vo);

				if (!$AccountdetailData = $Accountdetail->create($AccountdetailData)) {
					$this->error($Accountdetail->getError());
				}

				$result = $Accountdetail->lock(true)->add($AccountdetailData);
			}

			if (false !== $result && 0 !== $result) {
				$result = $AccountInfo->where("ClientID = '%s'", $ClientID)->setDec($UpdateField, $vo);
			}
			if (false !== $result && 0 !== $result) {
				//更新账户信息表中的累计接受金额
				$result = $AccountInfo->where("ClientID = '%s'", $ClientID)
					->setInc("TotalReceiveMoney", $vo);
			}

			if (false !== $result && 0 !== $result) {
				$AccountInfo->commit();
			} else {
				$AccountInfo->rollback();
				$this->ajaxReturn($AccountInfo->getError());
			}
		}

		$this->ajaxReturn("1");
	}

	/*
	 * 接受援助
	 */
	function ReceiveSupportList()
	{

		// 绑定钱包类型下拉框
		$ReceiveTypeList = array(
			array(
				"Code" => "01",
				"CodeValue" => "静态钱包"
			),
			array(
				"Code" => "02",
				"CodeValue" => "动态钱包"
			)
		);

		$this->assign('WalletTypeList', $ReceiveTypeList);

		// 获取账户信息
		$Accountinfo = M('Accountinfo');
		$AccountinfoData = $Accountinfo->where("ClientID = '%s'", session('LoginClientID'))->find();
		$this->assign("data", $AccountinfoData);
		$this->assign("JingtaiYue", sprintf("%.2f", ($AccountinfoData ['StaticsWallet'])));


		$where = array();
		if (IS_POST) {
			$ReceiveSupportStatus = I('post.ReceiveSupportStatus');
			if (isset ($ReceiveSupportStatus) && !empty ($ReceiveSupportStatus)) {
				$where ['ReceiveSupportStatus'] = $ReceiveSupportStatus;
				$this->assign('ReceiveSupportStatus', $ReceiveSupportStatus);
			}
			$StartApplyTime = I('post.StartApplyTime');
			$arr1 = array();
			if (isset ($StartApplyTime) && !empty ($StartApplyTime)) {
				$arr1 = array(
					'EGT',
					date('Y-m-d H:i:s', strtotime($StartApplyTime))
				);
				$this->assign('StartApplyTime', $StartApplyTime);
			}
			$EndApplyTime = I('post.EndApplyTime');
			$arr2 = array();
			if (isset ($EndApplyTime) && !empty ($EndApplyTime)) {
				$arr2 = array(
					'ELT',
					date('Y-m-d H:i:s', strtotime("$EndApplyTime +1 day"))
				);
				$this->assign('EndApplyTime', $EndApplyTime);
			}
			if (!empty ($arr2) && !empty ($arr1)) {
				$where ['ApplyTime'] = array(
					'between',
					array(
						date('Y-m-d H:i:s', strtotime($StartApplyTime)),
						date('Y-m-d H:i:s', strtotime("$EndApplyTime +1 day"))
					)
				);
			} else if (!empty ($arr2)) {
				$where ['ApplyTime'] = $arr2;
			} else if (!empty ($arr1)) {
				$where ['ApplyTime'] = $arr1;
			}
		} else {
		}

		$where ['DeleteFlag'] = '0';
		$where ['ClientID'] = getLoginClient();

		$Receivesupport = M('Receivesupport');
		$count = $Receivesupport->where($where)->count();
		$Page = $this->GetPageStyle($count);
		$show = $Page->show(); // 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$Receivesupport->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows);
		$list = $Receivesupport->alias('T')->join("left join dbcommondata dbr on T.ReceiveSupportStatus = dbr.Code and dbr.CodeKey = 'ReceiveSupportStatus'")->

		field("T.id,T.ReceiveSupportNo,T.Money,T.ReceiveSupportStatus,T.ApplyTime,
				dbr.CodeValue ReceiveSupportStatusName ")->select();
		$this->assign('list', $list); // 赋值数据集
		$this->assign('page', $show); // 赋值分页输出

		$this->display("receivesupportlist");
	}

	// 查看接受援助详情
	public
	function ReceiveSupportInfo($ReceiveSupportNo)
	{
		if (empty ($ReceiveSupportNo)) {
			halt('参数错误');
		}
		$SupporplaymoneymapData = $this->GetReceiveInfoHoutai($ReceiveSupportNo);
		$this->assign("data", $SupporplaymoneymapData);

		$this->display("receivesupportinfo");
	}

	function GetReceiveInfoHoutai($ReceiveSupportNo)
	{
		$QuerySql = "SELECT RSSDC.CodeValue ReceiveSupportStatusName,
			DCL.FullName PlayMoneyClientFullName,
			SCL.FullName ReceiveMoneyClientFullName,
			RS.*,
			GS.*,
			T.ID MapID,
			T.GiveSupportNo,
			T.ReceiveSupportNo,
			T.PlayMoneyClientID,
			T.ReceiveMoneyClientID,
			T.Money,
			T.PayStatus,
			T.PlayMoneyTime,
			T.ConfirmPlayMoneyTime,
			T.CreateTime MCreateTime
		
		
			FROM SupporPlayMoneyMap T
		
			LEFT JOIN GiveSupport GS
			ON T.GiveSupportNo = GS.GiveSupportNo
		
			LEFT JOIN receivesupport RS
			ON T.ReceiveSupportNo = RS.ReceiveSupportNo
				
			LEFT JOIN CLIENT DCL
			ON T.PlayMoneyClientID = DCL.ClientID
				
			LEFT JOIN CLIENT SCL
			ON T.ReceiveMoneyClientID = SCL.ClientID
				
			LEFT JOIN dbcommondata RSSDC
			ON RS.ReceiveSupportStatus = RSSDC.Code
			AND RSSDC.CodeKey = 'ReceiveSupportStatus'
		
		
			WHERE GS.GiveSupportStatus <> '06'
			AND T.ReceiveSupportNo = '{$ReceiveSupportNo}'";

		$Supporplaymoneymap = M('Supporplaymoneymap');
		// 存在一对多的情况
		$SupporplaymoneymapData = $Supporplaymoneymap->query($QuerySql);
		foreach ($SupporplaymoneymapData as &$value) {
			// 计算打款倒计时
			if ($value ['PayStatus'] === C('PayStatus.WDK')) {

				// 如果是
				if ($value ['GiveSupportStatus'] === C('GiveSupportStatus.DKQ')) {

					$cztime = time() - strtotime($value ['MCreateTime']);
					$ydhours = C('SYSTEM_RULE_CONFIG.PPCG')['DK'];
					$ydseconds = $ydhours * 3600;

					$rtime = $ydseconds - $cztime;

					$value ['CountDown'] = $rtime;
				}
			}
			// 计算确认收款倒计时
			if (empty ($value ['ConfirmPlayMoneyTime'])) { // 前提是还未确认收款
				if (!empty ($value ['PlayMoneyTime'])) {
					$cztime = time() - strtotime($value ['PlayMoneyTime']);
					$ydhours = C('SYSTEM_RULE_CONFIG.PPCG')['QR'];
					$ydseconds = $ydhours * 3600;

					$rtime = $ydseconds - $cztime;

					$value ['CountDownQR'] = $rtime;
				}
			}
		}

		if (empty ($SupporplaymoneymapData)) {
			$SupporplaymoneymapData = $Supporplaymoneymap->query("SELECT
							T.*,
					SCL.FullName ReceiveMoneyClientFullName,
							SCL.ClientID ReceiveMoneyClientID,
							RSSDC.CodeValue ReceiveSupportStatusName
		
		
							FROM receivesupport T
		
									LEFT JOIN CLIENT SCL
									ON T.ClientID = SCL.ClientID
		
									LEFT JOIN dbcommondata RSSDC
									ON T.ReceiveSupportStatus = RSSDC.Code
									AND RSSDC.CodeKey = 'ReceiveSupportStatus'
		
										
									WHERE T.DeleteFlag = 0
									AND T.ReceiveSupportNo = '{$ReceiveSupportNo}'
		
									");
		}

		return $SupporplaymoneymapData;
	}

	/*
	 * 确认收款处理函数
	 */
	public
	function ReceivePlayMoneyDo()
	{
		if (!IS_POST) {
			halt('参数错误');
		}
		$ReceiveSupportNo = I('post.ReceiveSupportNo');
		$MapID = I('post.MapID');

		$ClientID = getLoginClient();
		$supporplaymoneymap = M("supporplaymoneymap");

		$PayStatusData = $supporplaymoneymap->where(" ID = '%s'  AND DeleteFlag = 0 ", $MapID)->field("ID,PayStatus,GiveSupportNo,Money,ReceiveMoneyClientID,PlayMoneyClientID")->find();

		if (!empty ($PayStatusData ['ConfirmPlayMoneyTime'])) {
			$this->ajaxReturn("此订单已经确认收款");
		}

		$GiveSupport = M('Givesupport');
		$GiveSupportData = $GiveSupport->where("GiveSupportNo = '%s' ", $PayStatusData ['GiveSupportNo'])->find();

		$ReceiveSupport = M('Receivesupport');
		$ReceiveSupport->startTrans();

		$UpdateWhere ['GiveSupportStatus'] = C("GiveSupportStatus.SZZ");
		$UpdateWhere ["PurchaseStartTime"] = getTime();
		$UpdateWhere ["UpdateUser"] = $ClientID;
		$UpdateWhere ['UpdateTime'] = getTime();
		$result = $GiveSupport->where("GiveSupportNo = '%s'", $PayStatusData ['GiveSupportNo'])->lock(true)->save($UpdateWhere);


		// 结算排队期收益 正常通道

		// 修改接收援助表状态
		if (false !== $result && 0 !== $result) {
			$UpdateReceiveSupportData = null;
			$UpdateReceiveSupportData ['ReceiveSupportStatus'] = C("ReceiveSupportStatus.YQRSK");
			$UpdateReceiveSupportData ["UpdateUser"] = $ClientID;
			$UpdateReceiveSupportData ['UpdateTime'] = getTime();

			$result = $ReceiveSupport->where("ReceiveSupportNo = '%s'", $ReceiveSupportNo)->lock(true)->save($UpdateReceiveSupportData);
		}

		// 修改打款映射表
		if (false !== $result && 0 !== $result) {
			$Supporplaymoneymap = M('Supporplaymoneymap');
			$UpdateArr = null; // 用之前先清空
			$UpdateArr ['ConfirmPlayMoneyTime'] = getTime();
			$UpdateArr ['PayStatus'] = C('PayStatus.YDK');
			$UpdateArr ["UpdateUser"] = $ClientID;
			$UpdateArr ['UpdateTime'] = getTime();

			$result = $Supporplaymoneymap->where("ID = '%s'", $PayStatusData ['ID'])->lock(true)->save($UpdateArr);
		}

		/*
		// 返还排单币  换算成钱返还到静态钱包
		$Product=M('Product');
		$ProductData = $Product->where("ProductCode = '%s' ",$GiveSupportData['ProductCode'])->field("BespeakConsumeTB")->find();

		$fhmoney=$ProductData['BespeakConsumeTB']*10;

		// 修改财务信息表
		$Accountinfo = M ( 'Accountinfo' );

		$AccountinfoData = $Accountinfo->where ( "ClientID = '%s'", array (
				$GiveSupportData['ClientID']
		) )->find ();
		$AccountinfoUpdateData=null;
		$AccountinfoUpdateData ['StaticsWallet'] = $AccountinfoData ['StaticsWallet'] +$fhmoney;
		$AccountinfoUpdateData ['UpdateUser'] = $ClientID;
		$AccountinfoUpdateData ['UpdateTime'] = getTime ();

		if (false !== $result && 0 !== $result) {
			$result = $Accountinfo->where ( "ClientID = '%s'", array (
					$GiveSupportData['ClientID']
			) )->save ( $AccountinfoUpdateData );
		}


		// 插入财务明细表  抽奖扣除
		$Accountdetail = M ( 'Accountdetail' );
		$AccountdetailData = null;
		$AccountdetailData ['ClientID'] = $GiveSupportData['ClientID'];
		$AccountdetailData ['BusinessType'] = C('BusinessType.TGYZ');
		$AccountdetailData ['BusinessCode'] = $GiveSupportData ['GiveSupportNo'];
		$AccountdetailData ['FinanceType'] = C ( 'FinanceType.PDBFH' );
		$AccountdetailData ['IncomePayFlag'] = 0;
		$AccountdetailData ['PlanMoney'] = $fhmoney;
		$AccountdetailData ['WalletType'] = C('WalletType.JTQB');
		$AccountdetailData ['DeleteFlag'] = '0';
		$AccountdetailData ['UpdateUser'] = $ClientID;
		$AccountdetailData ['UpdateTime'] = getTime ();
		$AccountdetailData ['CreateUser'] =$ClientID;
		$AccountdetailData ['CreateTime'] = getTime ();
		$AccountdetailData ['InAccountStatus'] = C ( 'InAccountStatus.YRZ' );
		$AccountdetailData ['Money'] = $fhmoney; // 实际入账
		$AccountdetailData ['InAccountTime'] = getTime (); // 入账时间
		$AccountdetailData ['LastBalance'] =$AccountinfoUpdateData ['StaticsWallet']; // 入账后钱包余额
		$AccountdetailData ['Remark'] = "购买订单编号：".$GiveSupportData ['GiveSupportNo'];

		if (false !== $result && 0 !== $result) {
			$result = $Accountdetail->lock ( true )->add ( $AccountdetailData );
		}
		*/


		if (false !== $result && 0 !== $result) {
			$ReceiveSupport->commit();

			$this->ajaxReturn("1");
		} else {
			$ReceiveSupport->rollback();
			$this->ajaxReturn("确认失败");
		}
	}

	function GetReceiveSupportSerialNumHoutai()
	{
		$qz = 'G';

		$givesupport = M('Receivesupport');
		$SerialNum = $qz;
		$arr = array();
		for ($i = 0; $i < 12; $i++) {
			$arr [] = rand(0, 9);
		}
		$SerialNum .= implode("", $arr);

		$data = $givesupport->query("select ReceiveSupportNo
				from Receivesupport
				where ReceiveSupportNo = '{$SerialNum}'");

		while (isset ($data [0]) && !empty ($data [0])) {
			$SerialNum = $qz;
			$arr = array();
			for ($i = 0; $i < 12; $i++) {
				$arr [] = rand(0, 9);
			}
			$SerialNum .= implode("", $arr);

			$data = $givesupport->query("select ReceiveSupportNo
						from Receivesupport
						where ReceiveSupportNo = '{$SerialNum}'");
		}

		return $SerialNum;
	}

	private
	function GetChildClients($RecommendID, $arr, &$resultArr)
	{
		if (empty ($RecommendID) || !isset ($arr)) {
			return;
		}

		foreach ($arr as $vo) {
			if ($vo ['RecommendID'] === $RecommendID) {
				if (array_key_exists($vo ['RecommendID'], $resultArr)) {

					$temp = $resultArr [$vo ['RecommendID']] ['count'];
					$resultArr [$vo ['RecommendID']] ['count'] = $temp + 1;
				}
				$resultArr [$vo ['ClientID']] = array(
					"id" => $vo ['ClientID'],
					"parent" => $vo ['RecommendID'],
					"fullname" => $vo ['FullName'],
					"count" => 0,
					"parentcount" => 0,
					"createdata" => date('Y年m月d日', strtotime($vo ['CreateTime'])),
					"activation" => $vo ['Activation']
				);
				// 递归调用 获取所有子节点
				$this->GetChildClients($vo ['ClientID'], $arr, $resultArr);
			}
		}
	}

	private
	function GetChildCount($parent, &$arr, &$count)
	{
		if (empty ($parent) || !isset ($arr)) {
			return;
		}
		$ds++;
		foreach ($arr as $vo) {
			if ($vo ['parent'] === $parent) {

				$count = $count + 1;
				// 递归调用 累加直推总人数
				$this->GetChildCount($vo ['id'], $arr, $count);
			}
		}
		$arr [$parent] ['count'] = $count;
	}

	private
	function GetParentCount($id, $parent, &$arr, &$count)
	{
		$count++;
		if ($parent != '#') {
			$this->GetParentCount($id, $arr [$parent] ['parent'], $arr);
		}
		$arr [$id] ['parentcount'] += $count;
	}


	/******Begin: 抽奖开始处理函数************/
	public
	function Zhuanpan()
	{
		$msg = "";
		$PrizeTypeList = $this->GetDbCommonDataByKey('PrizeType');
		$arr = array();

		foreach ($PrizeTypeList as $vo) {
			$arr[$vo['Code']] = $vo['CodeValue'];
		}

		$msg .= "一等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['ONEV'] . '；';
		$msg .= "二等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['TWOV'] . '；';
		$msg .= "三等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['THREEV'] . '；';
		$msg .= "四等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['FOURV'] . '；';
		$msg .= "五等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['FIVEV'] . '；';
		$msg .= "六等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['SIXV'] . '；';
		$msg .= "七等奖：" . C('SYSTEM_RULE_CONFIG.CJ')['SEVENV'] . '；';

		$this->assign("description", $msg);


		$where [] = array(
			"T.ClientID" => getLoginClient(),
			"T.DeleteFlag" => '0'
		);

		$Lotteryrecord = M('Lotteryrecord');

		$count = $Lotteryrecord->alias('T')->where($where)->count();
		$Page = $this->GetPageStyle($count);
		$show = $Page->show(); // 分页显示输出// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		$Lotteryrecord->alias('T')->where($where)->order('id DESC')->limit($Page->firstRow . ',' . $Page->listRows);
		$list = $Lotteryrecord->join("left join Client CL on T.ClientID = CL.ClientID ")
			->field('T.*,CL.FullName ClientName')
			->select();

		$this->assign('page', $show);
		$this->assign("list", $list);


		$this->display("zhuanpan");
	}


	public
	function ChouJiang()
	{
		$ClientID = getLoginClient();

		$prize_arr = array(
			'0' => array('id' => 1, 'min' => 1, 'max' => 29, 'prize' => '一等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['ONEG']),
			'1' => array('id' => 2, 'min' => 302, 'max' => 328, 'prize' => '二等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['TWOG']),
			'2' => array('id' => 3, 'min' => 242, 'max' => 268, 'prize' => '三等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['THREEG']),
			'3' => array('id' => 4, 'min' => 182, 'max' => 208, 'prize' => '四等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['FOURG']),
			'4' => array('id' => 5, 'min' => 122, 'max' => 148, 'prize' => '五等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['FIVEG']),
			'5' => array('id' => 6, 'min' => 62, 'max' => 88, 'prize' => '六等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['SIXG']),
			'6' => array('id' => 7, 'min' => array(32, 92, 152, 212, 272, 332),
				'max' => array(58, 118, 178, 238, 298, 358), 'prize' => '七等奖', 'v' => C('SYSTEM_RULE_CONFIG.CJ')['SEVENG'])
		);

		foreach ($prize_arr as $key => $val) {
			$arr[$val['id']] = $val['v'];
		}

		$rid = $this->getRand($arr); //根据概率获取奖项id


		try {

			/*
			$fp = fopen(getcwd () . "/Web/ChouJiang/lock.txt", "w+");

			if(flock($fp,LOCK_EX | LOCK_NB)){

			}else{
				// 不处理
			}
			*/

			$res = $prize_arr[$rid - 1]; //中奖项
			$min = $res['min'];
			$max = $res['max'];
			if ($res['id'] == 7) { //七等奖
				$i = mt_rand(0, 5);
				$result['angle'] = mt_rand($min[$i], $max[$i]);
			} else {
				$result['angle'] = mt_rand($min, $max); //随机生成一个角度
			}
			$result['prize'] = $res['prize'];

			//调用结果处理函数
			$this->ChoujiangDo($res['id']);

			// 解锁操作
			//flock($fp,LOCK_UN);
			//fclose($fp);

			$this->ajaxReturn($result);
		} catch (Exception $ex) {

		} finally {

		}
	}

	function getRand($proArr)
	{
		$result = '';

		//概率数组的总概率精度
		$proSum = array_sum($proArr);

		//概率数组循环
		foreach ($proArr as $key => $proCur) {
			$randNum = mt_rand(1, $proSum);
			if ($randNum <= $proCur) {
				$result = $key;
				break;
			} else {
				$proSum -= $proCur;
			}
		}
		unset ($proArr);

		return $result;
	}

	function ChoujiangDo($id)
	{
		if (empty($id))
			return;

		// 判断抽奖时间
		$cjstart = strtotime(date("Y-m-d 09:50:00"));
		$cjend = strtotime(date("Y-m-d 10:20:00"));
		$cjnow = time();

		if ($cjnow > $cjend || $cjnow < $cjstart) {
			//$this->ajaxReturn(array("success"=>"0","msg"=>'抱歉，抽奖时间未到！'));
		}
		$ClientID = getLoginClient();

		// 判断累计打款
		$supporplaymoneymap = M('supporplaymoneymap');
		$SumMoney = $supporplaymoneymap->query("
				select sum(Money) SumMoney
				from supporplaymoneymap
				where deleteflag = 0
				and PlayMoneyClientID = '$ClientID'
				and PayStatus = '02'
				");

		$SumMoney = $SumMoney[0];

		if ($SumMoney['SumMoney'] < 30000) {
			$this->ajaxReturn(array("success" => "0", "msg" => '抱歉，您的累计打款金额未达标！'));
		}


		$number = 0;
		$type = "";
		switch ($id) {
			case "1":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['ONEV'];
				break;
			case "2":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['TWOV'];
				break;
			case "3":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['THREEV'];
				break;
			case "4":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['FOURV'];
				break;
			case "5":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['FIVEV'];
				break;
			case "6":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['SIXV'];
				break;
			case "7":
				$number = C('SYSTEM_RULE_CONFIG.CJ')['SEVENV'];
				break;
		}
		$UpdateField = "";
		$WalletType = "";
		$ProductCode = "";
		$ClientID = getLoginClient();


		$Lotteryrecord = M('Lotteryrecord');


		$Lotteryrecord->startTrans();
		$result = true;

		// 插入抽奖表
		$LotteryrecordData = null;
		$LotteryrecordData ['ClientID'] = $ClientID;
		$LotteryrecordData ['PrizeType'] = $type;
		$LotteryrecordData ['PrizeLevel'] = $id;
		$LotteryrecordData ['Remark'] = $number;
		$LotteryrecordData ['InAccountStatus'] = 0;
		$LotteryrecordData ['DeleteFlag'] = '0';
		$LotteryrecordData ['UpdateUser'] = $ClientID;
		$LotteryrecordData ['UpdateTime'] = getTime();
		$LotteryrecordData ['CreateUser'] = $ClientID;
		$LotteryrecordData ['CreateTime'] = getTime();

		if (false !== $result && 0 !== $result) {
			$result = $Lotteryrecord->lock(true)->add($LotteryrecordData);
		}

		// 修改财务信息表
		$Accountinfo = M('Accountinfo');

		$AccountinfoData = $Accountinfo->where("ClientID = '%s'", array(
			$ClientID
		))->find();

		$AccountinfoUpdateData = null;

		// 判断钱包余额

		$QBType = I('post.type');
		if ($QBType == '01') {
			$QBType = 'StaticsWallet';
		} elseif ($QBType == '02') {
			$QBType = 'BonusWallet';
		}
		$CJKCJE = 20;

		if ($AccountinfoData [$QBType] < $CJKCJE) {
			$Lotteryrecord->rollback();
			$this->ajaxReturn(array("success" => "0", "msg" => '您的对应钱包余额不足'));
		}
		$AccountinfoUpdateData [$QBType] = $AccountinfoData [$QBType] - $CJKCJE;
		$AccountinfoUpdateData ['UpdateUser'] = $ClientID;
		$AccountinfoUpdateData ['UpdateTime'] = getTime();

		if (false !== $result && 0 !== $result) {
			$result = $Accountinfo->where("ClientID = '%s'", array(
				$ClientID
			))->save($AccountinfoUpdateData);
		}


		// 插入财务明细表  抽奖扣除
		$Accountdetail = M('Accountdetail');
		$AccountdetailData = null;
		$AccountdetailData ['ClientID'] = $ClientID;
		$AccountdetailData ['BusinessType'] = null;
		$AccountdetailData ['BusinessCode'] = null;
		$AccountdetailData ['FinanceType'] = C('FinanceType.CJKC');
		$AccountdetailData ['IncomePayFlag'] = 1;
		$AccountdetailData ['PlanMoney'] = $CJKCJE;
		$AccountdetailData ['WalletType'] = I('post.type');
		$AccountdetailData ['DeleteFlag'] = '0';
		$AccountdetailData ['UpdateUser'] = $ClientID;
		$AccountdetailData ['UpdateTime'] = getTime();
		$AccountdetailData ['CreateUser'] = $ClientID;
		$AccountdetailData ['CreateTime'] = getTime();
		$AccountdetailData ['InAccountStatus'] = C('InAccountStatus.YRZ');
		$AccountdetailData ['Money'] = $CJKCJE; // 实际入账
		$AccountdetailData ['InAccountTime'] = getTime(); // 入账时间
		$AccountdetailData ['LastBalance'] = $AccountinfoUpdateData ['TMoneyWallet']; // 入账后钱包余额
		$AccountdetailData ['Remark'] = "";

		if (false !== $result && 0 !== $result) {
			$result = $Accountdetail->lock(true)->add($AccountdetailData);
		}


		if (false !== $result && 0 !== $result) {
			$Lotteryrecord->commit();

		}


	}

	public
	function YuYuePD()
	{
		$this->display("yypd");
	}


	/******End: 抽奖开始处理函数************/

	/*
	 * 写日志
	 */
	private
	function WriteLog($path, $message)
	{
		$open = fopen($path, "a+");
		fwrite($open, $message);
		fclose($open);
	}

}
