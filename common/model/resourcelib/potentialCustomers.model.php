<?php
/**
 *
 * 测试导入资源库抓取电话号码表
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/12/13
 * Time: 9:57
 */

class potentialCustomersModel extends model
{
    public function __construct()
    {
        parent::__construct(C('db_default'), 'potential_customers');
    }
}