<?php
$operator_name 	= "";			//操作员
$bucket_name 	  = "";			//服务名称
$password       = "";			//密码
//任务数组
	$tasks = array(
	array(
	  "sources" => "/result/abc.zip",  //需要解压的文件
	  "save_as" => "/upyun/cxiang/"        //保存路径
	)
	        );
//1.组装好的参数转换为 JSON 字符串 2.对 JSON 字符串进行 base64 编码处理。
$tasks = base64_encode(json_encode($tasks));
$postdata = array(
		"bucket_name" => $bucket_name,			//服务名称
		"notify_url" => "http://callback.com", 			//测试回调地址
		"app_name"   => "depress",				//任务所使用的云处理程序， 解压的服务名称
		"tasks"	 =>  $tasks					// 处理任务
		);
//生成signature
        if (is_array($postdata)) {
            ksort($postdata);
            $string = '';
            foreach($postdata as $k => $v) {
                if(is_array($v)) {
                    $v = implode('', $v);
                }
                $string .= "$k$v";
            }
            $sign = $operator_name.$string.md5($password);
            $signature = md5($sign);
          }
//认证头信息
	$headers[] = "Authorization: UPYUN ".$operator_name.":".$signature;
	$headers[] = "Date: ".gmdate("D, d M Y H:i:s \G\M\T");
//发送请求
	$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://p0.api.upyun.com/pretreatment/");
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120 );
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postdata ));
        $data = curl_exec($ch);
        print $data;
