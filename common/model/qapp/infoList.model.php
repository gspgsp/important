<?php
class infoListModel extends Model
{

    public function __construct()
    {
        parent::__construct(C('db_default'), 'info_list');
    }

}