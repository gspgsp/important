<?php
/**
 * 系统设置
 */
class sysUploadAction extends adminBaseAction {
	public function __init(){
		$this->debug = false;
	}

	/**
	 * 上传图片
	 * @access public
	 * @return html
	 */
	public function images(){
		$this->is_ajax=true; //指定为Ajax输出
		set_time_limit(0);

		//取得模块的缩小尺寸规格
		$model=sget('model','s');
		$sys=M('system:setting')->getSetting();
		$thumbs='';
		if(isset($sys['model_img'][$model])){
			$thumbs=$sys['model_img'][$model]['string'];
		}
		//取出静态页面直接传过来的缩略图尺寸
		$thumb_size=sget('thumb_size','s');
		if(empty($thumbs)&& !empty($thumb_size)){
			$thumbs=$thumb_size;
		}
		$result=A('public:image')->upload($thumbs);

		$from=sget('from','s'); //来源
		if(empty($result['err'])){
			if($from=='kind'){
				$this->json_output(array('error'=>0,'message'=>'','url'=>C('TEMP_REPLACE.__UPLOAD__').'/'.$result['img']));
			}else{
				if(empty($result['sm_img'])){
					$this->success($result['img']);
				}else{
					$this->success($result);
				}

			}
		}else{
			if($from=='kind'){
				$this->json_output(array('error'=>1,'message'=>$result['err'],'url'=>''));
			}else{
				$this->error($result['err']);
			}
		}
	}

	/**
	 * 上传文件
	 * @access public
	 * @return html
	 */
	public function upload(){
		$this->is_ajax=true; //指定为Ajax输出
		set_time_limit(0);
		//$savePath = "fileContract";
		$result=A('public:upload')->upload($savePath);

		$from=sget('from','s'); //来源
		if(empty($result['err'])){
			$file = $result['file'];
			$att = array(
					'name'=> $file[0]['name'],
					'file_url'=> $file[0]['savename'],
					'file_exts'=> $file[0]['type'],
					'file_size'=> $file[0]['size'],
					'input_time'=> CORE_TIME,
				);
			M('system:attachment')->addAtt($att);
			$attId = M('system:attachment')->getLastID();

			if($from=='kind'){
				$this->json_output(array('error'=>0,'message'=>'','url'=>C('TEMP_REPLACE.__UPLOAD__').'/'.$file[0]['savename']));
				//$this->json_output(array('error'=>0,'message'=>'','attId'=>$attId,'url'=>APP_URL.'/upload/'.$result['file']));
			}else{
				//$this->success($result['file']);
				$this->json_output(array('error'=>0,'attId'=>$attId,'url'=>$file[0]['savename']));
			}
		}else{
			if($from=='kind'){
				$this->json_output(array('error'=>1,'message'=>$result['err'],'url'=>''));
			}else{
				$this->error($result['err']);
			}
		}
	}
}
?>
