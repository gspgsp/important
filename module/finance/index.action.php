<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/7/8
 * Time: 14:13
 */

class IndexAction extends  homeBaseAction{
    protected $db;
    public function __init()
    {
        $this->db=M('public:common');
    }

    public function init(){

        $this->display('finance');
    }
}