<?php
/**
 * Created by PhpStorm.
 * User: sick
 * Date: 2/23/17
 * Time: 1:14 AM
 */
class logisticsSupplierModel extends model
{
    public function __construct()
    {
        parent::__construct(C('db_default'), 'logistics_supplier');
    }
}