<?php
    /**
     * 项目应用配置文件
     */
    return array(
        //默认设定
        'DEFAULT_M'      => 'index',  //默认模块名称
        'DEFAULT_C'      => 'index',  //默认控制器
        'DEFAULT_A'      => 'init',   //默认操作
        //'配置项'=>'配置值'
        'SESSION_ADMIN'  => true,    //是否自动开启Session
        'SESSION_TTL'    => 180000,  //session有效期
        'SESSION_TYPE'   => 'memcache', //mysql或memcache
        'SESSION_NAME'   => 'cSsR_SSID', //名称
        'SESSION_TABLE'  => 'p2p_session', //mysql:完整session表
        'SESSION_DTABLE' => 'p2p_session_data', //mysql:完整大session数据表
        'DES_PASSCODE'   => '123456789',
        //测试白名单用户
        'white_test'     => array(),
        'payment'        => array(
            'jyt' => array('name' => ''),
        ),
        //公司部门配置
        'depart'         => array(
            1 => '销售部',
            2 => '物流部',
            3 => '财务部',
            4 => '电商部',
            5 => '行政人事部',
        ),
    );
?>
