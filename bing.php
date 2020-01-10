<?php
header("content-type:text/html;charset:utf-8");
	$_date = "time:" . date("H");
	print_r($_date);echo '</br>';
	// if ($_date === 00) {
		$xml_str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?idx=0&n=1');
		// $str = file_get_contents('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-US');
		class XmlToJson {
			public function Parse($xml_str) {
				$replace_str = str_replace(array('\n', '\r', '\t'), '', $xml_str);
				$str = trim(str_replace('"', "'", $replace_str));
				$simpleXml = simplexml_load_string($str);	// 转换形式良好的XML字符串为 SimpleXMLElement对象
				$json_str1 = json_encode($simpleXml);
				$json_str = json_decode($json_str1,1);	// json转数组
				return $json_str;
			} 
		}
		$XmlToJsonClass = new XmlToJson;
		$arr = $XmlToJsonClass->Parse($xml_str);
		// var_dump($arr['image']['fullstartdate']);
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
		
		$imgUrl = "http://cn.bing.com" . $arr['image']['url'];
		$imgDate = $arr['image']['fullstartdate'];
		$imgCopyright = $arr['image']['copyright'];
		if ($imgUrl) {
			$conn = new mysqli("localhost", "root", "root", "bingimgs_db");
			if($conn == false) {
				die("错误：" . $conn->connect_error);
			} else {
				echo '连接成功';
			}
			$conn->query("set names UTF-8");
			$sql = "INSERT INTO bingimgs_base(imgUrl, imgDate, imgCopyright) VALUES ('$imgUrl', '$imgDate', '$imgCopyright')";
			// on DUPLICATE KEY UPDATE imgDate = value('$imgDate')";
			$sqldata = mysqli_query($conn,$sql);
			if ($sqlData) {
				echo '插入成功';
			} else {
				echo mysqli_error($conn);
			}
			// $row = mysqli_fetch_array($sqldata);		//获取mysql查询并以数组形式返回结果
			// print_r($row);
			mysqli_close($conn);
		}
	// }
?>