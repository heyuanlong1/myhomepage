<?php
/**
 * 客户端请求本接口 获取订单 ， appid等信息
 * Created by PhpStorm.
 * User: s
 * Date: 2017/5/7
 * Time: 18:47
 */
$preorderApi = "http://api.qqqq.cn";//下单接口
$appid = 20000; //商户appid
$appkey = "xxx";//

$amount = 1;
$payway = 'weixin';

if (isset($_REQUEST["amount"])) {
    $amount = $_REQUEST["amount"];
}
if (isset($_REQUEST["payway"])) {
    $payway = $_REQUEST["payway"];
}
$paytype = "wap";//h5, wap , android , ios
if (isset($_REQUEST["paytype"])) {
    $paytype = $_REQUEST["paytype"];
}

$params = array(
    "appid" => $appid,//商户apid
    "amount" => $amount, //价格 分
    "itemname" => "商品名",//商品名
    "ordersn" => "{$appid}_" . time() . mt_rand(1000, 9999),//商户订单号,必须唯一,必须appid_ 开头
    "orderdesc" => "订单描述",//订单描述
    "notifyurl" => "http://api.shanLide.cn/appdemo/paycb.php",//后端异步支付回调地址，改成app自己的通知地址
);
ksort($params);//key a-z 排序
$signstr = join($params, "|") . "|" . $appkey;//把值拼接好再拼接上appkey
$sign = md5($signstr);

$params["sign"] = $sign;//签名
$params["returnurl"] = "http://baidu.com";//web前端同步跳转地址
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

