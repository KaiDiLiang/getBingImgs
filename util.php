<?php
	/**
	 * xml转换json/array类
	 * @author kai930322@outlook.com
	 * @param	$xml_str
	 * @return arr_str
	 */
	class XmlToJson {
		public function Parse($xml_str) {
			$replace_str = str_replace(array('\n', '\r', '\t'), '', $xml_str);
			$str = trim(str_replace('"', "'", $replace_str));
			$simpleXml = simplexml_load_string($str);	// 转换形式良好的XML字符串为 SimpleXMLElement对象
			$json_str = json_encode($simpleXml);	// SimpleXMLElement对象转json
			$arr_str = json_decode($json_str,1);	// json转数组
			return $arr_str;
		} 
	}

	/**
	 * 正则匹配字符
	 * @param $download_imgUrl
	 * @return $matches[1]
	 */
	function getImgType($download_imgUrl) {
		/**
		 * 该写法匹配了所有的标签
		 * $patt = "/<[^>]+>(.*)<\/[^>]+>/U";
		 * preg_match_all($patt, $str, $res);
		 * print_r($res[0][3]);
		 */
		// $patt = "/<url>.+<\/url>/";
		// preg_match_all($patt, $str, $res);
		// $imgUrl = 'http://cn.bing.com' . $res[0][0];
		// $imgDate = strtotime(date('yy-m-d H:i:s'));	时间转时间戳
		$patt = "/\w(\.jpg|\.jpeg|\.png|\.bmp|\.git)/i";
		preg_match($patt, $download_imgUrl[0], $matches);
		return $matches[1];
	}
?>