<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/6/27
 * Time: 9:32
 */
class productAttentionModel extends model{

    public function __construct() {
        parent::__construct(C('db_default'), 'concerned_product');
    }
    //获取关注列表
    public function getAttentionvalue(){
        return   $list = $this->db->model('concerned_product')->where('user_id='.$this->user_id)
                ->order("input_time desc")
                ->limit('6');

    }
}