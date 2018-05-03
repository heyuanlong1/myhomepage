<?php
include '../../include/header.inc.php';
header("Content-Type: text/html; charset=UTF-8");

writeLog("pay.dun.return","_REQUEST:".json_encode($_REQUEST));
writeLog("pay.dun.return","_POST:".json_encode($_POST));
writeLog("pay.dun.return","_input:".file_get_contents('php://input'));
echo "<h1>支付成功</h1>";
include('../../include/footer.inc.php');