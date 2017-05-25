<?php

/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/2/17
 * Time: 1:49 AM
 */
class scoreAction extends baseAction
{
    private $param;
    private $is_mobile = false;


    /**
     * 积分记录
     * @api {post} /qapi_3/score/scoreRecord   塑料圈app之积分记录
     * @apiVersion 3.1.0
     * @apiName  pointSupplyList
     * @apiGroup score
     * @apiUse UAHeader
     *
     * @apiParam   {int} page   页码
     * @apiParam   {int} size   野蛮
     *
     * @apiSuccess {int}  err   错误码
     * @apiSuccess {String}   msg   描述
     *
     * @apiSuccessExample {json} Success-Response:
     *     array(
    'err'       => 0,
    'data'      => $data['data'],
    'pointsAll' => $points,
    )
     * @apiErrorExample {json} Error-Response:
     *      {
     *       "err": 2,
     *       "msg": "没有相关数据"
     *      }
     */
    public function scoreRecord ()
    {
        $this->is_ajax = true;
        if ($_POST) {
            $user_id = $this->checkAccount ();
            $page    = sget ('page', 'i', 1);
            $size    = sget ('size', 'i', 10);
            //$data=M("qapp:pointsBill")->select('id,addtime,type,points')->where("uid = $user_id and type in (2,3,5,6)")->order('id desc')->page($page,$size)->getPage();
            $data = M ("qapp:pointsBill")->select ('id,addtime,type,points,share_type')->where ("uid = $user_id and is_mobile =1")
                                         ->order ('id desc')->page ($page, $size)->getPage ();
            if ((empty($data['data']) && $page == 1) || $page>10) {
                $this->json_output (array(
                    'err' => 2,
                    'msg' => '没有相关数据',
                ));
            }
            $this->_checkLastPage ($data['count'], $size, $page);
            //我的积分
            $points = M ('qapp:pointsBill')->getUerPoints ($user_id);
            $points = empty($points) ? 0 : $points;
            foreach ($data['data'] as $k => &$v) {
                $v['typename'] = $this->pointsType[$v['type']];
                if ($v['type'] == 13) {
                    $v['typename'] = $this->shareType[$v['share_type']];
                }

                $v['addtime'] = $this->checkTime ($v['addtime']);
                unset($v['type']);
            }
            $this->json_output (array(
                'err'       => 0,
                'data'      => $data['data'],
                'pointsAll' => $points,
            ));
        }
        $this->_errCode (6);
    }

    /*
 * 塑料圈app之积分规定
 */
    public function pointsRule ()
    {
        $this->display ('plasticzone/points_rule.html');
        //        if ($_POST) {
        //            $this->is_ajax = true;
        //            $this->checkAccount ();
        //            $salePoints = intval (M ('system:setting')->get ('points')['points']['sale']);
        //            $purPoints  = intval (M ('system:setting')->get ('points')['points']['pur']);
        //            $rule       = '';
        //            $rule .= '<span>1. 每日发布报价/求购一条，增加'.$salePoints.'/'.$purPoints.'积分</span><br />';
        //            $rule .= '<span>2. 与我的塑料网成交后自动累计积分，买的多送的多</span><br />';
        //            $rule .= '<span>3. 积分商城积分兑换的商品不但免费还免运费</span>';
        //            $this->json_output (array( 'err' => 0, 'rule' => $rule ));
        //        }
        //        $this->_errCode (6);
    }

}
