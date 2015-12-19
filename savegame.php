<?php
$openid=$_GET['uid'];
$username=$_GET['username'];
$gamescore=$_GET['gamescore'];

$con = mysql_connect("localhost", "root", "lihb123456");
if (!$con) {
    die('Could not connect: ' . mysql_error());
}
mysql_select_db("test", $con);
$game = mysql_query("select * from v5data where openid='".$openid."';");
$row = mysql_fetch_array($game);
if(empty($row)){
     mysql_query("insert into v5data  (openid,username,gamescore) values ('". $openid . "','".$username."',".$gamescore.");");
}else{
	if($row['gamescore']<=$gamescore){
	 mysql_query("update  v5data  set gamescore=".$gamescore." where openid='".$openid."';");
	}
}
mysql_close($con);
?>