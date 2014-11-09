<?php
	class UploadFile
	{
		//错误代码
		private $errorCode = 0;
		//文件信息
		public $file = array();
		//保存目录
		public $dir = 'public';
		//文件上传最大限制,单位kb
		public $maxSize = 2048;
		
		/**
		 * @param array $file 上传的文件
		 * @param string $temp 保存的目录
		 * return bool
		 */
		public function __construct($file,$dir='temp')
		{
			if(!is_array($file) || empty($file) || !$this->isUploadFile($file['tmp_name']) || trim($file['name']) == '' || $file['size'] == 0)
			{
				$this->file = array();
				$this->errorCode = -1;
				return false;
			}
			else
			{
				$file['size'] = intval($file['size']);
				$file['name'] = trim($file['name']);
				$file['ext'] = $this->fileEXt($file['name']);
				$file['isImage'] = $this->isImageExt($file['ext']);
				$info = $this->getImageInfo($file['tmp_name']);
				$imageInfo = $this->getImageInfo($file['tmp_name']);
				$imageType = $imageInfo['type'];
				$file['fileDir'] = $this->getTargetDir($dir);
				$file['prefix'] = md5(microtime(true)).random('6');
				$file['target'] = $file['fileDir'].'/'.$file['prefix'].'.'.$imageType;
				$file['localTarget'] = SITE_ROOT.$file['target'];
				$this->file = $file;
				$this->errorCode = 0;
				return true;
			}
		}
		/**
		 * 保存文件
		 * return bool
		 */
		public function save()
		{
			if(empty($this->file) || empty($this->file['tmp_name']))
			{
				$this->errorCode = -101;
			}
			elseif(!$this->file['isImage'])
			{
				$this->errorCode = -102;
			}
			elseif(!$this->saveFile($this->file['tmp_name'],$this->file['localTarget']))
			{
				$this->errorCode = -103;
			}
			else
			{
				$this->errorCode = 0;
				return true;
			}
			return false;
		}
		/**
		 * 获取错误代号
		 * @return number
		 */
		public function error()
		{
			return $this->errorCode;
		}
		/**
		 * 获取文件的扩展名
		 * return string
		 */
		public function fileExt($fileName)
		{
			return addslashes(strtolower(substr(strrchr($fileName, '.'), 1, 10)));
		}
		/**
		 * 根据扩展名判断文件是否为图像
		 * @param string $ext
		 * return bool
		 */
		public function isImageExt($ext)
		{
			static $imgExt = array('jpg','jpeg','png','git','giff');
			return in_array($ext,$imgExt) ? 1 : 0;
		}
		/**
	 * 获取图像信息
	 * @param string $target 文件路径
	 * return mixed
	 */
		public function getImageInfo($target)
		{
			$info = array();
			if(!isset($info[$target]))
			{
				$info[$target] = false;
				$ext = self::fileExt($target);
				$isImage = self::isImageExt($ext);
				
				if(!$isImage && $ext != 'tmp')
				{
					return false;
				}
				elseif(!is_readable($target))
				{
					return false;
				}
				elseif($imageInfo = @getimagesize($target))
				{
					$fileSize = floatval(@filesize($target));
					$fileSize = $fileSize / 1024;
					if($fileSize > $this->maxSize)
					{
						return false;
					}
					
					list($width,$height,$type) = !empty($imageInfo) ? $imageInfo : array(0,0,0);
					if($isImage && !in_array($type,array(1,2,3,6,13)))
					{
						return false;
					}
					$imageInfo['type'] = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
					$info[$target] = $imageInfo;
				}
				else
				{
					return false;
				}
			}
			return $info[$target];
		}
		/**
	 * 获取是否充许上传文件
	 * @param string $source 文件路径
	 * return bool
	 */
		public function isUploadFile($source)
		{
			return $source && ($source != 'none')&&(is_Uploaded_file($source));
		}
		/**
	 * 获取保存的路径
	 * @param string $dir 指定的保存目录
	 * return string
	 */
		public function getTargetDir($dir)
		{
			if($dir == 'temp')
			$dir = './public/upload/temp/'.date("Y/m/d");
			else
				$dir = './public/upload/'.$dir.'/'.date("Y/m/d");
	
			makeDir(SITE_ROOT.$dir);
			return $dir;
		}
		/**
		 * 保存文件
		 *@param string $source 源文件路径
	 	 * @param string $target 目录文件路径
	     * @return bool
	     **/
		public function saveFile($source,$target,$isConver = false)
		{
			if(!self::isUploadFile($source))
			{
				$success = false;
			}
			elseif($isConver && $this->converType($source,$target))
			{
				$success = true;
			}
			elseif(copy($source,$target))
			{
				$success = true;
			}
			elseif(function_exists('move_uploaded_file') && @move_upload_file($source,$target))
			{
				$success = true;
			}
			elseif(@is_readable($source) && ($fps = fopen($source,'rb')) && (@$fpt=fopen($target,'wb')))
			{
				while(!feof($fps))
				{
					$s = @fread($fpsm1024*512);
					@fwrite($fpt,$s);
				}
				fclose($fps);
				fclose($fpt);
				$success = true;
			}
			if($success)
			{
				$this->errorCode = 0;
				@chmod($target,0644);
				unlink($source);
			}
			else
			{
				$this->errorCode = 0;
			}
			
			return $success;
		}
		/**
		 * 转换图像的格式
		 * @param string $source 源文件路径
	 	 * @param string $target 目录文件路径
	     * @return bool
		 */
		public function convertType($source,$target)
		{
			$info = self::getImageInfo($source);
			if($info !== false)
			{
				$width = $info[0];
				$height = $info[1];
				$type = $info['type'];
			
				//载入原图
				$createFun = 'imagecreatefrom'.($type == 'jpg' ? 'jpeg' : $type);
				if(!function_exists($createFun))
				{
					$createFun = 'imagecreatefromjpeg';
				}
				$srcImg = $createFun($source);
				if($type == 'gif' || $type == 'png')
				{
					//为图像分配颜色
					imagecolorallocate($srcImg,255,255,255);
				}
				//对jpeg图像进行隔行扫描
				if('jpg' == $type || 'jpeg' == $type)
				{
				 	imageinterlace($srcImg,1);
				}
				if($source == $target)
				{
					@unlink($source);
				}
				//生成图片
				imagefilter($srcImg,IMG_FILTER_CONTRAST,-2); //改变图像的对比度
				imagejpeg($srcImg,$target,IMAGE_CREATE_QUALITY);// JPEG 格式将图像输出
				imagedestroy($srcImg);
			}
			return false;
		}
		//计算缩略图尺寸
		public function scale($info,$maxWidth=200,$maxHeight=50)
		{
				$srcWidth = $info['width'];
				$srcHeight = $info['height'];
				$type = $info['type'];
				if(empty($type))
				{
					return false;
				}
				unset($info);
				if($maxWidth > 0 && $maxHeight > 0)
				{
					//以宽度缩放
					$scale = $maxHeight/$srcHeight;
				}
				elseif($maxWidth == 0)
				{
					$scale = $maxHeight/$srcHeight;
				}
				elseif($maxHeight == 0)
				{
					$scale = $maxWidth/$srcWidth;
				}
				if($scale >=1)
				{
					//超过原图大小不再缩略
					$width = $srcWidth;
					$height = $srcHeight;
				}
				else
				{
					//缩略图尺寸
					$width = intval($srcWidth*$scale);
					$height = intval($srcHeight*$scale);
				}
				
				return array($width,$height,$type);
		}
		public function thumb($image,$maxWidth=200,$maxHeight=50,$gen=0,$interlace=true,$filePath = '')
		{
			$info = $this->get_image_info($image);
			if(false !== $info)
			{
				list($width,$height,$type) = $this->scale($info,$maxWidth,$maxHeight);
				$paths = pathinfo($image);
				$ext = $type;
				if(empty($filePath))
				{
					$thumbname = str_replace('.'.$paths['extension'],'',$image).'_'.$maxWidth.'x'.$maxHeight.'.'.$ext;
				}
				else
				{
					$thumbname = $filePath;
				}
				$thumburl = str_replace(SITE_ROOT,'',$thumbname);
				//载入原图
				$createFun = 'imagecreatefrom'.($type == 'jpg' ? 'jpeg' : $type);
				if(!function_exists($createFun))
				{
					$createFun = 'imagecreatefromjpeg';
				}
				$srcImg = $createFun($image);
				//创建缩略图
				if($type != 'gif' && function_exists('imagecreatetruecolor'))
				{
					$thumbImg = imagecreatetruecolor($width,$height);
				}
				else
				{
					$thumbImg = imagecreate($width,$height);
				}
				
				$x = 0;
				$y = 0;
				if($gen == 1 && $maxWidth > 0 && $maxHeight > 0)
			{
				$resize_ratio = $maxWidth/$maxHeight;
				$src_ratio = $srcWidth/$srcHeight;
				if($src_ratio >= $resize_ratio)
				{
					$x = ($srcWidth - ($resize_ratio * $srcHeight)) / 2;
					$width = ($height * $srcWidth) / $srcHeight;
				}
				else
				{
					$y = ($srcHeight - ( (1 / $resize_ratio) * $srcWidth)) / 2;
					$height = ($width * $srcHeight) / $srcWidth;
				}
			}

            // 复制图片
            if(function_exists("imagecopyresampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height, $info['width'],$info['height']);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, $x, $y, $width, $height,  $info['width'],$info['height']);
            if('gif'==$type || 'png'==$type) {
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
				imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }

            // 对jpeg图形设置隔行扫描
            if('jpg'==$type || 'jpeg'==$type)
				imageinterlace($thumbImg,$interlace);

            // 生成图片
			imagefilter($thumbImg, IMG_FILTER_CONTRAST,-1);

			// 保存图片
			$img_fuc="image".$type;
			if(!function_exists($img_fuc)){
				$img_fuc="imagejpeg";
			}
			$img_fuc($thumbImg,$thumbname);
			
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
           
			return array('url'=>$thumburl,'path'=>$thumbname);
         }
         return false;
		}
		
		public function get_image_info($src){
		$image_info=getimagesize($src);
		
		$result=array();
		$result['width']=$image_info[0];
		$result['height']=$image_info[1];
		
		switch($image_info[2]){
			case 1:
			$result['type']='gif';
			break;
			case 2:
			$result['type']='jpeg';
			break;
			case 3:
			$result['type']='png';
			break;
			case 15:
			$result['type']='wbmp';
			break;
			case 16:
			$result['type']='xmb';
			break;
		}
		return $result;
		
	}
	public function water($source,$water,$alpha=80,$position="4")
    {
        //检查文件是否存在
        if(!file_exists($source)||!file_exists($water))
            return false;

        //图片信息
        $sInfo=$this->get_image_info($source);
        $wInfo=$this->get_image_info($water);

        //如果图片小于水印图片，不生成图片
        if($sInfo["width"] < $wInfo["width"] || $sInfo['height'] < $wInfo['height'])
            return false;
		if(empty($sInfo['type'])){
			return false;
		}
        //建立图像
      
		$sCreateFun="imagecreatefrom".$sInfo['type'];
		if(!function_exists($sCreateFun))
			$sCreateFun = 'imagecreatefromjpeg';
		$sImage=$sCreateFun($source);
	
        $wCreateFun="imagecreatefrom".$wInfo['type'];
		if(!function_exists($wCreateFun))
			$wCreateFun = 'imagecreatefromjpeg';
        $wImage=$wCreateFun($water);

        //设定图像的混色模式
       // imagealphablending($wImage, true);
		
         switch (intval($position))
        {
        	case 0: break;
        	//左上
        	case 1:
        		$posY=0;
		        $posX=0;
		        //生成混合图像
        		imagecopy($sImage, $wImage, $posX, $posY, 0, 0,$wInfo['width'],$wInfo['height']);
        		break;
        	//右上
        	case 2:
        		$posY=0;
		        $posX=$sInfo[0]-$wInfo['width'];
		        //生成混合图像
        		imagecopy($sImage, $wImage, $posX, $posY, 0, 0,$wInfo['width'],$wInfo['height']);
        		break;
        	//左下
        	case 3:
        		$posY=$sInfo['height']-$wInfo['height'];
		        $posX=0;
		        //生成混合图像
        		imagecopy($sImage, $wImage, $posX, $posY, 0, 0,$wInfo['width'],$wInfo['height']);
        		break;
        	//右下
        	case 4:
		        $posY=$sInfo['height']-$wInfo['height'];
		        $posX=$sInfo['width']-$wInfo['width'];
		        //生成混合图像
        		 imagecopy($sImage, $wImage, $posX, $posY, 0, 0,$wInfo['width'],$wInfo['height']);
        		break;
        	//居中
        	case 5:
		        $posY=$sInfo['height']/2-$wInfo['height']/2;
		        $posX=$sInfo['width']/2-$wInfo['width']/2;
		        //生成混合图像
        		imagecopy($sImage, $wImage, $posX, $posY, 0, 0,$wInfo['width'],$wInfo['height']);
        		break;
        }

        //如果没有给出保存文件名，默认为原图像名
        @unlink($source);
        //保存图像
        $imagefun="image".$sInfo['type'];
        if($sInfo['type']=='jpeg'){
        	
        	$imagefun($sImage,$source,IMAGE_CREATE_QUALITY);
        }else{
        	$imagefun($sImage,$source);
        }
		
        imagedestroy($sImage);
		imagedestroy($wImage);
    }
}

if(!function_exists('image_type_to_extension'))
{
	function image_type_to_extension($imagetype)
	{
		if(empty($imagetype))
			return false;

		switch($imagetype)
		{
			case IMAGETYPE_GIF    : return '.gif';
			case IMAGETYPE_JPEG   : return '.jpeg';
			case IMAGETYPE_PNG    : return '.png';
			case IMAGETYPE_SWF    : return '.swf';
			case IMAGETYPE_PSD    : return '.psd';
			case IMAGETYPE_BMP    : return '.bmp';
			case IMAGETYPE_TIFF_II : return '.tiff';
			case IMAGETYPE_TIFF_MM : return '.tiff';
			case IMAGETYPE_JPC    : return '.jpc';
			case IMAGETYPE_JP2    : return '.jp2';
			case IMAGETYPE_JPX    : return '.jpf';
			case IMAGETYPE_JB2    : return '.jb2';
			case IMAGETYPE_SWC    : return '.swc';
			case IMAGETYPE_IFF    : return '.aiff';
			case IMAGETYPE_WBMP   : return '.wbmp';
			case IMAGETYPE_XBM    : return '.xbm';
			default               : return false;
		}
	}
	}		
	
?>