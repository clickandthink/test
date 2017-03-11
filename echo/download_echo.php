<?php 
error_reporting(0);
$link = $_POST['link']."?".time();
$filename = $_POST['filename']!==''?$_POST['filename'].".mp3":md5(time()).".mp3";
$filename = iconv("UTF-8","GBK",$filename);
$dir = "F:".DIRECTORY_SEPARATOR."EchoMusic".DIRECTORY_SEPARATOR;
$f = fopen($dir.$filename,"xb");
if(!$f){
	echo "repeat";
	exit;
}
set_time_limit(5*60); 
$file = file_get_contents($link);
if(fwrite($f,$file)){
	echo "success";
}else{
	echo "fail";
}
fclose($f);

