<?php
class UpLoadModel{
	//错误信息
	private $error="";
	//表单名
	const inputName='filedata';
	//最大上传大小 默认1M
	const maxAttachSize=1048576;
	
	/**
	 * 上传文件的入口
	 */
	public function Upload(){
		//拿到表单中的文件
		$upfile=@$_FILES[self::inputName];
		$msg="''";
		//判断它
		if($this->check($upfile)){
			$url=$this->saveImg($upfile);
			$msg="'$url'";
		}
		return "{'err':'".$this->jsonString($this->error)."','msg':".$msg."}";
	}
	/**
	 * 检查文件的类型和大小
	 * @param object $file
	 * @return boolean	返回文件有没有符合规范
	 */
	private function check($file){
		$Type=$file["type"];
		
		//判断已有的错误类型
		if(!empty($file['error'])){
			$errorType = $file['error'];
			switch($upfile['error'])
			{
				case '1': $err = '文件大小超过了网站定义的文件上传最大值';
					break;
				case '2': $err = '文件大小超过了HTML定义的文件上传最大值';
					break;
				case '3': $err = '文件上传不完全';
					break;
				case '4': $err = '无文件上传';
					break;
				case '6': $err = '缺少临时文件夹';
					break;
				case '7': $err = '写文件失败';
					break;
				case '8': $err = '上传被其它扩展中断';
					break;
				default: $err = '无有效错误代码';
			}
			$this->error=$err;
		}
		//判断文件类型
		else if (!(
				$Type == "image/gif" ||
				$Type == "image/jpeg" ||
				$Type == "image/pjpeg" ||
				$Type == "image/png"
			))
		{
			$this->error="您上传的后缀名必须为gif,jpeg,pjpeg,png";
			return false;
		//判断文件大小
		}
		else if($file["size"]>$this::maxAttachSize){
			$this->error="您上传的文件最大不能超过".$this->formatBytes($this::maxAttachSize);
		}
		
		return $this->error=="";
	}

	/**
	 * 保存文件
	 * @param object $file 上传文件
	 * @return string 返回文件地址
	 */
	private function saveImg($file){
		$arr=explode(".", $file["name"]);
		$extension=$arr[count($arr)-1];
		$newFile="Public/upload/".date("YmdHis").mt_rand(1000,9999).'.'.$extension;
		move_uploaded_file($file["tmp_name"],$newFile);
		return __APP__."/".$newFile;
		
		/* $ip=require 'Conf/Fdfsconfig.php';
		$domain=require "Conf/domainConf.php";
		$server = fastdfs_connect_server($ip[1], '22122');
		if($server==false){
			$server = fastdfs_connect_server($ip[2], '22122');
		}
		$tracker = fastdfs_tracker_get_connection();
		$storage = fastdfs_tracker_query_storage_store();
		$file_info = fastdfs_storage_upload_by_filename($file["tmp_name"], null, array(), null, $tracker, $storage);
		fastdfs_tracker_close_all_connections();
		return $domain['domain']."/Img/img?url=/".$file_info['group_name']."/".$file_info['filename']; */
	}
	/**
	* 得到一个对象的json格式
	* @param string 内容
	* @return mixed 对象的json格式
	*/
	private function jsonString($str)
	{
		return preg_replace("/([\\\\\/'])/",'\\\$1',$str);
	}
	/**
	 * 得到文件大小
	 * @param int $bytes 字节数
	 * @return string 返回文件大小
	 */
	private function formatBytes($bytes) {
		if($bytes >= 1073741824) {
			$bytes = round($bytes / 1073741824 * 100) / 100 . 'GB';
		} elseif($bytes >= 1048576) {
			$bytes = round($bytes / 1048576 * 100) / 100 . 'MB';
		} elseif($bytes >= 1024) {
			$bytes = round($bytes / 1024 * 100) / 100 . 'KB';
		} else {
			$bytes = $bytes . 'Bytes';
		}
		return $bytes;
	}
}
$model=new UpLoadModel();
$model->Upload();
?>