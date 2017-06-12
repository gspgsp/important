<?php
/**
 * 项目语言项
*/
return array(
	//项目筹集状态
	'item_status'=>array(
		1=>'待发布',
		2=>'已发布',
		3=>'取消/失败',
		9=>'完成',
	),
	'sms_channels'=>array(
		1=>'嘉讯软件',
		2=>'食指网络',
		3=>'大汉三通',
		4=>'上海月呈',
		5=>'创蓝文化',
		6=>'亿美短信',
	),
	'theme_types'=>array(
		'default'=>'默认主题',
	),
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
	//后台管理员用户状态
	'adm_status'=>array(
		0=>'禁用',
		1=>'正常',
	),
	//公司联系人状态
	'contact_status'=>array(
		1=>'正常',
		2=>'冻结',
		3=>'关闭',
	),
	//厂家管理状态
	'factory_status'=>array(
		1=>'正常',
		2=>'锁定',
	),
		//客户公司审核状态
	'cus_cop_status'=>array(
		1=>'待审核',
		2=>'已审核',
		3=>'不通过',
		4=>'已作废',
		5=>'待认证',
		6=>'已认证',
	),
	//商品状态
	'product_status'=>array(
		1=>'上架',
		2=>'下架',
		),
	//商品单位
	'unit'=>array(
		1=>'吨',
		),
	//业务员跟进客户方式1 座机、2手机、3QQ,
	'follow_up_way'=>array(
		1=>'座机',
		2=>'手机',
		3=>'QQ',
		),

	//票据类型
	'bile_type'=>array(
		1=>'增值税',
		2=>'商业发票',
		3=>'服务业发票',
		),

	'chk_node'=>array(
		array(
			'id'=>1,
			'text'=>"一级流程"
			,),
		array(
			'id'=>2,
			'text'=>"二级流程",
			),
		array(
			'id'=>3,
			'text'=>"三级流程",
			),
		array(
			'id'=>4,
			'text'=>"四级流程",
			),
		),

	//账户类型
	'account_status'=>array(
		1=>'银行账户',
		2=>'现金账户',
		),

	//记账类型
	'account_type'=>array(
		1=>'入账',
		2=>'出账',
		),

	//账户订单来源
	'order_chanel'=>array(
		0=>'内部系统',
		1=>'网站来源',
		),
	//战队名称
	'team'=>array(
		34=>'敢死队',
		35=>'战狼战队',
		36=>'雄狮战队',
		37=>'火焰战队',
		38=>'雷霆战队',
		40=>'临沂分公司',
		41=>'余姚分公司',
		42=>'常州分公司',
		46=>'盘锦分公司',
		49=>'超人战队',
		54=>'嘉善分公司',
		50=>'广州分公司',
		53=>'国际贸易部',
		),

	//塑料圈认证0 未认证、1 认证通过 2  认证不通过
	'is_pass'=>array(
		0=>'未认证',
		1=>'通过',
		2=>'不通过',
		),
	//资源库抓取电话
	'is_status'=>array(
		0=>'未打',
		1=>'已打',
	),

	//出库后是否收货
	'sign'=>array(
		0=>'未收货',
		1=>'已收货',
	),

	//客户流转操作类型
	'action_type'=>array(
		'register'=>'注册',
		'allocation'=>'分配交易员',
		'check'=>'审核',
		'change'=>'修改资料',
		'share'=>'共享操作',
		'revocation'=>'撤销黑名单',
		'delete'=>'删除',
	),

	//授信状态
	'is_credit'=>array(
		0=>'未授信',
		2=>'预授信',
		1=>'已授信',
		3=>'拒绝',
	),
	//授信状态用于筛选
	'is_credit_new'=>array(
		1=>'未授信',
		2=>'已授信',
		3=>'预授信',
		4=>'拒绝',
	),
	//战队配资操作类型
	'team_capital_type'=>array(
		'sale_come'=>'财务收款',
		'sale_red'=>'财务收款红冲',
		'buy_red'=>'财务付款红冲',
		'buy_pay'=>'采购付款申请',
		'un_buy_pay'=>'采购付款申请到特批',
		'buy_pay_pass'=>'特批通过',
		'buy_pay_unpass'=>'特批不通过',
		'buy_pay_del'=>'付款申请删除',
		'sale_transport_pass'=>'销售物流审核通过',
		'buy_transport_pass'=>'采购物流审核通过',
		'buy_transport_unpass'=>'采购物流审核不通过',
		'buy_order_unpass'=>'采购订单审核不通过',
		'sale_invalid'=>'销售单作废',
		'buy_invalid'=>'采购单作废',
	),
	'customer'=>array(
		 'c_id' =>  '客户id';
		 'c_name' => '客户名称',
		 'type'  =>   '1 塑料制品厂、2 原料供应商、3 工贸一体  4 服务商',
		 'level'       =>             '客户级别1:A级100万以上、 2:B级30-100万、3:C级10-30万、4:D级10万以下',
		 'chanel'            =>       '客户渠道',
		 'need_product_adm'   =>      '系统根据订单统计的所需牌号情况',
		 'need_product'          =>   '所需要的牌号',
		 'origin'           =>        '省市',
		 'zip'              =>        '邮编',
		 'address'          =>        '详细地址',
		 'com_intro'         =>       '公司介绍',
		 'com_logo'          =>       '公司头像',
		 'file_url'          =>       '附件地址',
		 'fund_date'         =>       '成立日期',
		 'registered_capital'=>       '注册资本',
		 'credit_level'      =>       '信用等级',
		 'identification'     =>      '认证1,待认证2,已认证',
		 'status'             =>      '状态1,待审核2,已审核3,不通过4,已作废5,待认证6,已认证 8,黄名单客户 9为黑名单客户',
		 'business_licence'    =>     '营业执照证件号码',
		 'business_licence_pic' =>    '营业执照照片',
		 'organization_code'    =>    '组织机构代码',
		 'organization_pic'    =>     '组织机构代码图',
		 'tax_registration'      =>   '税务登记号码',
		 'tax_registration_pic'  =>   '税务登记图片',
		 'legal_person'         =>    '法人姓名',
		 'legal_idcard'        =>     '法人身份证号',
		 'legal_idcard_pic'   =>      '法人身份证照片',
		 'contact_id'         =>      '主要联系人id',
		 'customer_manager'   =>      '交易员',
		 'depart'              =>     '交易员所属部门',
		 'grounp_no'          =>      '组织代码-此字段用于判断内外部账号',
		 'remark'             =>      '备注',
		 'input_time'         =>      '创建时间',
		 'input_admin'         =>     '创建人姓名',
		 'update_time'        =>      '更新时间',
		 'update_admin'       =>      '更新操作人员的姓名',
		 'organization_state'  =>     '提交三证后状态:1,未提交2,(提交)未审核3,通过4,不通过',
		 'zip_url'              =>    '三证压缩地址',
		 'powerofattorney_pic'  =>    '授权委托书',
		 'invoice'              =>    '开票资料 1否2是',
		 'credit_status'        =>    '1 待评价 ,  2 已评价',
		 'download'             =>    '报价下载次数',
		 'quan_type'         =>       '塑料圈中注册的途径划分，chanel=6才有效。 0：塑料圈 ，1：塑料圈APP，2：塑料圈PC客户端',
		 'is_sale'              =>    '0否1是2拉黑待审核',
		 'is_pur'         =>          '0 否 1是 2拉黑待审核',
		 'last_follow'       =>       '最后一次跟进的时间戳',
		 'last_sale'       =>         '最后一次销售的收款完成的时间戳',
		 'last_no_sale'      =>       '没有合作的时间',
		 'month_consum'     =>        '月用量',
		 'main_product'      =>       '主营产品',
		 'true_capital'     =>        '实到资本',
		 'credit_limit'      =>       '信用额度',
		 'credit_time'       =>       '授信时间',
		 'pre_credit_limit'   =>      '预信用额度',
		 'is_credit'          =>      '是否授信 0 未授信 1 已授信 2  预授信 3拒绝',
		 'merge_three'        =>      '是否三证合一 0否1是',
		 'available_credit_limit' =>  '可用授信额度',
		 'china_area'         =>      '0 全部 1 华东 2 华北 3 华南 4 其他',
		 'msg'               =>       '是否发送短信 1否 2是',
		 'drive_end_place'   =>       '销售和采购送货地点',
		 'reason'            =>       '释放原因',
	),
);
?>
