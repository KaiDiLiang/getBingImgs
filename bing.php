<?php
	include_once('util.php');

	class GetBingImgJson {
		public function GetBingImg() {
			$_date = "time:" . date("H");
			print_r($_date);echo '</br>';
			// if ($_date == 00) {
				$xml_str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?idx=0&n=1');
				// 	$json_str = file_get_contents('http://www.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=en-US');
				$XmlToJsonClass = new XmlToJson;
				$arr = $XmlToJsonClass->Parse($xml_str);
				
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
					$row = mysqli_fetch_array($sqldata);		//获取mysql查询并以数组形式返回结果
					print_r($row);
					mysqli_close($conn);
				}
			return [$imgUrl, $imgCopyright, $imgDate];
			// }
		}
		public function downloadImgs($download_imgUrl, $save_dir = 'images/') {
			if($download_imgUrl) {
				$curl_s = curl_init();
				$con_timeout = 15;
				curl_setopt($curl_s, CURLOPT_URL, $download_imgUrl[0]);
				curl_setopt($curl_s, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl_s, CURLOPT_CONNECTTIMEOUT, $con_timeout);
				$file = curl_exec($curl_s);
				curl_close($curl_s);
				
				$this->saveAsImage($download_imgUrl, $file, $save_dir);
			} else {
				ob_start();
				readfile($download_imgUrl[0]);
				$file = ob_get_contents();
				ob_end_clean();
			}
		}
		private function saveAsImage($download_imgUrl, $file, $save_dir) {
			if(!file_exists($save_dir)&&!mkdir($save_dir,0777,true)) {
				return array("file_name" =>"", "save_path" =>"", "error"=>5);
			}
			$fileName_str = parse_url($download_imgUrl[1]);
			$file_name = explode('(',$fileName_str['path']);
			$res = fopen($save_dir . $file_name[0] . getImgType($download_imgUrl), "a", "utf-8");
			fwrite($res, $file);
			fclose($res);
			unset($file, $imgUrl);
			echo '图片保存成功，并保存图片远端地址到数据库';
		}
	}
	$getBingJson = new GetBingImgJson;
	// echo $getBingJson->GetBingImg()[1];
	$download_imgUrl = $getBingJson->GetBingImg();
	$getBingJson->downloadImgs($download_imgUrl);
?>