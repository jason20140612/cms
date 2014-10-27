<?php
	class FormCheck
	{
		public static  $error = array();
		public static function parse($fs)
		{
			foreach($fs as $key=>$val)
			{
				$value=0;
				switch ($val[1])
				{
					case 'GET#':
						$value = isset($_GET[$val[0]]) ? $_GET[$val[0]] : 0;
						if(!$value)
						{
							continue;
						}
						$value = FormCheck::my_strip_tags_lib($value);
						break;
					case 'GET':
						$value = isset($_GET[$val[0]]) ? $_GET[$val[0]] : 0;
						if(!$value)
						{
							self::$error[] = $val[0].'不能为空';
						}
						break;
					case 'POST#':
						$value = isset($_POST[$val[0]]) ? $_POST[$val[0]] : 0;
						if(!$value)
						{
							continue;
						}
						$value = FormCheck::my_strip_tags_lib($value);
						break;
						break;
					case 'POST':
						$value = isset($_POST[$val[0]]) ? $_POST[$val[0]] : 0;
						if(!$value)
						{
							self::$error[] = $val[0].'不能为空';
						}
						break;
					default:
						self::$error[] = $val[0].'类型不支持';
						break;
				}
				FormCheck::validate($val[0],$value, $val[2], $val[3],isset($val[4]) ? $val[4] : 0);
			}
			if(count(self::$error))
			{
				$msg = '';
				foreach (self::$error as $e)
				{
					$msg .= $e.'\n';
				}
				throw new Exception($msg);
			}
			else
			{
				 return true;
			}
		}
		public static function validate($name,$val,$dataType,$range,$callback=array())
		{
			switch ($dataType)
			{
				case 'i':
					$val = intval($val);
					if(!($val>=$range[0] && $val <= $range[1]))
						self::$error[] = $name.'超出了范围';
					if($callback)
					{
						$func = explode('::', $callback[0]);
						if (! $func[0]::$func[1]($val))
						{
							$msg = $callback[1];
							if(!(true === $msg))
							{
								self::$error[] = $msg;
							}
						}
					}
					break;
				case 's':
					$val = trim($val);
					if(!(strlen($val)>=$range[0] && strlen($val) <= $range[1]))
						self::$error[] = $name.'超出了范围';
					if($callback)
					{
						$func = explode('::', $callback[0]);
						if (! $func[0]::$func[1]($val))
						{
							$msg = $callback[1];
							if(!(true === $msg))
							{
								self::$error[] = $msg;
							}
						}
					}
					break;
				default:
					self::$error[] = $name.'数据类型不支持';
					break;
			}
		}
		private static function my_strip_tags_lib($value)
		{
			$value=htmlspecialchars(strip_tags($value),ENT_QUOTES);
			return $value;
		}
	}
?>