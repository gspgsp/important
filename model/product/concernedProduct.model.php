<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/6/27
 * Time: 11:47
 */

class concernedProductModel extends Model{
    public function __construct() {
        parent::__construct(C('db_default'), 'concerned_product');
    }

    /**关注列表
     * 根据user_id查询用户关注信息
     * @param $where
     */
    public function getConcernedList($where){

        return  $this->from('concerned_product as cp')
            ->where($where)
            ->select('cp.id, cp.product_id')
            ->getAll();

    }


}