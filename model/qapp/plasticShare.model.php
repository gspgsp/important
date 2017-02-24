<?php
/**
 *塑料圈分享模型-zhanpeng
 */
class plasticShareModel extends model
{
    public function __construct() {
        parent::__construct(C('db_default'), 'share_log');
    }
    public function getMySharePur($id){
        $where = " pur.id = $id ";
        $data = $this->model('purchase')->select('pur.id,pur.p_id,pur.user_id,pro.model,pur.unit_price,pur.store_house,fa.f_name,pur.input_time,pur.type,pur.content')->from('purchase pur')
            ->leftjoin('product pro','pur.p_id=pro.id')
            ->leftjoin('factory fa','pro.f_id=fa.fid')
            ->where($where)
            ->getRow();

        //显示的内容
        if(empty($data['content'])){
            if($data['unit_price']==0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])){
                return false;
            }else{
                $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['f_name'].'/'.$data['store_house'];
            }
        }elseif(!empty($data['content'])){
            if($data['unit_price']==0.00 && empty($data['model']) && empty($data['f_name']) && empty($data['store_house'])){
                $data['contents'] = $data['content'];
            }else{
                $data['contents'] = '价格'.$data['unit_price'].'元左右/'.$data['model'].'/'.$data['f_name'].'/'.$data['store_house'].'/'.$data['content'];
            }
        }



        $arr['type']=$data['type'];
        $arr['input_time']=date("Y-m-d H:i:s",$data['input_time']);
        $arr['content'] = mb_substr(strip_tags($data['contents']), 0, 50, 'utf-8') . '...';
        return $arr;
    }




    public function saveShareLog($share_id,$type,$user_id,$share){
        $today = strtotime(date('Y-m-d',time()));
        $share_count = $this->where("user_id=$user_id and input_time > $today")->select('count(id)')->getOne();
        $contact = M('user:customerContact')->getListByUserid($user_id);
        $_log = array(
            'user_id'=>$user_id,
            'name'=>$contact['name'],
            'mobile'=>$contact['mobile'],
            'share_id'=>$share_id,
            'type'=>$type,
            'input_time'=>CORE_TIME,
        );
        if($this->add($_log)){
            if($share_count < 1){
                M('qapp:pointsBill')->addPoints($share, $user_id, 13, 1);
            }
            return true;
        }else{
            return false;
        }
    }
}