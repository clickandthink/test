<?php 
header("Content-type:text/html;charset=utf-8");

// function getDown(){
// 	$mime = 'application/force-download'; 
// 	header('Pragma: public');     // required 
// 	header('Expires: 0');        // no cache 
// 	header('Cache-Control: must-revalidate, post-check=0, pre-check=0'); 
// 	header('Cache-Control: private',false); 
// 	header('Content-Type: '.$mime); 
// 	header('Content-Disposition: attachment; filename="'.basename($file_name).'"'); 
// 	header('Content-Transfer-Encoding: binary'); 
// 	header('Connection: close');
// }

@$link = $_GET['link'];
$ch = curl_init(); 
$timeout = 5; 
curl_setopt($ch, CURLOPT_URL, $link); 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout); 
//在需要用户检测的网页里需要增加下面两行 
//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY); 
//curl_setopt($ch, CURLOPT_USERPWD, US_NAME.":".US_PWD); 
function getContent($first,$second,$str,$bool = false){
	$offset = strlen($first);
	$first = stripos($str,$first);
	if($bool){
		$second = stripos($str,$second,intval($first));
	}else{
		$second = stripos($str,$second);
	}
	return substr($str,$first+$offset,$second-$first-$offset+1);
}
$contents = curl_exec($ch); 
curl_close($ch); 
$title = trim(getContent("<title>","</title>",$contents)," <");
$findf = "var page_sound_obj = ";
$finds = "};";
$res = getContent($findf,$finds,$contents,true);
$array = json_decode($res,true);
$mp3_link = substr($array['source'],0,stripos($array['source'],"?"));

 ?>
 <!DOCTYPE html>
 <html>
 <head>
 	<title>下载 echo回声 音乐</title>
 </head>
 <script type="text/javascript">
 	function xmlhttp(link){
		var xmlhttp;
		var rs;
		if (window.XMLHttpRequest)
		  {// code for IE7+, Firefox, Chrome, Opera, Safari
		  xmlhttp=new XMLHttpRequest();
		  }
		else
		  {// code for IE6, IE5
		  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		  }
		xmlhttp.onreadystatechange=function()
		  {
		  if (xmlhttp.readyState==4 && xmlhttp.status==200)
		    {
		     rs = xmlhttp.responseText;
		    }
		  }
		xmlhttp.open("POST","download_echo.php",false);
		xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		xmlhttp.send(link);
		return rs;
	}
 	// function getPrev(e){
 	// 	var p = e.parentNode.childNodes;
 	// 	for(var i=0;i<p.length;i++){
 	// 		if(p[i].nodeName=="#text" && !/\s/.test(p[i].nodeValue)){
 	// 			removeChild(p[i]);
 	// 			if(p[i]==e){
 	// 				return p[i+1];
 	// 			}
 	// 		}
 	// 	}
 	// }
 </script>
 <body>
 <h1>echo回声 音乐下载</h1>
 <br>

<form>
	<h4>回声地址:</h4>
 	<input size="100" type="href" name="link">
 	<input type="submit" value="提交">
</form>
<br>
<h4>mp3地址：</h4>
<input type="text" size="100" value='<?php echo $mp3_link ?>' name="">
<button id="copy" data-link="<?php echo $mp3_link?>">复制</button>
<h4>保存文件名：</h4>
<input type="text" name="filename" size=100 value='<?php echo $title?>'>
<button onclick="getDown(this)" data-link="<?php echo $mp3_link ?>">下载</button>
<script type="text/javascript">
	getDown = function(e){
		// alert(1);
		var link = "link="+e.getAttribute("data-link");
		var fileName = "filename="+document.getElementsByName("filename")[0].value;
		var rs = xmlhttp(link+'&'+fileName);
		if(rs=="repeat"){
			alert("文件名重复");
		}else if(rs=="success"){
			alert("下载成功");
		}else if(rs=="fail"){
			alert("下载失败");
		}
	}
	document.getElementById("copy").onclick=function(){
			var clipBoardContent = this.getAttribute("data-link");
			console.log(clipBoardContent);
		 	window.clipboardData.setData("Text",clipBoardContent);
		 	console.log(window.ClipboardEvent)
	}
</script>

 </body>
 </html>

