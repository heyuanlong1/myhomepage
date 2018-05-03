<?php
/**
 * Created by PhpStorm.
 * User: s
 * Date: 2017/5/7
 * Time: 18:56
 */

//http://localhost/vserver/appdemo/paycb.php?aid=10000&billid=203351&cent=100&itemname=%E5%A6%82%E6%84%8F%E9%87%91%E7%AE%8D%E6%A3%92&oid=10000_14943203325135&orderdesc=%E5%8F%AF%E5%A4%A7%E5%8F%AF%E5%B0%8F%E4%BC%B8%E7%BC%A9%E8%87%AA%E5%A6%82&sign=8331cdb12524fb849ba4de4708252c1d&channel=1&ext=&paytime=2017-05-07%2017:08:05
$appkey = "xxx";

$params = array(
    "appid" => $_REQUEST["appid"], "amount" => $_REQUEST["amount"], "itemname" => $_REQUEST["itemname"],
    "ordersn" => $_REQUEST["ordersn"], "orderdesc" => $_REQUEST["orderdesc"], "serialno" => $_REQUEST["serialno"]
);
//签名顺序
//amount|appid|itemname| orderdesc| ordersn| serialno |appkey 
ksort($params);
$signstr = join($params, "|") . "|" . $appkey;//把值拼接好再拼接上appkey
//拼接的字符串
//10000|203351|100|如意金箍棒|10000_14943203325135|可大可小伸缩自如|WEPQgf22dGk7376tF0VFzgs5TDCo11DH
$sign = md5($signstr);

if ($sign != $_REQUEST["sign"]) {
    exit("invalid sign");
}

//app服务器处理相关事务 TODO

exit("success");