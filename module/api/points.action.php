<?php

/**
 * Created by PhpStorm.
 * User: janfly
 * Date: 5/12/17
 * Time: 1:49 AM
 */
class pointsAction extends null2Action
{
    private $param;
    private $is_mobile = false;

    public function setMobile(){
        $this->is_mobile = true;
        M("qapp:pointsBill")->setMoblie($this->is_mobile);
    }

    public function getScoreConfig(){
        return M ('system:setting')->get ('points')['points']; //这个是加了缓存的
    }

    /**
     * @param int $type  1  分享求购  2 分享供给(报价)  3 分享头条
     * @param int $user_id
     * @return array
     */
    public function addScoreByShare($type = 0 ,$user_id = 0){
        $points = $this->getScoreConfig();
        $this->setMobile();
        $_today= strtotime(date("Y-m-d"));
        if(!in_array($type,array(1,2,3)) || $user_id < 1){
            return array('err'=>6,'msg'=>'参数错误');
        }
        switch($type) {
            case 1://分享求购
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=1 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) {
                    return array('err'=>0,'msg'=>'今天求购分享次数>5，不加积分');
                }
                M ("qapp:pointsBill")->startTrans();
                if(!M ("qapp:pointsBill")->addPoints ($points['share_pur'], $user_id, 13,1)){
                    M ("qapp:pointsBill")->rollback();
                    return array( 'err' => 101, 'msg' => '系统错误' );
                }
                M ("qapp:pointsBill")->commit();
                return array( 'err' => 0, 'msg' => '积分添加成功' );
                break;
            case 2://分享供给
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=2 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) return array('err'=>0,'msg'=>'今天供给分享次数>5，不加积分');
                M ("qapp:pointsBill")->startTrans();
                if(!M ("qapp:pointsBill")->addPoints ($points['share_pur'], $user_id, 13,2)){
                    M ("qapp:pointsBill")->rollback();
                    return array( 'err' => 101, 'msg' => '系统错误' );
                }
                M ("qapp:pointsBill")->commit();
                return array( 'err' => 0, 'msg' => '积分添加成功' );
                break;
            case 3://分享头条
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type =13 and share_type=3 and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) return array('err'=>0,'msg'=>'今天塑料头条分享次数>5，不加积分');
                M ("qapp:pointsBill")->startTrans();
                if(!M ("qapp:pointsBill")->addPoints ($points['share_news'], $user_id, 13,3)){
                    M ("qapp:pointsBill")->rollback();
                    return array( 'err' => 101, 'msg' => '系统错误' );
                }
                M ("qapp:pointsBill")->commit();
                return array( 'err' => 0, 'msg' => '积分添加成功' );
                break;
        }

    }

    /**
     * @param int $type   1  求购  2  报价（供给）
     * @param int $standard   1 标准  2 不标准
     * @param int $user_id
     * @return array
     */
    public function addScoreByPur($type = 0,$standard = 0 ,$user_id = 0){
        $points = $this->getScoreConfig();
        $this->setMobile();
        $_today= strtotime(date("Y-m-d"));
        if(!in_array($type,array(1,2)) || $user_id < 1){
            return array('err'=>6,'msg'=>'参数错误');
        }
        M ("qapp:pointsBill")->startTrans();
        switch($type){
            case 1://发布求购
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type in (6,18) and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) return array('err'=>0,'msg'=>'今天发布求购次数>5，不加积分');
                //   1 标准  2 不标准
                $points['pur'] = $standard==2?$points['pur']:$points['standard_pur'];
                if(!M ("qapp:pointsBill")->addPoints ($points['pur'], $user_id, $standard==2?6:18)){
                    M ("qapp:pointsBill")->rollback();
                    return array( 'err' => 101, 'msg' => '系统错误' );
                }
                M ("qapp:pointsBill")->commit();
                return array( 'err' => 0, 'msg' => '积分添加成功' );
                break;
            case 2:
                $_tmp = M ("qapp:pointsBill")->select('id ,addtime')->where("uid = $user_id and type in (3,17) and addtime>$_today")->order("id desc")->getAll();
                if(count($_tmp)>=5) return array('err'=>0,'msg'=>'今天发布供给次数>5，不加积分');
                //   1 标准  2 不标准
                $points['sale'] = $standard==2?$points['sale']:$points['standard_sale'];
                if(!M ("qapp:pointsBill")->addPoints ($points['sale'], $user_id, $standard==2?3:17)){
                    M ("qapp:pointsBill")->rollback();
                    return array( 'err' => 101, 'msg' => '系统错误' );
                }
                M ("qapp:pointsBill")->commit();
                return array( 'err' => 0, 'msg' => '积分添加成功' );
                break;
        }

    }

    /**
     * @param int $points
     * @param int $user_id
     * @return array
     */
    public function addScoreByMoney($points = 0, $user_id = 0){
        $this->setMobile();
        if($points < 1 || $user_id < 1){
            return array('err'=>6,'msg'=>'参数错误');
        }
        if(empty($points)) return array('err'=>6,'msg'=>'积分数值错误');
        M ("qapp:pointsBill")->startTrans();
        if(!M ("qapp:pointsBill")->addPoints ($points, $user_id, 16)){
            M ("qapp:pointsBill")->rollback();
            return array( 'err' => 101, 'msg' => '系统错误' );
        }
        M ("qapp:pointsBill")->commit();
        return array( 'err' => 0, 'msg' => '积分添加成功' );
    }

    /**
     * @param int $points
     * @param int $user_id
     * @param int $gid
     * @return array
     */
    public function desScoreByProduct($points = 0 ,$user_id =0, $gid = 0){
        if(empty($gid) || $user_id < 1) return array('err'=>6,'msg'=>'参数错误');
        M ("qapp:pointsBill")->startTrans();
        //decPoints ($num = 0, $uid = 0, $type = 0, $gid = 0)
        if (!M("qapp:pointsBill")->decPoints($points, $user_id, 5 ,$gid)) {
            M("qapp:pointsBill")->rollback();
            return array('err' => 100, 'msg' => '积分不足');
        }
        M("qapp:pointsBill")->commit();
        return array('err' => 0, 'msg' => '积分减少成功');
    }

    /**
     * @param int $user_id
     * @param int $other_id
     * @return array
     */
    public function desScoreByTongxulu($user_id = 0 ,$other_id = 0){
        $points = $this->getScoreConfig();
        $points = $points['see_list'];
        $this->setMobile();
        if($other_id< 1 || $user_id < 1){
            return array('err'=>6,'msg'=>'参数错误');
        }
        M ("qapp:pointsBill")->startTrans();
        $_tmp=M("qapp:infoList")->where("user_id= $user_id and other_id = $other_id")->order("info_list_id desc")->getOne();
        if(!M("qapp:infoList")->add(array('user_id'=>$user_id,'other_id'=>$other_id,'input_time'=>CORE_TIME))){
            M("qapp:pointsBill")->rollback();
            return array('err' => 101, 'msg' => '系统错误');
        }
        if($_tmp){
            M("qapp:pointsBill")->commit();
            return array('err' => 0, 'msg' => '已减过积分,此次不用了');
        }
        if (!M("qapp:pointsBill")->decPoints($points, $user_id, 14 ,$gid =$other_id )) {
            M("qapp:pointsBill")->rollback();
            return array('err' => 100, 'msg' => '塑豆不足');
        }
        M("qapp:pointsBill")->commit();
        return array('err' => 0, 'msg' => '积分减少成功');
    }












}
