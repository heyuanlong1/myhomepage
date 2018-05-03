<?php
/**

 */
include '../../include/header.inc.php';
if(isset($_GET['userid'])){
    $user=$db->GetRow("select * from user where userid='".((int)$_GET['userid'])."'");
}else{
    $user=checklogin();
}
if(!$user){
    include $app_path.'include/footer.inc.php';
    exit('请先登录');
}


$preorderApi = "http://api.qzxczs.cn";//下单接口
$appid = 20305; //商户appid
$appkey = "e8199b96d5c4542593c1284fe0991305";//

$money = (int)$_GET['p3_Amt'];
$payway = 'weixin';
$paytype = "wap";//h5, wap , android , ios


$amount = $money;
$channel=30;
$reduce=1;
$balanceadd=$money*RMB_XNB*$reduce;
$orderid=payInsertOrders($user,$money,$channel,$balanceadd);
$trade_no = sprintf("%s%05d",date(Ymd),$orderid);




$params = array(
    "appid" => $appid,//商户apid
    "amount" => $amount, //价格 分
    "itemname" => "商品名",//商品名
    "ordersn" => "{$appid}_" . $trade_no,//商户订单号,必须唯一,必须appid_ 开头
    "orderdesc" => "订单描述",//订单描述
    "notifyurl" => _API_URL_.'guofu/notify.php',//后端异步支付回调地址，改成app自己的通知地址
);
ksort($params);//key a-z 排序
$signstr = join($params, "|") . "|" . $appkey;//把值拼接好再拼接上appkey
$sign = md5($signstr);

$params["sign"] = $sign;//签名
$params["returnurl"] =  _API_URL_.'guofu/return.php';//web前端同步跳转地址
$params["payway"] = $payway;//支付类型 ，不填表示app支付，可指定类型
$params["ext"] = "";//app扩展参数
$params["paytype"] = "$paytype";//付费方式

$url = $preorderApi . "?" . http_build_query($params);
echo $url;
echo "<p><hr/></p>";
$context = stream_context_create(array('http'=>array('ignore_errors'=>true)));
$ret = file_get_contents($url, FALSE, $context);
//$ret = file_get_contents($url);

/*----微信支付----*/
echo "$ret";
echo "<p><hr/></p>";
$data = json_decode($ret, true);

if ($data["status"] == 1) {
	/*微信公众号 wap支付*/
	echo "<script>";
	echo "window.location.href='".$data['data']."'";
	echo "</script>";
} else {
    exit($data["msg"]);
}

