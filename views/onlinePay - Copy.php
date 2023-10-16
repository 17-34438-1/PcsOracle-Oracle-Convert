<?php

$dt=date('Y-m-d');
$ref=$requst_id."_".$flag;
$post_data = '{
"AccessUser": {
    "userName" : "bdtaxUser2014",
    "password" : "duUserPayment2014"
},
"strUserId" : "bdtaxUser2014",
"strPassKey": "duUserPayment2014",
"strRequestId": "'.$requst_Id.'",
"strAmount": "'.$payAmt.'",
"strTranDate": "'.$dt.'",
"strAccounts": "0002634271324"
}';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/GetSessionKey");
//added
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($ch, CURLOPT_POST, 1 );


//added

curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC

 //$result = curl_exec($ch);
//curl_close($ch);
//echo $result; 
//var_dump($result);
 

$content = curl_exec($ch);

$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($ch))) {
	curl_close( $ch);
	$sessionData = $content;
} else {
	curl_close( $ch);
	echo "FAILED TO CONNECT  API";
	exit;
}
 $data = json_decode($sessionData, true );
//echo "<br>";
$dataPart = explode('"',$data);
//echo $dataPart[3];
//echo "<br>";
// echo $sessionData->scretKey;
// print_r($sessionData);
// var_dump($scretKey);
// echo $scretKey->scretKey;
// echo "<br>";
// echo $sessionData[1];
// echo "<br>";
 
  $skey=$dataPart[3];
 /*for($i=13; $i<109; $i++)
 {
	 $skey.=$data[$i];
 }
 echo  $skey;  
 */
/* 	 if($flag == 1)
	 {
		  $RefTranNo=$trucVisitId."_".$assignmentType."_".$flag;
	 }
	else if($flag == 1)
	{
		$RefTranNo=$cont."_".$rot."_".$assignmentType."_".$flag;
	} */
	
  $cur_time=date('Y-m-d H:i:s', time());

 
$post_data2='{
 "Authentication": {
 "ApiAccessUserId": "bdtaxUser2014",
 "ApiAccessPassKey":"'.$skey.'"
 },
 "ReferenceInfo": {
 "RequestId": "'.$requst_id.'",
 "RefTranNo": "'.$ref.'",
 "RefTranDateTime": "'.$cur_time.'",
 "ReturnUrl": "http://cpatos.gov.bd/pcs/index.php/ShedBillController/onlinePaymentSuccess";
 "ReturnMethod": "POST",
 "TranAmount": "5",
 "ContactName": "'.$name.'",
 "ContactNo": "'.$contact.'",
 "PayerId": "'.$login_id.'",
 "Address": "applicentAddress"
 },
 "CreditInformations": [
 {
 "SLNO": "1",
 "CreditAccount": "0002634271324",
 "CrAmount": "5",
 "Purpose": "TRN",
 "Onbehalf": "Test"
 }
 ]
 }';
 
 echo $post_data2;
 echo '\r\n';
 
 $handle = curl_init();
curl_setopt($handle, CURLOPT_URL, "https://spg.sblesheba.com:6314/api/SpgService/PaymentByPortal");
//added
curl_setopt($handle, CURLOPT_TIMEOUT, 30);
curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 30);
curl_setopt($handle, CURLOPT_POST, 1 );


//added

curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json","Authorization: OAuth 2.0 token here"));
curl_setopt($handle, CURLOPT_POST, 1);
curl_setopt($handle, CURLOPT_POSTFIELDS, $post_data2);
curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, FALSE); # KEEP IT FALSE IF YOU RUN FROM LOCAL PC


$content2 = curl_exec($handle);

$code = curl_getinfo($handle, CURLINFO_HTTP_CODE);

if($code == 200 && !( curl_errno($handle))) {
	curl_close( $handle);
	$getData = $content2;
} else {
	curl_close( $handle);
	echo "FAILED TO CONNECT WITH API";
	exit;
}
$session_token = json_decode($getData, true );
echo $session_token ;
echo "<br/>";
$token_str = explode('"',$session_token);
//echo $token_str;
echo "<br/>";
$token=$token_str[7];
echo $token;


$direct_api_url= "https://spg.sblesheba.com:6313/SpgLanding/SpgLanding/".$token;

echo $retValue;


  //SOMETHING DONE THAT SETS $url
  header('Location:'.$direct_api_url);  




?>



