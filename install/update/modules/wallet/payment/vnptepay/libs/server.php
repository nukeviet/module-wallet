<?php
function executeSelect($query)
{
	$link = mysql_connect("localhost", "sms", "saomayman")  or die("Could not connect");
	mysql_select_db("sms") or die("Could not select database");
	$query = $query;
	$result = mysql_query($query) or die(mysql_error());
	return $result ;

}
/*
function execute5giay($query)
{
	$link = mysql_connect("5giay.vn", "igo", "igosmsdb")  or die("Could not connect");
	mysql_select_db("web1_db3") or die("Could not select database");
	$query = $query;
	$result = mysql_query($query) or die(mysql_error());
	return $result ;

}
*/

function execute5giay($query)
{
	$link = mysql_connect("localhost", "sms", "saomayman")  or die("Could not connect");
	mysql_select_db("forum") or die("Could not select database");
	$query = $query;
	$result = mysql_query($query) or die(mysql_error());
	return $result ;

}

function query_first($selectdb,$query)
{
	if ($selectdb='5giay')
		$link = execute5giay($query);
	else
		$link = executeSelect($query);
	return mysql_fetch_array($link);
}

//Lọc chuỗi
function killchar($sInput)
{
	$badChars = array("select", "drop", "--", "insert", "delete", "update", "xp_","script","*",".swf","-","=","or","'");
	$size=count($badChars);	
	for($i=0;$i<$size;$i++)	
		$sInput=str_replace($badChars[$i],"",$sInput);
	return $sInput;
}	
// load SOAP library
require_once("libs/nusoap.php");

// load library that holds implementations of functions we're making available to the web service
// set namespace
$ns="";
// create SOAP server object
$server = new soap_server();
// setup WSDL file, a WSDL file can contain multiple services
$server->configureWSDL('ReceiveMO',$ns);
$server->wsdl->schemaTargetNamespace=$ns;
// register a web service method
$server->register('ReceiveMO',
	array('moid' => 'xsd:string',  //Unique ID from GW. Trong trường hợp nhận được nhiều MO có cùng moid thì chỉ được xử lý 1 lần duy nhất.
	'moseq' => 'xsd:string',       //Unique ID from GW
	'src' => 'xsd:string',         //End user mobile number from GW. ex : 84915043333
	'dest' => 'xsd:string',        //Service_number. ex : 8588 / 6xxx
	'cmdcode' => 'xsd:string',     // DKG
	'msgbody' => 'xsd:string',     // DKG 123
	'opid' => 'xsd:string',        // ID telco. Ex: gpc8x88,vms8x88,gpc6x65
	'username' => 'xsd:string',    // user provide by iGO
	'password' => 'xsd:string'),   // pass provide by iGO	// input parameters
	array('ReceiveMOResult' => 'xsd:int'), 						// output parameter
	$ns, 														// namespace
    "$ns/ReceiveMO",		                					// soapaction
    'rpc',                              						// style
    'encoded',                          						// use
    ''           												// documentation
	);
/*
function ws_add($int1, $int2){
return new soapval('return','xsd:integer',add($int1, $int2));
}
*/
function ReceiveMO($moid,$moseq,$src,$dest,$cmdcode,$msgbody,$opid,$username,$password){
	if ($username=='igo' && $password=='lightuponline')
	{
		//process content Received from VDC 				
		executeSelect("insert into receive(moid,phone,service_number,syntax,content,telco,time_receive) values ($moid,'$src','$dest','$cmdcode','$msgbody','$opid',".time().")");		
		
		$mtseq = mysql_insert_id();
		
		$msgbody ="He thong 5giay hien tai khong dap ung duoc dich vu ban yeu cau";
		
		//Cắt chuỗi nội dung tin nhắn 
		$tmp = explode(" ",killchar(trim($msgbody)));
		
		
		//Xử lý cú pháp 5KH--------------------------------------------------		
		if (strtoupper($cmdcode)=="5KH")
		{
			//check service_number
			if ($dest=="8588" || $dest=="8088")				
			{
				$strsql = "SELECT userid,username FROM user WHERE smskeyactive = '$tmp[1]'";
				$result = query_first('5giay',$strsql);		
				if($result['userid']!=NULL){		
					execute5giay("UPDATE user SET usergroupid = 2, smskeyactive = 'Done' WHERE userid=".$result['userid']);
					$msgbody ="Tai khoan ".$result['username']." da duoc kich hoat, Cam on ban da dang ky lam thanh vien tai 5giay.vn";
				}else{
					$msgbody ="Ma kich hoat khong ton tai. Ban vui long kiem tra lai ma kich hoat";
				}
					
			}
			else
			{
				$msgbody ="Ban da nhan tin sai cu phap. Vui long soan 5KH ACTIVECODE gui 8588";
			}
		}
		//Xử lý cú pháp 5KH--------------------------------------------------	
		
		//Xử lý cú pháp 5GIAY--------------------------------------------------		
		if (strtoupper($cmdcode)=="5GIAY")
		{
			//check service_number
			if ($dest=="8788")				
			{
				$strsql = "SELECT userid,username FROM user WHERE userid =".$tmp[1];
				$result = query_first('5giay',$strsql);		
				if($result['userid']!=NULL){					
					execute5giay("update user set point5s =point5s+15 where userid=".$result['userid']);
					$msgbody ="Tai khoan ".$result['username']." da duoc cong 15K, Cam on ban da su dung dich vu tai 5giay.vn";
				}else{		
					$msgbody ="Ma thanh vien khong ton tai. Ban vui long kiem tra lai ma thanh vien";
				}
			}
			else
			{
				$msgbody ="Ban da nhan tin sai cu phap. Vui long soan 5GIAY USERID gui 8788";
			}
		}
		//Xử lý cú pháp 5GIAY--------------------------------------------------	
		
		
		//Xử lý cú pháp 5UP--------------------------------------------------		
		if (strtoupper($cmdcode)=="5UP")
		{	
			$strsql = "SELECT threadid FROM thread WHERE threadid =".$tmp[1];
			$result = query_first('5giay',$strsql);	
			switch ($dest){
				
				case "8100" : 				
					if ($result!=NULL)
					{
						$strsql ="update thread set lastpost=".time().",count_sms =count_sms+1 where threadid =".$tmp[1];
						$msgbody ="Cam on ban da su dung dich vu cua 5giay.vn. Topic ".$tmp[1]." cua ban duoc them 1 luot UP";
					}else{
						$msgbody ="Ban da nhan tin sai cu phap. Vui long Soan UP <ID topic> gui toi 8100 de su dung dich vu. Cam on ban";
					}
				break;
				
				case "8500" :
					if ($result!=NULL)
					{
						$strsql ="update thread set lastpost =".time().",datehot=".time().",status_sms = 3 where threadid =".$tmp[1];
						$msgbody ="Cam on ban da su dung dich vu cua 5giay.vn. Topic ".$tmp[1]." cua ban thanh topic HOT trong 24 gio";
					}else{
						$msgbody ="Ban da nhan tin sai cu phap. Vui long Soan UP <ID topic> HOT gui toi 8500 de su dung dich vu. Cam on ban";
					}
				break;
				
				default :
					$msgbody ="Ban da nhan tin sai cu phap. Vui long xem lai huong dan tai 5giay.vn";
				break;
			}//end switch		
			
		}
		//Xử lý cú pháp 5UP--------------------------------------------------	
		
		$msgtype = "Text";
		$msgtitle =""; 
		$mttotalseg ="1"; 
		$mtseqref = "1"; 
		$reqtime = date(YmdGis,time());
		$procresult="1"; 
		
			
		//Begin call Function SendMT VDC
		$client = new nusoap_client('http://www.mymobile.com.vn/SMSAPIWS/SMSAgentWS.asmx?wsdl', 'wsdl',
						'', '', '', '');
		
		$param=array('mtseq'=>$mtseq, //Thứ tự/ID MT bên phía hệ thống 5giay
		'moid' => $moid,
		'moseq' => $moseq,
		'src' => $dest,
		'dest' => $src,
		'cmdcode' => $cmdcode,
		'msgbody' => $msgbody, //noi dung tin nhan tra ve
		'msgtype' => $msgtype, //Text,Bookmark(Wappush),Ringtone,Logo,Picture...
		'msgtitle' => $msgtitle, //Tiêu đề của wappush (chỉ có khi gửi wappush).
		'mttotalseg' => $mttotalseg, //Tổng số MT trả về đối với một MO nhắn lên. Có giá trị =1 đối với MT không có MO yêu cầu
		'mtseqref' => $mtseqref, //Số thứ tự MT của tin nhắn trả về trong tổng số MT trả về
		'cpid' => '191',
		'reqtime' => $reqtime, //Thời gian nhận MO từ hệ thống sms vdc, có format như sau: yyyyMMddHHmmss
		'procresult' => $procresult, //Có giá trị =0 không tính cước,Có giá trị =1 tính cước
		'opid' => $opid,
		'username' => '5giay',
		'password' => '7rnuixm8l3'); 
		
		$result=$client->call('SendMT', $param);	//get return function SendMT	
		
		if ($client->fault) {
			$returnend =$result;
		} else {
			// Check for errors
			$err = $client->getError();
			if ($err) {
				// Display the error
				$returnend = $err;
			} else {
				// Display the result				
				if ($result['SendMTResult']==200)
				{							
					$returnvalue = 200;
					$returnend = 200;						
				}
				else
				{
					$returnend = $result['SendMTResult'];	     
					$returnvalue = 202;
				}
			}
		}			
		//End call Function SendMT VDC
		
		//Insert into table reply		
		executeSelect("insert into reply(mtseq,moid,phone,service_number,syntax,reply_content,telco,result,time_reply) values ('$mtseq','$moid','$src','$dest','$cmdcode','$msgbody','$opid','$returnend',".time().")");	
		
		return new soapval('return','xsd:int',$returnvalue);	//return function ReceiveMO		
		
	}
	else 
		return new soapval('return','xsd:int',404);
}
// service the methods 
$server->service($HTTP_RAW_POST_DATA);
?>