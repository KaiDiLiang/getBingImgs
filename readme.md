#### mysql:
1.**链接正常，无法正确插值**
如果mysql可以成功链接，但是数值无法正确显示插入，那么使用`mysqli_error()`返回错误
```
        // $conn为sql链接语句
        $sqldata = mysqli_query($conn,$sql);
        if ($sqlData) {
            echo '插入成功';
        } else {
            echo mysqli_error($conn);
        }
```

2.**php7.0操作Mysql要改用new mysqli(), mysqli_query(), mysqli_close(), mysqli_fetch_aray()**
```
        $conn = new mysqli("localhost", "root", "root", "bingimgs_db");
        $sql = 'SELECT * FROM bingimgs_db';
        $sqldata = mysqli_query($conn,$sql);
        $row = mysqli_fetch_array($sqldata);
        print_r($row);
        mysqli_close($conn);
```

---
#### php的正则：
1.**匹配所有<>标签**
```
        $str = file_get_contents('http://cn.bing.com/HPImageArchive.aspx?idx=0&n=1');
	
    	// 该写法匹配了所有的标签
		    $patt = "/<[^>]+>(.*)<\/[^>]+>/U";
		    preg_match_all($patt, $str, $res);
		    print_r($res[0][3]);
```

2.**匹配<url></url>标签之间的内容** 
```
		    $patt = "/<url>.+<\/url>/";
		    preg_match_all($patt, $str, $res);
		    $imgUrl = 'http://cn.bing.com' . $res[0][0];
		    print_r($imgUrl);
```

---
#### php:
1.**时间转时间戳**
```
            $imgDate = strtotime(date('yy-m-d H:i:s'));
```
