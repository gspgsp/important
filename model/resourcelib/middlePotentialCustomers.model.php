<?php
/**
 * Created by PhpStorm.
 * User: yuanjiaye
 * Date: 2016/12/13
 * Time: 16:38
 */


class middlePotentialCustomersModel extends model
{
    public function __construct()
    {
        parent::__construct(C('db_default'), 'middle_potential_customers');
    }
}