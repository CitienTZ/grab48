<?php
//ini_set('display_errors',1);            //错误信息  
//ini_set('display_startup_errors',1);    //php启动错误信息

/* 1 设置跨域 */
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
  exit;
}
/* 2 获取api Abbr */
$f=$_GET['f'];
/* $i=0;
if (isset($_GET['i'])) $i=$_GET['i']; */

/* 3 设置api地址 */
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

/* cache 机制 */
if ($f=="update") {
  /* 获取缓存时间 */
  $timefile = fopen("cachetime.json", "r+");
  $cachetime = (int) fread($timefile,filesize("cachetime.json"));
  fclose($timefile);
  /* 如果缓存时间<24h，直接取缓存 */
  if ( time()-$cachetime < 24*3600 ) {
  /* 取得cache */
  $cachefile = fopen("update.json", "r+");
  echo fread($cachefile,filesize("update.json"));
  fclose($cachefile);
  exit();
  }
}

//var_dump($api);
/* 4 获取post数据 */
$post=file_get_contents("php://input");
//var_dump(file_get_contents("php://input"));
/* 5 获取headers */
$headers=getallheaders();
//var_dump(getallheaders());
/* 6 写入请求header */
$header=array();
array_push($header,'appinfo: {"vendor":"RowB","deviceName":"Row B 10.3","deviceId":"123","appVersion":"6.0.0","appBuild":"1","osType":"android","osVersion":"android 10.3.3","longitude":1.033,"latitude":1.033}');
if (isset($headers['token'])) array_push($header,'token: '.$headers['token']);
array_push($header,'User-Agent: ios');
//var_dump($header);
array_push($header, 'Content-type: application/json');

/* 7 发送请求 */
$ch = curl_init($api[$f]);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //取消SSL验证
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false); //取消SSL验证
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

/* 8 获取结果 */
$result = curl_exec($ch);

/* cache 机制 */
if ($f=="update") {
  /* 写入cache */
  $cachefile = fopen("update.json", "w+");
  fwrite($cachefile,$result);
  fclose($cachefile);

  /* 写入缓存时间 */
  $timefile = fopen("cachetime.json", "w+");
  fwrite($timefile,time());
  fclose($timefile);
}
echo $result;
