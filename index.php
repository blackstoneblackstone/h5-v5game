<?php
$origin=$_GET['origin'];
if($_COOKIE['v5uid']){
  header("Location: http://www.wexue.top/v5/wxlogin.php?state=".$origin);
}else{
  header("Location: https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxce0069199bab04f5&redirect_uri=".urlencode('http://www.wexue.top/v5/wxlogin.php')."&response_type=code&scope=snsapi_userinfo&state=".$origin."#wechat_redirect");
}
?>