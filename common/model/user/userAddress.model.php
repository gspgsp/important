<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/6/7
 * Time: 14:04
 */
//用户地址信息记录
class userAddressModel extends  model{

    public function __construct()
    {
        parent::__construct(C('db_default'), 'user_address');
    }


}