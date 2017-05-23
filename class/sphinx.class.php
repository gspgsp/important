<?php

/**
 * Created by PhpStorm.
 * User: sick
 * Date: 17-5-22
 * Time: 下午3:09
 */
class sphinx
{
    public function __construct ()
    {
        $this->sphinx = new SphinxClient;
        $this->sphinx ->SetServer('localhost', 9312);
        $this->sphinx ->SetMatchMode(SPH_MATCH_EXTENDED2);
    }

    /**
     * sphinx调用接口
     * @param $keywords
     * @param string $index
     * @param $page
     * @param $size
     * @param array $filter array(' input_time DESC',' @relevance DESC')
     * @param array $sort  默认按照最新排序
             *        $sort = array(
            'eq'=>array(
            array(
            'attr'=>'user_id',
            'val'=>'32131',
            'exclude'=>false
            ),
            array(
            'attr'=>'name',
            'val'=>'谢磊',
            'exclude'=>false
            )
            )，
            'range'=>array(
            array(
            'attr'=>'user_id',
            'min'=>'10000',
            'max'=>'50000',
            'exclude'=>false
            ),
            array(
            'attr'=>'price',
            'min'=>'100',
            'max'=>'3000',
            'exclude'=>false
            )
            )，
            );
     * @return array
     */


    public function search($keywords,$index='*',$page,$size,$filter=array(),$sort=array())
    {
        if(empty($sort))
        {
            $this->sphinx ->SetSortMode(SPH_SORT_EXTENDED, ' @id DESC');
        }else{
            if(count($sort)>5)
            {
                return false;
            }
            $sort_str = join(',',$sort);
            $this->sphinx ->SetSortMode(SPH_SORT_EXTENDED, $sort_str);
        }
        if(!empty($filter['eq']))
        {
            foreach($filter['eq'] as $item)
            {
                $this->sphinx ->SetFilter ($item['attr'],$item['val'],isset($item['exclude'])?$item['exclude']:false);
            }
        }
        if(!empty($filter['range']))
        {
            foreach($filter['range'] as $item)
            {
                $this->sphinx ->SetFilterRange ($item['attr'],$item['min'],$item['max'],isset($item['exclude'])?$item['exclude']:false);
            }
        }
        $this->sphinx ->setLimits (abs ($page - 1) * $size, $size, 1000);
        $result = $this->sphinx ->query ($keywords, $index);

        $ids    = array_keys ($result['matches']);

        return $result;
    }

}