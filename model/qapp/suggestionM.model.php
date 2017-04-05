<?php
/**
 *塑料圈关注牌号-zhanpeng
 */
class suggestionMModel extends model
{
    public function __construct()
    {
        parent::__construct(C('db_default'), 'suggestion_model');
    }
}