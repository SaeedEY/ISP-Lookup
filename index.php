<?php
//Written in December 2016 until December 2017 By SaeedEY.com
function ISP($ip){
	if(!preg_match("/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/ ", $ip))
		return false;
	preg_match_all("/([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})/ ", $ip, $myip);
	$tables = json_decode(file_get_contents("[ISP].txt"),true)['16-bit'];
	foreach ($tables as $key => $value) {
		$range = explode('-',$value['net_range']);
		preg_match_all("/([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})/ ", $range[0], $from);
		preg_match_all("/([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})\.([\d]{1,3})/ ", $range[1], $to);
		for($i=1;$i<4;$i++){
			if(intval($myip[$i][0]) == intval($from[$i][0]))
				continue;
			elseif(intval($myip[$i][0]) >= intval($from[$i][0]) and intval($myip[$i][0]) <= intval($to[$i][0])){
			    	// Save IP for Statistics usage ! :D
				return $value;
			}else
				break;
		}
	}
	return false;
}
if(isset($_GET['json'])){
    die(json_encode(ISP(trim(htmlspecialchars(($_GET['json'] == "me")?$_SERVER['REMOTE_ADDR']:$_GET['json'])))['designation'],JSON_PRETTY_PRINT));
}
?>
<!DOCTYPE html>
<html>
<head>
     <title>ISP Lookup | SaeedEY.com</title>
	<meta name="keywords" content="isp lookup 2017,2018,2017,isp finder , what is my isp , who is isp , my isp is , my internet service provider , ISP lookup , find isp from ip , ip range isp,isp define from ip address" />
	<meta content="Saeed EY Open Source ISP Lookup With New 2017-2018 ISP List and usefull Module For Bussines Usage.Test it Your Self ." name="description">
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<form method="post" id= "form2" action="<?php $_SERVER['PHP_SELF']; ?>">
		<span>IP Address : </span><input type="ip" name="One" />
		<input type="button" value="Find My ISP" onclick="ValidIP(One);" />
	</form>
	
	<a href="mailto:info@saeedey.com?subject=Request For MY ISP">[!] Contact Me [!]</a>
	<br></p>
	<span>API :</span>
	<pre>
	@param $_GET['json'] (String)
	@return THE ISP NAME (String)
	</pre>
	<span>Example :</span>
	<pre>
	@param <?php echo $_SERVER['PHP_SELF'];?>?json=129.55.1.1
	@return "Massachusetts Institute of Technology"
	</pre>
	<span>Last Update : 10 December 2017</span><hr>

	<script type="text/javascript">
	    function ValidIP(IP){  
	    	var ipformat = /^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/;  
	    	if(IP.value.match(ipformat) || IP.value=="me"){  
	    		document.getElementById("form2").submit();  
	     		return true;  
	     	}else{  
	     		alert("You have entered an invalid IP address!");  
	     		return false;  
	     	}  
	    }  
	</script>
<?php
     if(isset($_POST['One']) && $_POST['One'] != null){
        $ip = $_POST['One'];
        $ip = trim(htmlspecialchars($ip));
        $ip = ($ip == "me")?$_SERVER['REMOTE_ADDR']:$ip;
        $ISP = ISP($ip);
        if($ISP)
            echo '<centeR><br>Your ISP is :<span style="font-size:18px;">'.$ISP['designation']."</span><centeR><script>alert('Your ISP is : ". $ISP['designation']."');</script>";
        else
            echo '<centeR><br>This is an Incorrect IP or maybe not even exist.'.$ip.'<centeR><script>alert("This is an Incorrect IP '.$ip.'or maybe not even exist.");</script>';
     }
?>
</body>
</html>
