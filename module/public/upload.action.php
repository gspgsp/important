<?php
/**
 * 图像处理控制器 
 */
class uploadAction extends action {
	private $uptype=''; //上传方式:local,remote
	private $cfg=array(); //上传配置
	private $handle=NULL; //操作句柄
	private $save_path =  array();
	public function __construct(){
		$this->uptype=C('upload_type');//local
		$this->save_path =C('upload_local');
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
			$handle->allowExts = array('jpg', 'gif', 'zip', 'jpeg','pdf','doc','rar','docx','xls','xlsx','png');//上传类型
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
		$this->json_output($data);
		//return $data;
	}

	/*
	 * 上传图片控制器
	 * @access public
	 * @param string $thumb 缩略图规格(80x100,320x400)
	 * @param string $self_path 指定子目录(末尾不带/)
	 * @return array(err,img)
	*/
	public function saveCardImg($self_path='',$type){
		if(empty($_FILES)) return false;
		$handle = $this->handle;
		//补全路径（带后缀）
		if(!empty($self_path) && substr($self_path,-1)!='/'){
			$self_path = $self_path.'/';
		}
		// $handle->maxWidth=400;
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
			$handle->allowExts = array('jpg', 'gif', 'zip', 'jpeg','pdf','doc','rar','docx','xls','xlsx','png');//上传类型
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
		if(empty($data['err'])){
			$file = $data['file'];
			//新用户
			if(!empty($_SESSION['mobile'])){
				$_SESSION[$_SESSION['mobile'].'_CardImg'] = FILE_URL.'/upload/'.$data['file'][0]['savename'];
			}else{
				if($type==1){
					$arr = array(
							'thumbcard'=>FILE_URL.'/upload/'.$data['file'][0]['savename'],
							'update_time'=>CORE_TIME,
					);
				}elseif ($type==2) {
					$arr = array(
							'thumbqq'=>FILE_URL.'/upload/'.$data['file'][0]['savename'],
							'update_time'=>CORE_TIME,
					);
				}
				M('user:contactInfo')->updateContactInfo($_SESSION['userid'],$arr);
			}
			$this->json_output(array('err'=>0,'url'=>FILE_URL.'/upload/'.$data['file'][0]['savename']));
		}else{
			$this->json_output(array('err'=>2,'message'=>$data['err'],'url'=>''));
		}
	}

	/*
	 * 上传图片控制器
	 * @access public
	 * @param string $thumb 缩略图规格(80x100,320x400)
	 * @param string $self_path 指定子目录(末尾不带/)
	 * @param string $user_id 用户id
	 * @return array(err,img)
	*/
	public function saveqAppCardImg($self_path='',$type,$user_id){
		if(empty($_FILES)) $this->json_output(array('err'=>1,'msg'=>'图片为空'));
		$handle = $this->handle;
		//补全路径（带后缀）
		if(!empty($self_path) && substr($self_path,-1)!='/'){
			$self_path = $self_path.'/';
		}
		//$handle->maxWidth=400;
		$data=array('err'=>'','file'=>array());

//		$handle->thumb=true;
//		$handle->thumbSize='200x200';
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
			$handle->allowExts = array('jpg', 'gif', 'zip', 'jpeg','pdf','doc','rar','docx','xls','xlsx','png');//上传类型
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
		//ROOT_PATH.'upload/'

		if(empty($data['err'])){
			$file = $data['file'];
				if($type==1){
					$arr = array(
							'thumbcard'=>FILE_URL.'/upload/'.$data['file'][0]['savename'],
							'update_time'=>CORE_TIME,
					);
				}elseif ($type==2) {
					$ass=$this->save_path['path'].$data['file'][0]['savename'];
					$this->reImg2($ass);
					$arr = array(
							'thumbqq'=>FILE_URL.'/upload/'.$data['file'][0]['savename'],
							'update_time'=>CORE_TIME,
					);
				}
				M('user:contactInfo')->updateContactInfo($user_id,$arr);
			$this->json_output(array('err'=>0,'url'=>FILE_URL.'/upload/'.$data['file'][0]['savename']));
		}else{
			$this->json_output(array('err'=>2,'message'=>$data['err'],'url'=>''));
		}
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

	//处理图片的压缩问题
	private function reImg2($source_path, $target_width=200, $target_height=200){
		$source_info   = getimagesize($source_path);
		$source_width  = $source_info[0];
		$source_height = $source_info[1];
		$source_mime   = $source_info['mime'];
		$source_ratio  = $source_height / $source_width;
		$target_ratio  = $target_height / $target_width;
		if ($source_ratio > $target_ratio){
			$cropped_width  = $source_width;
			$cropped_height = $source_width * $target_ratio;
			$source_x = 0;
			$source_y = ($source_height - $cropped_height) / 2;
		}elseif ($source_ratio < $target_ratio){
			$cropped_width  = $source_height / $target_ratio;
			$cropped_height = $source_height;
			$source_x = ($source_width - $cropped_width) / 2;
			$source_y = 0;
		}else{
			$cropped_width  = $source_width;
			$cropped_height = $source_height;
			$source_x = 0;
			$source_y = 0;
		}
		switch ($source_mime){
			case 'image/gif':
				$source_image = imagecreatefromgif($source_path);
				break;
			case 'image/jpeg':
				$source_image = imagecreatefromjpeg($source_path);
				break;
			case 'image/png':
				$source_image = imagecreatefrompng($source_path);
				break;
			default:
				return false;
				break;
		}
		$target_image  = imagecreatetruecolor($target_width, $target_height);
		$cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);
		imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
		imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
		imagejpeg($target_image,$source_path,90);
		imagedestroy($source_image);
		imagedestroy($target_image);
		imagedestroy($cropped_image);
	}
	
	
}
?>