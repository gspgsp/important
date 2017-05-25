<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 4/4/17
 * Time: 12:41 AM
 */
class scoreModel extends model{
    public function __construct() {
        parent::__construct(C('db_default'), 'suliaoquan_score');
    }

    /*
     * 获取所有分数信息
     * @access public
     * @return int
     */
    public function getScore($user_id){
        $score=$this->select('*')->where("user_id = ".$user_id)->getRow();
        if(empty($score)){
            return false;
        }
        return $score['score'];
    }

}