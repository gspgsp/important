<?php
class newsModel extends model {
    //初始化模型
    public function __construct(){
        parent::__construct(C('db_default'),'news_content');
    }

    //通过分类id来获取各自分类的文章
    public function getIndex($type=''){
        $arr=array();
        $cates=$this->model('news_cate')->select('cate_id,cate_name,spell')->getAll();
        $where='status=1';
        if(!empty($type)){
            $where.=' and type in("'.$type.'","public")' ;
        }
        $time=strtotime(date('Y-m-d'));
        foreach ($cates as $v) {
            $arr[$v['spell']]=$this->model('news_content')->select('id,title,hot,input_time,type')->where($where.' and cate_id='.$v['cate_id'])->order('input_time desc,sort_order desc')->limit('19')->getAll();
            foreach ($arr[$v['spell']] as $k=>$v2) {
                if($v2['input_time']-$time>=0){
                    $arr[$v['spell']][$k]['today']=true;
                }
            }
            $arr[$v['spell']]['hot']=$this->model('news_content')->select('id,title,content,input_time,type')->where($where.' and cate_id='.$v['cate_id'].' and hot=1')->order('input_time desc,sort_order desc')->getRow();
            $arr[$v['spell']]['hot']['content']=mb_substr(strip_tags($arr[$v['spell']]['hot']['content']),0,67,'utf-8').'...';
        }
        return $arr;
    }

    //通过类型和分类来获取列表页文章
    public function getList($type,$cate_id,$page=1,$page_size=0){
        if($cate_id==22){
            $where='cate_id in (22,23,24,25,26,27,28,29,30,32)';
        }else{
            $where='cate_id ='.$cate_id;
        }

        if($type!='public'){
            $where.=' and type in ("'.$type.'","public")';
        }
        $this->model('news_content')->where($where)->select('id,title,content,cate_id,author,input_time,type')->order('input_time desc,sort_order desc');
        if($page&&$page_size){
            return $this->page($page,$page_size)->getPage();
        }elseif ($page_size) {
            $this->limit($page_size);
        }
        return $this->getAll();
    }
    /**
     * 通过类型和分类来获取列表页文章
     * @Author   zhanpeng
     * @DateTime 2016-11-4 09:50:04
     *
     * @return   [type]                   [description]
     */
    //通过类型和分类来获取列表页文章
    public function getqAppList($type,$cate_id,$page=1,$page_size=0){
        if($cate_id==22){
            $where='cate_id in (22,23,24,25,26,27,28,29,30,32)';
        }elseif($cate_id == 12){
            $where='cate_id in (13,14,15)';
        }else{
            $where='cate_id ='.$cate_id;
        }
        if($type!='public'){
            $where.=' and type in ("'.$type.'","public")';
        }
        $this->model('news_content')->where($where)->select('id,title,content,description,cate_id,author,input_time,type')->order('input_time desc,sort_order desc');
        if($page&&$page_size){
            return $this->page($page,$page_size)->getPage();
//				$this->page($page,$page_size)->getPage();
//				showTrace();exit;
        }elseif ($page_size) {
            $this->limit($page_size);
        }
        return $this->getAll();
    }

    //通过类型和分类来获取列表也文章
    public function getqAppCateList($type,$cate_id,$ids=array(),$page=1,$page_size=0){
        $where="1";
        if($cate_id==22){
            $where.=' and cate_id in (22,23,24,25,26,27,28,29,30,32)';
        }elseif($cate_id == 12){
            $where.=' and cate_id in (13,14,15)';
        }elseif($cate_id>0){
            $where.=' and cate_id ='.$cate_id;
        }
        if($type!='public'&&$type!=""){
            $where.=' and type in ("'.$type.'","public")';
        }
        $order="input_time desc,sort_order desc";
        if(!empty($ids)){
            $str=implode(",",$ids);
            $where.=" and id in ($str)";
            $order="sort_order desc";
        }
        $this->model('news_content')->where($where)->select('id,title,description,cate_id,author,input_time,type')->order("$order");
        if($page&&$page_size){
            return $this->page($page,$page_size)->getPage();
//				$this->page($page,$page_size)->getPage();
//				showTrace();exit;
        }elseif ($page_size) {
            $this->limit($page_size);
        }
        return $this->getAll();
    }


    //通过id获取文章详情
    public function getNews($id){
        $data=$this->model('news_content')->where('id='.$id)->getRow();

        //取出右键导航分类名称
        $data['cate_name']=$this->model('news_cate')->select('cate_name')->where('cate_id='.$data['cate_id'])->getOne();
        //取出上一篇和下一篇
        $data['lastOne']=$this->model('news_content')->where('cate_id='.$data['cate_id'].' and id <'.$id)->select('id,title')->order('id desc')->limit(1)->getRow();
        $data['nextOne']=$this->model('news_content')->where('cate_id='.$data['cate_id'].' and id >'.$id)->select('id,title')->order('id asc')->limit(1)->getRow();
        //取出内链文章
        $id_arr=$this->model('news_content')->where('cate_id='.$data['cate_id'])->select('id')->getAll();
        foreach ($id_arr as $v) {
            $tmp_ids[$v['id']]=$v['id'];
        }
        $tmp_ids=array_rand($tmp_ids,10);
        $id_str=implode(',',$tmp_ids);
        $data['neilian']=$this->model('news_content')->where('id in ('.$id_str.')')->select('id,title')->getAll();
        return $data;
    }

    //更新文章访问量
    public function updatePv($id){
        $pv_arr=$this->model('news_content')->where('id='.$id)->select('pv,true_pv')->getRow();
        $this->model('news_content')->where('id='.$id)->update(array('pv'=>($pv_arr['pv']+1),'true_pv'=>($pv_arr['true_pv']+1)));
        return $pv_arr['pv']+1;
    }
    /**
     * 塑料圈app更新阅读量
     * @Author   zhanpeng
     * @DateTime 2016-11-4 11:32:19
     * @Param
     * @return
     */
    public function updateqAppPv($id){
        //pv默认加200阅读量
        $this->model('news_content')->query('update p2p_news_content set pv=pv+1,true_pv=true_pv+1 where id='.$id);//刷一次算一次页面

    }

    //根据访问量进行排行
    public function charts($type,$cate_id,$keywords='',$limit=10,$day=0){
        if($keywords){
            $data=$this->model('news_content')->where('title like "%'.$keywords.'%" or content like "%'.$keywords.'%"')->select('id,title,content,cate_id,author,input_time,type,pv')->order('sort_order desc,pv  desc')->limit($limit)->getAll();
        }else{
            $where="1";
            if($cate_id==22 && $type!='pvc' && $type!='public'){
                $where.=' and cate_id in (22,23,24,25,26,27,28,32)';
            }elseif($cate_id==22 && $type=='pvc'){
                $where.='and cate_id in (22,23,25,26,27,29,30,32)';
            }elseif($cate_id==22 && $type=='public'){
                $where.=' and cate_id in (22,23,24,25,26,27,28,29,30,32)';
            }elseif($cate_id>0){
                $where.='and cate_id ='.$cate_id;
            }

            if($type!='public'&&$type!=''){
                $where.=' and type in ("'.$type.'","public")';
            }
            if($day>0){
                $now=CORE_TIME;
                $before=strtotime(date("Y-m-d"))-86400*($day-1);
                $where.=" and input_time between $before and $now";
            }
            $data=$this->model('news_content')->where($where)->select('id,title,description,cate_id,author,input_time,type,pv')->order('sort_order desc,pv  desc')->limit($limit)->getAll();
        }
        //文章排行编号从1开始并进行补零
        foreach ($data as $key => $value) {
            $chartsData[sprintf("%02d",$key+1)]=$value;
            $chartsData[sprintf("%02d",$key+1)]['cate_name']=$this->model('news_cate')->select('cate_name')->where('cate_id="'.$value['cate_id'].'"')->getOne();
            //判断是否有前缀
            if(array_search($value['cate_id'], array(1,3,4,13,14,15))===false){
                $chartsData[sprintf("%02d",$key+1)]['prefix']=1;
            }
        }
        return $chartsData;
    }

    //根据关键词获取文章
    public function search($ids){
        $ids = implode(',', $ids);
        return $this->model('news_content')->select('id,author,cate_id,description,title,type,input_time')->where("id in (".$ids.")")->order('input_time desc')->getAll();
    }

    //官网首页5F调用数据
    public function getHomeNews(){
        $arr=array('早盘抢先看'=>'1,2','期刊报告'=>'13,14,15','期货资讯'=>21,'企业动态'=>9,'装置动态'=>11);
        $i=1;
        foreach ($arr as $k => $v) {
            $tmp[$k]=$this->model('news_content')->select('id,title,cate_id,type,input_time')->where('cate_id in ('.$v.')')->order('input_time desc')->limit(10)->getAll();
            foreach ($tmp[$k] as $k2 => $v2) {
                $tmp[$k][$k2]['spell']=$this->model('news_cate')->select('spell')->where('cate_id='.$v2['cate_id'])->getOne();
            }
            $tmp[$k]=array_chunk($tmp[$k], 5);
            $tmp[$k]['num']=$i;
            $i++;

        }
        //p($tmp);exit;
        return $tmp;
    }

    /**
     * 获取下级分类的cate_id
     * @param int $pid
     * @return mixed
     */
    public function getCateSons($pid=0){
        return $this->model("news_cate")->select("cate_id")->where("pid=$pid and status=1")->getCol();
    }

    /**
     * 获取所属分类的名字
     * @param $cate_id
     */
    public function getSubName($cate_id){
        $tmp=$this->model("news_cate")->select("cate_id,cate_name,pid")->where("status=1 and cate_id=$cate_id")->getRow();
        if($tmp['pid']==0) return $tmp['cate_name'];
        if($tmp['pid']>0){
            $tmp2=$this->model("news_cate")->select("cate_id,cate_name,pid")->where("status=1 and cate_id={$tmp['pid']}")->getRow();
            if(!empty($tmp2)) return $tmp2['cate_name'];
        }
    }


    public function getNewsOrderByPv($type,$cate_id,$keywords='',$limit=10,$day=0){
        if($keywords){
            $data=$this->model('news_content')->where('title like "%'.$keywords.'%" or content like "%'.$keywords.'%"')->select('id,title,content,cate_id,author,input_time,type,pv')->order('sort_order desc,pv  desc')->limit($limit)->getAll();
        }else{
            $where= " 1 ";
            if($type!='public'&&$type!=''){
                $where.=' and type in ("'.$type.'","public")';
            }
            $_cates=(array)$this->getCateSons($cate_id);
            if(empty($_cates)){
                $where.='and cate_id ='.$cate_id;
            }else{
                $where.=' and cate_id in ('.implode(',',$_cates).')';
            }
            if($cate_id==22 && $type!='pvc' && $type!='public'){
                $where.=' and cate_id in (22,23,24,25,26,27,28,32)';
            }elseif($cate_id==22 && $type=='pvc'){
                $where.='and cate_id in (22,23,25,26,27,29,30,32)';
            }elseif($cate_id==22 && $type=='public'){
                $where.=' and cate_id in (22,23,24,25,26,27,28,29,30,32)';
            }
            if($day>0){
                $now=CORE_TIME;
                $before=strtotime(date("Y-m-d"))-86400*($day-1);
                $where.=" and input_time between $before and $now";
            }
            return $data=$this->model('news_content')->where($where)->select('id,title,cate_id,input_time,type')->order('sort_order desc,pv  desc')->limit($limit)->getAll();
        }

    }


    //通过类型和分类来获取列表也文章
    public function getQQCateList($type,$cate_id,$ids=array(),$page=1,$page_size=0){
        $where="1";
        if($cate_id==22){
            $where.=' and cate_id in (22,23,24,25,26,27,28,29,30,32)';
        }elseif($cate_id == 12){
            $where.=' and cate_id in (13,14,15)';
        }elseif($cate_id>0){
            $where.=' and cate_id ='.$cate_id;
        }
        if($type!='public'&&$type!=""){
            $where.=' and type in ("'.$type.'","public")';
        }
        $order="input_time desc,sort_order desc";
        if(!empty($ids)){
            $str=implode(",",$ids);
            $where.=" and id in ($str)";
            $order="sort_order desc";
        }
        $this->model('news_content')->where($where)->select('id,title,description,cate_id,content,sm_img')->order("$order");
        if($page&&$page_size){
            return $this->page($page,$page_size)->getPage();
//				$this->page($page,$page_size)->getPage();
//				showTrace();exit;
        }elseif ($page_size) {
            $this->limit($page_size);
        }
        return $this->getAll();
    }


    public function getQQNews($id){
        $data=$this->model('news_content')->select('id,sm_img,title,description,hot,keywords,input_time,type,cate_id')->where('id='.$id.' and status = 1')->getRow();
        if(empty($data)) return false;
        //取出右键导航分类名称
        $data['cate_name']=$this->model('news_cate')->select('cate_name')->where('cate_id='.$data['cate_id'])->getOne();
        return $data;
    }













    }
?>