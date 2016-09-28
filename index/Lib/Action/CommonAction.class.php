<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter
 * @version 1.0.0
 *
 * 功能简介：商户后台基类
 * @author
 * @copyright
 * @time
 * @version 1.0.0
 */
	Load('extend');//插件

	import('ORG.Util.Page');//分页类

	import('ORG.Net.UploadFile');//文件上传类

	class CommonAction extends Action
	{

		/**
		 * 初始化方法
		 *
		 * 参数描述：
		 *
		 *
		 *
		 *
		 * 返回值：
		 *
		 */
		public function _initialize()
		{
			if (!$_SESSION['Rongzi']['user'])
			{
				// die('<meta http-equiv="Content-Type" content="text/html"; charset="utf8">您未登录或登录已过期，点击<a href="'.__APP__.'/Login/login" target="_top">此处</a>重新登录');
				redirect(__APP__.'/Login/login', 0);
			}
			else
			{
				define('MEMBER_ID', $_SESSION['Rongzi']['user']['uid']);

				if (!MEMBER_ID) { redirect(__APP__.'/Login/login', 0); }

				$this -> model = D('Common');

				//查询商户
				$params = array(

					'table_name' => 'member',
					'where' => "status = 1 AND uid = ".MEMBER_ID
				);

				$_SESSION['Rongzi']['user'] = $this -> model -> my_find($params);

			}
		}

		/**
		 * 返回
		 *
		 * 参数描述：
		 *   message
		 *
		 *
		 * 返回值：
		 *
		 */
	    public function _back($message)
	    {
	    	$msg = $message ? $message : '出现错误，请稍后再试。';

	    	die('<meta http-equiv="Content-Type" content="text/html"; charset="utf8"><script language="javascript">alert("' . $msg . '");window.history.back(-1);</script>');
	    }


	    /**
		*[Rongzi] (Beta)2014-~  Crm.
		************************************
		*  接口上传图片封装
		************************************
		* @author:qbx(304151978@qq.com)
		* @time:2014-06-12
		* @version: 1.0.0
		***************参数描述*************
		*   @param width
		*   @param height
		*   @param path
		*   @param prefix
		*
		* 返回值：path图片路径 msg图片名字  status上传状态
		* 使用：$this -> _upload_pic('160,230', '230,121',  "dishes/", 'small_,middle_');
		* 多参数使用 'width1,width2'  , 'height1,height2' , 存放路径 , '第一个图片前缀,第二个图片前缀'
		******************************************************
		* 例子 $pic = $this -> _upload_pic('160,230', '230,121',  "dishes/", 'small_,middle_');
		*$param['dishes_big_pic']=$pic["path"].''.$pic["msg"];
		*$param['dishes_middle_pic']=$pic["path"].'middle_'.$pic["msg"];
		*$param['dishes_small_pic']=$pic["path"].'small_'.$pic["msg"];
		*/
		public function _upload_pic($path='others')
		{

			import('ORG.Net.UploadFile');
			$width = '1000,654,600,130';
			$height = '570,768,400,130';
			$prefix = 'b_,m_,s_,l_';
	        $upload = new UploadFile(); // 实例化上传类
	        $upload->maxSize =10000000; // 设置附件上传大小
	        $upload->savePath = './Uploads/images/' . $path . '/'; // 设置附件上传目录
	        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
	        // $upload->saveRule = 'time';
	        $upload->uploadReplace = true; //是否存在同名文件是否覆盖
	        $upload->thumb = true; //是否对上传文件进行缩略图处理
	        $upload->thumbMaxWidth = $width; //缩略图处理宽度
	        $upload->thumbMaxHeight = $height; //缩略图处理高度
	        $upload->thumbPrefix = $prefix; //缩略图前缀

	        $upload->thumbPath = './Uploads/images/' . $path .'/'; //缩略图保存路径
	        //$upload->thumbRemoveOrigin = true; //上传图片后删除原图片
	        $upload->autoSub = false; //是否使用子目录保存图片
	        $upload->subType = 'date'; //子目录保存规则
	        //$upload->dateFormat = 'Ymd'; //子目录保存规则为date时时间格式

	        if (!$upload->upload()) {// 上传错误提示错误信息
	            return array('msg' => $upload->getErrorMsg(), 'status' => 0);
	        } else {// 上传成功 获取上传文件信息
	            $info = $upload->getUploadFileInfo();

	            $picname = $info[0]['savename'];

	            //$picname = explode('/', $picname);
				//$picname = $picname[1];
	            //$picname = $picname[0] . '/' . $prefix . $picname[1];
	            return array('status' => 1, 'msg' => $picname,'path' => $upload->thumbPath);
	        }
		}


		/**
		*[Rongzi]
		************************************
		* 多个图片上传封装
		************************************
		* @author:qbx(304151978@qq.com)
		* @time:2016-09-23
		* @version: 1.0.0
		***************参数描述*************
		*   @param width
		*   @param height
		*   @param path
		*   @param prefix
		*
		* 返回值：path图片路径 msg图片名字  status上传状态
		* 使用：$this -> _upload_pic('160,230', '230,121',  "dishes/", 'small_,middle_');
		* 多参数使用 'width1,width2'  , 'height1,height2' , 存放路径 , '第一个图片前缀,第二个图片前缀'
		******************************************************
		* 例子 $pic = $this -> _upload_pic('160,230', '230,121',  "dishes/", 'small_,middle_');
		*$param['dishes_big_pic']=$pic["path"].''.$pic["msg"];
		*$param['dishes_middle_pic']=$pic["path"].'middle_'.$pic["msg"];
		*$param['dishes_small_pic']=$pic["path"].'small_'.$pic["msg"];
		*/
		public function _upload_pic_all($path='others')
		{

			import('ORG.Net.UploadFile');
			$width = '1000,654,600,130';
			$height = '570,768,400,130';
			$prefix = 'b_,m_,s_,l_';
	        $upload = new UploadFile(); // 实例化上传类
	        $upload->maxSize =10000000; // 设置附件上传大小
	        $upload->savePath = './Uploads/images/' . $path . '/'; // 设置附件上传目录
	        $upload->allowExts = array('jpg', 'gif', 'png', 'jpeg'); // 设置附件上传类型
	        // $upload->saveRule = 'time';
	        $upload->uploadReplace = true; //是否存在同名文件是否覆盖
	        $upload->thumb = true; //是否对上传文件进行缩略图处理
	        $upload->thumbMaxWidth = $width; //缩略图处理宽度
	        $upload->thumbMaxHeight = $height; //缩略图处理高度
	        $upload->thumbPrefix = $prefix; //缩略图前缀

	        $upload->thumbPath = '../Uploads/images/' . $path .'/'; //缩略图保存路径
	        //$upload->thumbRemoveOrigin = true; //上传图片后删除原图片
	        $upload->autoSub = false; //是否使用子目录保存图片
	        $upload->subType = 'date'; //子目录保存规则
	        //$upload->dateFormat = 'Ymd'; //子目录保存规则为date时时间格式

	        if (!$upload->upload()) {// 上传错误提示错误信息
	            return array('msg' => $upload->getErrorMsg(), 'status' => 0);
	        } else {// 上传成功 获取上传文件信息
	            $info = $upload->getUploadFileInfo();

	            foreach ($info as $key => $value) {
	            	# code...
	            	$infos["{$value['key']}"]['status'] = 1;
	            	$infos["{$value['key']}"]['msg'] = $value['savename'];
	            }

	            $picname = $infos;

	            return $picname;
	            //$picname = $info[0]['savename'];

	            //$picname = explode('/', $picname);
				//$picname = $picname[1];
	            //$picname = $picname[0] . '/' . $prefix . $picname[1];
	            //return array('status' => 1, 'old_name' => $info[0]['name'], 'msg' => $picname,'path' => $upload->thumbPath);
	        }
		}

		//*****************************************内部方法************************************************
		/**
		 * 调用curl发送方法
		 *
		 * 参数描述：
		 *   $xml		返回的字符串
		 *
		 * 返回值：
		 *   有结果		array 结果集
		 */
		function _xml_to_array($xml)
		{
			$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
			if(preg_match_all($reg, $xml, $matches)){
				$count = count($matches[0]);
				for($i = 0; $i < $count; $i++){
				$subxml= $matches[2][$i];
				$key = $matches[1][$i];
					if(preg_match( $reg, $subxml )){
						$arr[$key] = $this->_xml_to_array( $subxml );
					}else{
						$arr[$key] = $subxml;
					}
				}
			}
			return $arr;
		}

		/*
		 * 非空验证
		 *
		 * 参数描述：
		 *   data
		 *
		 *
		 * 返回值：
		 *   jump/data
		 */
	    public function _is_null($data, $msg='请填写必填信息')
	    {
	    	if ($data == '' || $data == NULL || $data == FALSE)
	    	{
	    		$this -> _back($msg);
	    	}
	    	else
	    	{
	    		return $data;
	    	}
	    }

	/**
	************************************
	* 获取汉字首字母
	************************************
	* @author:qbx
	* @time:2014-07-21
	* @version: 1.0.0
	***************功能描述*************
	* @param: str 汉字
	************************************
	*/
	public function getFirstCharter($str){
     if(empty($str)){return '';}
     $fchar=ord($str{0});
     if($fchar>=ord('A')&&$fchar<=ord('z')) return strtoupper($str{0});
     $s1=iconv('UTF-8','gb2312',$str);
     $s2=iconv('gb2312','UTF-8',$s1);
     $s=$s2==$str?$s1:$str;
     $asc=ord($s{0})*256+ord($s{1})-65536;
     if($asc>=-20319&&$asc<=-20284) return 'A';
     if($asc>=-20283&&$asc<=-19776) return 'B';
     if($asc>=-19775&&$asc<=-19219) return 'C';
     if($asc>=-19218&&$asc<=-18711) return 'D';
     if($asc>=-18710&&$asc<=-18527) return 'E';
     if($asc>=-18526&&$asc<=-18240) return 'F';
     if($asc>=-18239&&$asc<=-17923) return 'G';
     if($asc>=-17922&&$asc<=-17418) return 'H';
     if($asc>=-17417&&$asc<=-16475) return 'J';
     if($asc>=-16474&&$asc<=-16213) return 'K';
     if($asc>=-16212&&$asc<=-15641) return 'L';
     if($asc>=-15640&&$asc<=-15166) return 'M';
     if($asc>=-15165&&$asc<=-14923) return 'N';
     if($asc>=-14922&&$asc<=-14915) return 'O';
     if($asc>=-14914&&$asc<=-14631) return 'P';
     if($asc>=-14630&&$asc<=-14150) return 'Q';
     if($asc>=-14149&&$asc<=-14091) return 'R';
     if($asc>=-14090&&$asc<=-13319) return 'S';
     if($asc>=-13318&&$asc<=-12839) return 'T';
     if($asc>=-12838&&$asc<=-12557) return 'W';
     if($asc>=-12556&&$asc<=-11848) return 'X';
     if($asc>=-11847&&$asc<=-11056) return 'Y';
     if($asc>=-11055&&$asc<=-10247) return 'Z';
     return null;
 }
 	/**
	************************************
	* 处理名字显示
	************************************
	* @author:qbx
	* @time:2014-09-15
	* @version: 1.0.0
	***************功能描述*************
	* @param: name 姓名 sex 性别
	************************************
	*/
	public function getDealName($name,$sex){
		if ($this->abslength($name) == 1) {
			if ($sex == 0) {
				# code...
				$sex = mb_convert_encoding("女士", "UTF-8", "GBK");
			} else {
				# code...
				$sex = mb_convert_encoding("先生", "UTF-8", "GBK");
			}

			return "{$name}{$sex}";
		}else{
			return "{$name}";
		}
	}
	/**
	* 可以统计中文字符串长度的函数
	* @param $str 要计算长度的字符串
	* @param $type 计算长度类型，0(默认)表示一个中文算一个字符，1表示一个中文算两个字符
	*
	*/
	public function abslength($str)
	{
		if(empty($str)){
			return 0;
		}
		if(function_exists('mb_strlen')){
			return mb_strlen($str,'utf-8');
		}else {
			preg_match_all("/./u", $str, $ar);
			return count($ar[0]);
		}
	}

	/**
	* 生成uniqid订单号
	*
	*/
	public function get_order_number(){
		return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
	}

	/**
	* 生成uniqid订单号
	*
	*/
	public function get_user_number(){
		return rand(0,9).substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 6).rand(0,9);
	}

	/**
	* 导出excel
	*
	*/
	public function exportExcel($expTitle,$expCellName,$expTableData){

		$xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
		$fileName = $_SESSION['account'].date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
		$cellNum = count($expCellName);
		$dataNum = count($expTableData);
		import("ORG.PHPExcel");
		$objPHPExcel = new PHPExcel();

		$cellName = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ');

		$objPHPExcel->getActiveSheet(0)->mergeCells('A1:'.$cellName[$cellNum-1].'1');//合并单元格
	   // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.'  Export time:'.date('Y-m-d H:i:s'));
		for($i=0;$i<$cellNum;$i++){
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i].'2', $expCellName[$i][1]);
		}
		  // Miscellaneous glyphs, UTF-8
		for($i=0;$i<$dataNum;$i++){
		  for($j=0;$j<$cellNum;$j++){
			$objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j].($i+3), $expTableData[$i][$expCellName[$j][0]]);
		  }
		}

		header('pragma:public');
		header('Content-type:application/vnd.ms-excel;charset=utf-8;name="'.$xlsTitle.'.xls"');
		header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
		$objWriter->save('php://output');
		exit;
	}


	/**
	* 获取当前页面完整URL地址
	*/
	function get_url() {
	   $sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	   $php_self = $_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME'];
	   $path_info = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
	   $relate_url = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.$_SERVER['QUERY_STRING'] : $path_info);
	   return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
	}
}
