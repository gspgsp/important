<?php
/**
 *塑料圈用户信息-zhanpeng
 */
class contactInfoModel extends model
{
    public function __construct()
    {
        parent::__construct(C('db_default'), 'contact_info');
    }
}