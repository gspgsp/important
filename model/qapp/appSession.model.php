<?php

/**
 * 塑料圈的sessionId的获取
 * Class appSessionModel
 */
class appSessionModel extends model
{
    private $sessionId='';  //当前session ID

    public function __construct() {
        $this->sessionId = $_SESSION[C('SESSION_NAME')];
    }

    /**
     * 获取用户的sessionID
     * @access public
     * @return string
     */
    public function getSessionValue(){
        return $this->sessionId; // 返回session id
    }

    /**
     * 生成sessionID对应的值
     * @access public
     * @return string
     */
    public function getSessionAppend(){
        return C('SESSION_NAME')."=".$this->getSessionValue(); // 返回session id的字符串
    }
}