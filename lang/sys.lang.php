<?php
/**
 * 项目语言项
*/
return array(
	//登录渠道:1web,2app,3wap,4微信(保留)
	'user_chanel'=>array(
		1=>'web',				 
		2=>'touch',	
		3=>'weixin',
		7=>'android',
		8=>'ios',
		9=>'wp',
	),
	//物流公司
	'ship_company'=>array(
		1=>'企辉物流',
		2=>'拓桥物流',
		3=>'凤莲物流',
		4=>'龙腾运输',
		5=>'灏泰物流',
	),
	//性别
	'sex'=>array(
		0=>'男',
		1=>'女',
	),
	//客户类型
	'company_type'=>array(
		1=>'工厂',
		2=>'贸易',
		3=>'工贸一体',
	),
	//客户级别
	'company_level'=>array(
		1=>'A级100万以上',
		2=>'B级30-100万',
		3=>'C级10-30万',
		4=>'D级10万以下',
	),
	//客户来源渠道
	'company_chanel'=>array(
		0=>'电话',
		1=>'我的塑料网',
		2=>'网络',
		3=>'客户介绍',
		4=>'朋友介绍',
		5=>'地面推广',
		6=>'市场推广',
		7=>'其它',
	),

	//客户信用等级
	'credit_level'=>array(
		1=>'A级',
		2=>'B级',
		3=>'C级',
		4=>'D级',
		5=>'E级',
	),

	//客户状态
	'status'=>array(
		1=>'待审核',
		2=>'已审核',
		3=>'不通过',
		4=>'已作废',
	),
	//客户认证
	'identification'=>array(
		1=>'待认证',
		2=>'已认证',
	),
	//产品分类
	'product_type'=>array(
		1=>'HDPE',
		2=>'LDPE',
		3=>'LLDPE',
		4=>'PP',
		5=>'PVC',
	),
	//加工级别
	'process_level'=>array(
		1=>'重包',
		2=>'涂覆',
		3=>'薄膜',
		4=>'滚塑',
		5=>'注塑',
		6=>'中空',
		7=>'管材',
		8=>'拉丝',
		9=>'纤维',
		10=>'茂金属',
		11=>'其他',
	),
	//期货周期
	'period'=>array(
		1=>'10天以内',
		2=>'10-20天',
		3=>'20-30天',
		4=>'30天以上',
	),
	//货物类型
	'cargo_type'=>array(
		1=>'现货',
		2=>'期货',
	),
	//价格单位
	'price_type'=>array(
		1=>'RMB',
		2=>'USD',
	),
	//归属区域 1华东 2华南 3华北
	'belong_area'=>array(
		1=>'华东',
		2=>'华南',
		3=>'华北',
		4=>'华中',
		5=>'东北',
		6=>'西北',
		7=>'西南',
		8=>'青藏',
	),
	//货物状态
	'product_status'=>array(
		1=>'上架',
		2=>'下架',
		3=>'待审核',
		4=>'审核不通过',
	),
	//采购信息审核状态
	'purchase_status'=>array(
		1=>'待审核',
		2=>'审核通过',
		3=>'洽谈中',
		4=>'交易成功',
		5=>'无效',
		6=>'过期',
	),
	//是否是vip客户
	'is_vip'=>array(
		1=>'是',
		2=>'否',
	),
	//是否可以议价
	'bargain'=>array(
		1=>'可议价',
		2=>'实价',
	),

	//订单来源
	'order_source'=>array(
		1=>'网站',
		2=>'ERP',
		3=>'APP',
		4=>'接口',
		5=>'其他',
	),
	//付款方式
	'pay_method'=>array(
		1=>'银行电汇',
		2=>'现金',
		3=>'支票',
		4=>'转账',
		5=>'托收',
	),
	//运输方式
	'transport_type'=>array(
		1=>'供方送到',
		2=>'自理',
	),
	//业务模式
	'business_model'=>array(
		1=>'利润',
		2=>'撮合',
	),
	//财务记录
	'financial_records'=>array(
		1=>'是',
		2=>'否',
	),
	//订单审核
	'order_status'=>array(
		1=>'待审核',
		2=>'已审核',
		3=>'已取消',
	),
	//订单审核
	'transport_status'=>array(
		1=>'待审核',
		2=>'已审核',
		3=>'已关闭',
	),
	//发货状态
	'goods_status'=>array(
		1=>'待发货',
		2=>'部分发货',
		3=>'全部发货',
	),
	//开票状态
	'invoice_status'=>array(
		1=>'待开票',
		2=>'部分开票',
		3=>'全部开票',
	),
	//数量单位
	'unit'=>array(
		1=>'吨',
	),
	//入库状态
	'in_storage_status'=>array(
		1=>'待入库',
		2=>'已入库',
	),
	//出库状态
	'out_storage_status'=>array(
		1=>'待出库',
		2=>'已出库',
	),
	//销售类型
	'sales_type'=>array(
		1=>'先采后销',
		2=>'先销后采',
	),
	//采购类型
	'purchase_type'=>array(
		1=>'销售采购',
		2=>'备货采购',
	),
	//出库类型
	'out_type'=>array(
		1=>'销售出库',
		2=>'直接出库',
	),
	//入库类型
	'in_type'=>array(
		1=>'采购入库',
		2=>'直接入库',
	),
	//订单类型
	'order_type'=>array(
		1=>'销售订单',
		2=>'采购订单',
	),
	//订单开票类型
	'billing_type'=>array(
		1=>'销售订单开票',
		2=>'采购订单开票',
	),

	//交货方式
	'ship_type'=>array(
		1=>'自提',
		2=>'送货上门',
		3=>'其他',
	),
	//积分订单状态
	'points_status'=>array(
		1=>'已兑换，待确认',
		2=>'已确认，待发货',
		3=>'已发货',
		4=>'订单取消',
		5=>'订单完成',
	),

	//快递公司
	'express_company'=>array(
		1=>'顺丰',
		2=>'EMS',
		3=>'圆通',
		4=>'中通',
		5=>'汇通',
		6=>'申通',
		4=>'韵达',
		5=>'天天',
		6=>'宅急送',
	),
	//关注状态
	'attention_status'=>array(
		1=>'关注中',
		2=>'已取消',
		),
	//操作
	'operate'=>array(
		1=>'取消关注',
		2=>'重新关注',
		),

	//商品分类
	'goods_category'=>array(
		1=>'家居',
		2=>'数码',
		3=>'母婴',
		4=>'玩具',
		5=>'食品',
		6=>'美容',
		4=>'配饰',
		5=>'运动',
	),

	//站内信消息类型
	'msg_type'=>array(
		1=>'系统消息',
		2=>'交易信息',
	),
	//站内信消息状态
	'msg_status'=>array(
		1=>'未读',
		2=>'已读',
	),

	//交易公司账户
	'company_account'=>array(
		1=>'中晨',
		2=>'梓晨',
	),
	//管理员审批日志
	'operation_type'=>array(
		'recharge'=>'充值',
		'credit_level'=>'借贷等级',
		'paypasswd_modify'=>'支付密码修改',
		'passwd_modify'=>'登录密码修改',
		'passwd_find'=>'找回登录密码',
		'phone_modify'=>'手机修改',
		'paypasswd_find'=>'找回交易密码',
		'idcard_verify'=>'身份证认证',
		'bank_verify'=>'银行卡认证',
		'paypasswd_set'=>'设置交易密码',
		'withdraw'=>'提现',
		'questions'=>'设置安全问题',
		'email_verify'=>'验证邮箱',
		'email_modify'=>'修改邮箱',
		'riskrating'=>'风险评估',
		'bank_amount'=>'修改银行认证金额',
		'incharge_amount'=>'给会员充值',
		'transfer_amount'=>'会员间转账',
		'edit_whitelist'=>'设置用户提现白名单',
		'edit_rfwhitelist'=>'设置用户推荐白名单',
		'set_customer_manager'=>'设置客户经理',
		'unlock_user'=>'解除登录锁定用户',
		'reset_passwd'=>'重置用户密码',
		'reset_paypasswd'=>'重置用户交易密码',
	),

);