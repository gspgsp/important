<?php

$email_body_header = <<<'EOT'
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml'>
　<head>
　　<meta http-equiv='Content-Type' content='text/html; charset=UTF-8' />
　</head>
<body>
<table style='border:1px solid #ddd' cellpadding='0' cellspacing='0' align='center' border='0' width='636'>
		<tbody>
			<tr>
			<td bgcolor='#f5f5f5'><img src='{__IMG__}/mail_head.png' width='636' height='102' /></td>
			</tr>
			<tr>
				<td bgcolor='#f5f5f5' height='10'></td>
			</tr>
EOT;
$email_body_footer = <<<'EOT'
			<tr>
				<td>
					<table style='font-size:12px; text-align:center; width:100%; background-color:#f5f5f5; padding:20px 0px 30px; color:#999999; line-height:24px;'>
						<tbody><tr>
							<td>此为系统邮件，请勿回复</td>
						</tr>
						<tr>
							<td>本页面内容最终解释权归{COMPANY_NAME}拥有</td>
						</tr>
						<tr>
							<td>如有任何疑问，您可以拨打我们的客服电话 <strong>{SERVICE_PHONE}</strong> 或 <a style='color:#e6551d;' href='mailto:{SERVICE_EMAIL}' target='_blank'>联系我们</a></td>
						</tr>
					</tbody></table>				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>
EOT;
return array(
	'mail_verify_email_subject' => '用户邮箱地址认证',
	'mail_verify_email_body' => <<<EOT
{$email_body_header}
			<tr>
				<td>
					<table style='font-family:'宋体';line-height:24px; padding:10px; font-size:14px; width:100%;'>
						<tbody><tr>
							<td style='font-weight:bold; padding:10px 0px;'>{NAME}，您好</td>
						</tr>
						<tr>
							<td style='text-indent:2em;'>请点击下面链接，完成邮箱认证，以便获取电子账单，最新投资理财资讯，并提升您的账户安全性。</td>
						</tr>
						<tr>
							<td style='padding:10px 0px; text-indent:2em;'><a href='{VERIFY_URL}' target='_blank'>点击认证您的邮箱</a></td>
						</tr>
						<tr>
							<td style='padding-top:20px; text-indent:2em;'>如果您不能点击上面链接，还可以将以下链接复制到浏览器地址栏中访问：</td>
						</tr>
						<tr>
							<td style='padding-bottom:10px; text-indent:2em;'>{VERIFY_URL}</td>
						</tr>
						<tr>
							<td style='border-top:1px dashed #bababa; padding-top:14px;'>{SITE_NAME}欢迎您的加入。</td>
						</tr>
					</tbody></table>
				</td>
			</tr>
		{$email_body_footer}
EOT
	,
	'mail_reset_email_subject' => '用户邮箱地址修改',
	'mail_reset_email_body' => <<<EOT
{$email_body_header}
		<tr>
			<td>
				<table style='font-family:'宋体';line-height:24px; padding:10px; font-size:14px; width:100%;'>
					<tbody><tr>
						<td style='font-weight:bold; padding:10px 0px;'>{NAME}，您好</td>
					</tr>
					<tr>
						<td style='text-indent:2em;'>您的邮箱验证码为：{VERIFY_CODE} </td>
					</tr>
					<tr>
						<td style='border-top:1px dashed #bababa; padding-top:14px;'>{SITE_NAME}欢迎您的加入。</td>
					</tr>
				</tbody></table>				</td>
		</tr>
		{$email_body_footer}
EOT
	,
	'mail_refund_notice_subject' => '到期还款提醒',
	'mail_refund_notice_body' => <<<EOT
{$email_body_header}
		<tr>
			<td>
				<table style='font-family:'宋体';line-height:24px; padding:10px; font-size:14px; width:100%;'>
					<tbody><tr>
						<td style='font-weight:bold; padding:10px 0px;'>{NAME}，您好</td>
					</tr>
					<tr>
						<td style='text-indent:2em;'>您的融资项目：{ITEM_NAME}，本月需还款：{NEXT_TOTAL}元，最迟还款日期：{BALANCE_TIME}。 </td>
					</tr>
					<tr>
						<td style='border-top:1px dashed #bababa; padding-top:14px;'>{SITE_NAME}迎您的加入。</td>
					</tr>
				</tbody></table>				</td>
		</tr>
		{$email_body_footer}
EOT
	,
	'mail_bill_subject' => '电子账单',
	'mail_bill_body' => <<<EOT
{$email_body_header}
		<tr>
			<td>
				<table style='font-family:'宋体';line-height:24px; padding:10px; font-size:14px; width:100%;'>
					<tbody><tr>
						<td style='font-weight:bold; padding:10px 0px;'>{NAME}，您好</td>
					</tr>
					<tr>
						<td style='text-indent:2em;'>您{MONTH_NAME}账单信息如下： </td>
					</tr>
					<tr>
						<td>
							{BILL}
						</td>
					</tr>
					<tr>
						<td><a href='{APP_URL}/my/bill' target='_blank'>查看历史账单</a></td>
					</tr>
					<tr><td></td></tr>
					<tr>
						<td style='border-top:1px dashed #bababa; padding-top:14px;'>{SITE_NAME}欢迎您的加入。</td>
					</tr>
				</tbody></table>				</td>
		</tr>
		{$email_body_footer}
EOT
);
