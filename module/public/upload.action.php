<?php
/**
 * 图像处理控制器 
 */
class uploadAction extends action {
	private $uptype=''; //上传方式:local,remote
	private $cfg=array(); //上传配置
	private $handle=NULL; //操作句柄
	public function __construct(){
		$this->uptype=C('upload_type');
		$this->cfg=C('upload_'.$this->uptype);
		$_class='upload'.ucfirst($this->uptype);
		$this->handle=new $_class;
	}
	
	/*
	 * 上传图片控制器
     * @access public
	 * @param string $thumb 缩略图规格(80x100,320x400)
	 * @param string $self_path 指定子目录(末尾不带/)
	 * @return array(err,img)
	*/
	public function upload($self_path=''){
		if(empty($_FILES)) return false;
		
		$handle = $this->handle;

	
		//补全路径（带后缀）
		if(!empty($self_path) && substr($self_path,-1)!='/'){
			$self_path = $self_path.'/';
		}
		//$handle->maxWidth=400;
	
		$data=array('err'=>'','file'=>array());
	
		//远程上传
		if($this->uptype=='remote'){
			$handle->remote_url=$this->cfg['url'];			
			$handle->path=$self_path;
			$handle->tmpPath=$this->cfg['path']; //本地临时路径
			if($handle->upload()){
				$result=$handle->getUploadFileInfo();
				$result=$this->formatPath($result,$self_path);
			}else{
				$error=$handle->getErrorMsg();
			}
		}else{	//本地上传
			$handle->savePath=$this->cfg['path'].$self_path; //上传绝对目录
			$handle->allowExts = array('jpg', 'gif', 'zip', 'jpeg','pdf','doc','rar','docx','xls','xlsx');//上传类型
			if($handle->upload()){
				$result=$handle->getUploadFileInfo();
				$result=$this->formatPath($result,$self_path);
			}else{
				$error=$handle->getErrorMsg();
			}
		}
		$data['err'] = $error;
		//$data['file'] = $result[0]['savename'];
		$data['file'] = $result;
		return $data;
	}
	
	
	
	/*
	 * 删除图片
     * @access public
	 * @param mix $img 相对路径图片(string or array)
	 * @return book
	*/
	public function delete($img=array()){
		if(is_string($img)){ //转化为数组
			$img=array($img);
		}
		if($this->uptype=='remote'){
			$handle = $this->handle;
			$handle->remote_url=$this->cfg['url'];	
			$handle->delete($img);
		}else{
			$savepath=$this->cfg['path'];
			foreach($img as $i){
				@unlink($savepath.$i);
			}
		}
		return true;
	}

	
	
	/*
	 * 格式化带子定义目录的图片路径
     * @access private
	 * @param array $img(array(savename=string,thumb=array))
	 * @param string $path 自定义路径
	 * @return $img
	*/
	private function formatPath($img=array(),$path=''){//追加返回结果的图片信息
		if(!empty($path)){
			foreach($img as $k=>$v){
				$img[$k]['savename']= $path.$v['savename'];
				if($v['thumb']){
					foreach($v['thumb'] as $tk=>$tv){
						$img[$k]['thumb'][$tk]=$path.$tv;
					}
				}
			}
		}
		return $img;
	}
	
	
}
?>