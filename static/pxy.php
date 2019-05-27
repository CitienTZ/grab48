<?php
//ini_set('display_errors',1);            //错误信息  
//ini_set('display_startup_errors',1);    //php启动错误信息
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
$f=$_GET['f'];
$i=0;
if (isset($_GET['i'])) $i=$_GET['i'];
//var_dump($i);
$api=[
  "update"=> "https://pocketapi.48.cn/user/api/v1/client/update/group_team_star",
  "livelist"=> "https://pocketapi.48.cn/live/api/v1/live/getLiveList",
  "openlivelist"=> "https://pocketapi.48.cn/live/api/v1/live/getOpenLiveList",
  "liveone"=> "https://pocketapi.48.cn/live/api/v1/live/getLiveOne",
  "openliveone"=> "https://pocketapi.48.cn/live/api/v1/live/getOpenLiveOne",
  "roomid"=> "https://pocketapi.48.cn/im/api/v1/im/room/info/type/source",
  "roomlio"=> "https://pocketapi.48.cn/im/api/v1/chatroom/msg/list/homeowner",
  "roomlia"=> "https://pocketapi.48.cn/im/api/v1/chatroom/msg/list/all",
  "login"=> "https://pocketapi.48.cn/user/api/v1/login/app/mobile",
  "checkin"=> "https://pocketapi.48.cn/user/api/v1/checkin",
  "userhome"=> "https://pocketapi.48.cn/user/api/v1/user/info/home"
];
//var_dump($api);
$post=file_get_contents("php://input");
//var_dump(file_get_contents("php://input"));
$headers=getallheaders();
//var_dump(getallheaders());
$header=array();
//if ($f=='login') array_push($header,'appinfo: {"vendor":"RowB","deviceName":"Row B 10.3","deviceId":"123","appVersion":"6.0.0","appBuild":"1","osType":"android","osVersion":"android 10.3.3","longitude":1.033,"latitude":1.033}');
if (isset($headers['token'])) array_push($header,'token: '.$headers['token']);
array_push($header, 'Content-type: application/json');
$ch = curl_init($api[$f]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //取消SSL验证
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false); //取消SSL验证
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
$result = curl_exec($ch);
echo $result;
?>