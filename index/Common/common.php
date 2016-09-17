<?php
/*********文件描述*********
 * @last update Sat Jul 27 16:48:54 GMT 2013  
 * @alter zhuangky(zhuangkeyong@sina.cn)
 * @version 1.0.0
 *
 * 功能简介：自定义公用方法
 * @author zhuangky(zhuangkeyong@sina.cn)
 * @copyright 微信海
 * @time Sat Jul 27 16:48:54 GMT 2013
 * @version 1.0.0 
 */


/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
if(!function_exists('ssubstr')){
	function ssubstr($str, $length, $start=0, $charset="utf-8") {
	    if(function_exists("mb_substr"))
	        $slice = mb_substr($str, $start, $length, $charset);
	    elseif(function_exists('iconv_substr')) {
	        $slice = iconv_substr($str,$start,$length,$charset);
	    }else{
	        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	        preg_match_all($re[$charset], $str, $match);
	        $slice = join("",array_slice($match[0], $start, $length));
	    }
	    
	    //自动判断是否加点
	    if($length < ceil(strlen($str)/3))
	    	$slice = $slice.'...';
	    	
	    return $slice;
	}
}


/**
 * 递归创建文件夹
 *
 * 参数描述：
 *   $path		目录
 *
 * 返回值：
 *   return 	bloon 
 */
if(! function_exists('mkdirs'))
{
	function mkdirs($path)
	{
		if(!is_dir($path))
		{
			if(!mkdirs(dirname($path))){
				return false;
			}
			if(!mkdir($path,0777)){
				return false;
			}
		}
		
		return true;
	}
}

/**
 * 公共页面跳转方法
 *
 * 参数描述：
 *   jumpmsg		提示
 *   url			跳转地址
 *   back			滚回上页
 *
 * 返回值：
 *   页面重定向
 */
if(! function_exists('jump'))
{
	function jump($param)
	{
		//变量初始
		$jumpmsg = isset($param['jumpmsg']) ? $param['jumpmsg'] : '操作完成';
		// session('jumpmsg', $jumpmsg);
		$_SESSION['jumpmsg'] = $jumpmsg;
		
		$url = $param['url'] ? $param['url'] : '';
		
		$back = $param['back'] ? $param['back'] : 0;
		
		//判断是否滚回上页
		if(1 == $back)
			die('<script language="javascript">window.history.back(-1);</script>');
		
		//查看是不是没有传url
		if(!$url){
			$pre_url = explode('/',$_SERVER['HTTP_REFERER']);
			
			$url = $pre_url[4].'/'.$pre_url[5];
			
			//拼接参数
			foreach ($pre_url as $k=>$v){
				if($k>5 && $k%2==0)
					$getmsg.='/'.$pre_url[$k].'/'.$pre_url[$k+1];
			}
		}
			
		if(strpos($url,'.php'))
			//重定向
			redirect($url.$getmsg);
		else
			//重定向
			redirect(__APP__.'/'.ltrim($url.$getmsg, '/'));
	}
}

/**
 * 获取字符串长度
 *
 * 参数描述：
 *   STR/目标字符串
 *   
 *   
 *
 * 返回值：
 *   INT/长度
 */
if(! function_exists('get_len'))
{
	function get_len(str $str, $ctrl = 0)
	{
		//长度
		$strlen = strlen($str);

		//改变过的长度
		$over_len = 0;

		for ($i = 0; $i < $strlen; $i++)
		{
	        //判断中英文
	        if(ord(substr($str, $i, 1)) > 127)
	        {  
	            $tmpstr .= substr($str, $i, 3);

	            $over_len += 2;

	            $i += 2;
	        }
	        else
	        {
	            $tmpstr .= substr($str, $i, 1);

	            $over_len += 1;
	        }
	    }

	    // return $over_len;
	    //判断是否控制长度
	    if($ctrl != 0)
	    {
	    	//判断长度是否正确
		    if ($over_len <= $ctrl)
		    {
		    	return $ctrl;
		    }
		    else
		    {
		    	jump(array('jumpmsg' => "数据超出{$ctrl}个字符长度", 'back' => 1));
		    }
		}
		else
		{
			return $over_len;
		}
	}
}

/**
 * 手机号验证
 *
 * 参数描述：
 *   STR/目标字符串
 *   
 *   
 *
 * 返回值：
 *   STR/FALSE
 */
if(! function_exists('is_tel'))
{
	function is_tel($str)
	{
		if (strlen($str) == 11)
		{
			if(preg_match('/^1[3458]\\d{9}$/', $str))
			{
				return $str;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/**
 * 邮箱验证
 *
 * 参数描述：
 *   STR/目标字符串
 *   
 *    
 *
 * 返回值：
 *   STR/FALSE
 */
if(! function_exists('is_email'))
{
	function is_email($str)
	{
		if(preg_match('/^[a-z]([a-z0-9]*[-_\.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[\.][a-z]{2,3}([\.][a-z]{2})?$/i', $str))
		{
			return $str;
		}
		else
		{
			return FALSE;
		}
	}
}
/* End of file common.php */