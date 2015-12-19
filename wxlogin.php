<?php
header("Content-type: text/html; charset=utf-8"); 
$openid='';
$username='';
$img='';
if($_COOKIE['v5uid']){
$openid=$_COOKIE['v5uid'];
$username=$_COOKIE['v5username'];
$img=$_COOKIE['v5img'];
 
}else{
$code = $_GET['code'];
$state = $_GET['state'];
$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=wxce0069199bab04f5&secret=5c7224c72e602a57eaa431556391c7a6&code=' . $code . '&grant_type=authorization_code';
$result = null;
try {
    $result = curlGet($url);
    $obj = json_decode($result);
    $getInfoUrl = "https://api.weixin.qq.com/sns/userinfo?access_token=" . $obj->access_token . "&openid=" . $obj->openid;
    //微信返回值
    $userObj = json_decode(curlGet($getInfoUrl));
} catch (Exception $e) {
    echo $e->getTraceAsString();

}
//echo $userObj->openid;die;
//echo $userObj->openid;die;
$openid=$userObj->openid;
$username=urlencode(str_replace(array("'", "\\"), array(''), $userObj->nickname));
$img=$userObj->headimgurl;
setcookie('v5uid',$userObj->openid,time()+3*24*60*60);
//setcookie('v5uid',"18612055774",time()+3*24*60*60);
setcookie('v5username',$username,time()+3*24*60*60);
setcookie('v5img',$img,time()+3*24*60*60);
//header("Location: http://wx.widalian.com/v5");
}

function curlGet($url, $method = 'get', $data = '')
{
    $ch = curl_init();
    $header = "Accept-Charset: utf-8";
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, strtoupper($method));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $temp = curl_exec($ch);
    return $temp;
}

$origin=$_GET['state'];
$textstate="";
if(!empty($origin)&&$origin!='undefined'){
$con = mysql_connect("localhost", "root", "lihb123456");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("test", $con);
$game = mysql_query("select * from v5data where openid='".$origin."';");
$row = mysql_fetch_array($game);
if(!strstr($row['friend'],$openid)){
     mysql_query("update  v5data  set sharescore=".($row['sharescore']+10).", friend='".$row['friend'].";".$openid."' where openid='".$origin."';");
     $textstate="你为好友<font color=orange>".urldecode($row['username'])."</font>加了10分！";
}else{
    $textstate="你已经为<font color=orange>".urldecode($row['username'])."</font>加了10分啦！";
}
mysql_close($con);
}

$con = mysql_connect("localhost", "root", "lihb123456");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("test", $con);
$game = mysql_query("select * from v5data where openid='".$openid."';");
$row = mysql_fetch_array($game);
$gamescore=0;
$sharescore=0;
if(!empty($row)){
    $gamescore=$row['gamescore'];
    $sharescore=$row['sharescore'];
    $username=$row['username'];
}else{
    mysql_query("insert into v5data  (openid,username) values ('". $openid . "','".$username."');");
    //echo "insert into v5data  (openid,username) values ('". $openid . "','".$username."');";die;
}
mysql_close($con); 
?>


<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <title>
        大王大王V5  五周年庆
    </title>
    <script type="text/javascript" src="http://tajs.qq.com/h5?sId=500000492" charset="UTF-8"></script>
</head>
<body style="text-align: center;background-image:url('login.png');background-size:100% 100%;">
<div style="margin-right: auto;margin-right: auto;height: 100%">
<img src="<?php echo $img?>" style="width:100px;margin-top:30px;border:solid #ffffff 2px;">
<br>
<br>
<div class="text" style="color:white;font-size:16px;">

    <?php
    echo "<div style='font-size:18px'>".$textstate."</div>";
if(($gamescore+$sharescore)>=200){
   echo "你的好友为你贡献了<font color=green>".$sharescore."</font>分，你在游戏中赢得了<font color=green>".$gamescore."</font>分，恭喜你将获得猴子玩偶一只，数量有限，先到先得，快去领吧。";
}else{
   echo "你的好友为你贡献了<font color=green>".$sharescore."</font>分，你在游戏中赢得了<font color=green>".$gamescore."</font>分，你还有<font color=black>".(200-($gamescore+$sharescore))."</font>分，就有机会获得猴子玩偶一只啦，再接再厉啊！";
}?>
</div>
<div style="margin-top:20px">
<a href="http://www.wexue.top/v5/v5.html"><img src="btnlogin.png" style="width:200px"></a>
</div>
<div style="font-size:14px">
<p>兑奖时间： 12月11日（星期五）—12月13日（星期日）每天早上十点开始，日限100只先到先得</p>

<p>兑奖地点：大兴王府井百货商场一层北门</p>
</div>
</div>


</body>
</html>