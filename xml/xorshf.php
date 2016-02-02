<html>
<body>

<?php
//settype($KEY_1, "integer");

//***********************************************
// function hex2bin
//***********************************************
 function hex2bin($str) {
     $bin = "";
     $i = 0;
     do {
         $bin .= chr(hexdec($str{$i}.$str{($i + 1)}));
         $i += 2;
     } while ($i < strlen($str));
     return $bin;
}


//***********************************************
// function strtohex
//***********************************************
function strtohex($x) {
  $s='';
  foreach(str_split($x) as $c) $s.=sprintf("%02X",ord($c));
  return($s);
}

//***********************************************
// function to8HexString
//***********************************************
function to8HexString($sa) {
    $buf = "";
    for ($k = strlen($sa); $k<8; $k++ ) {
        $buf.= 0;
    }
    $buf.= $sa;
    return $buf;
 }

//***********************************************
// function bin2ascii
//***********************************************
function bin2ascii($bin)
{
$result = '';
$len = strlen($bin);
for ($i = 0; $i < $len; $i += 8)
{
$result .= chr(bindec(substr($bin,$i,8)));
}
return $result;
}

//***********************************************
// function encrypt
//***********************************************
function encrypt( $keyin,$datain) {

    //$key = hex2bin("000000002dce910c0000ee48075bcd15");
    //$data = hex2bin("00019f1000019f11000004d200000000");
    //$key = hex2bin($keyin);
    $data = hex2bin($datain);

    $td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $keyin, $iv);
    $encrypted_data = mcrypt_generic($td, $data);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
     //echo "Encrypted data: ".bin2hex($encrypted_data). "<br/>";
    return bin2hex($encrypted_data);
}

//***********************************************
// function decrypt
//***********************************************
function decrypt( $keyin,$encrypted_data) {

    //$key = hex2bin("000000002dce910c0000ee48075bcd15");
    //$data = hex2bin("00019f1000019f11000004d200000000");
    //$key = hex2bin($keyin);
    $data = hex2bin($encrypted_data);

    $td = mcrypt_module_open('rijndael-128', '', 'ecb', '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $keyin, $iv);
    $decrypted_data = mdecrypt_generic($td, $data);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
     //echo "Decrypted data: ".bin2hex($decrypted_data). "<br/>";
     return bin2hex($decrypted_data);
}

//***********************************************
// function setUpKData
//***********************************************
function setUpKData($accesswindowstart,
                    $accesswindowstop,
                    $pincode,
                    $options ) {

  //echo "<br/>KData:<br/>";
  //$accesswindowstart = "106256";
  $accesswindowstartHex = to8HexString(dechex($accesswindowstart));

  //echo "accesswindowstart: ".$accesswindowstart."&nbsp;&nbsp;&nbsp;&nbsp;hex: ".$accesswindowstartHex."<br/>";
  //echo "accesswindowstart: ".$accesswindowstart."&nbsp;&nbsp;&nbsp;&nbsp;hex: ".dechex($accesswindowstart)."<br/>";

  //$accesswindowstop = "106257";
  $accesswindowstopHex = to8HexString(dechex($accesswindowstop));
  //echo "accesswindowstop: ".$accesswindowstop."&nbsp;&nbsp;&nbsp;&nbsp;hex: ".$accesswindowstopHex."<br/>";

  //$pincode = "1234";
  $pincodeHex = to8HexString(dechex($pincode));
  //echo "pincode: ".$pincode."&nbsp;&nbsp;&nbsp;&nbsp;hex: ".$pincodeHex."<br/>";

  //$options = "0";
  $optionsHex = to8HexString(dechex($options));
  //echo "options: ".$options."&nbsp;&nbsp;&nbsp;&nbsp;hex: ".$optionsHex."<br/>";

  $kdata = $accesswindowstartHex.$accesswindowstopHex.$pincodeHex.$optionsHex;
  //echo "kdata: ".$kdata."";

  return $kdata;
}

function setUpKey($key_0,
                  $key_1,
                  $key_2,
                  $key_3 ) {

  //$key_0 = "0";
  $key_0Hex = to8HexString(dechex($key_0));
  //$key_1 = "1";
  $key_1Hex = to8HexString(dechex($key_1));
  //$key_2 = "2";
  $key_2Hex = to8HexString(dechex($key_2));
  //$key_3 = "3";
  $key_3Hex = to8HexString(dechex($key_3));

  $key = $key_3Hex.$key_2Hex.$key_1Hex.$key_0Hex;

  return $key;
}


//***********************************************
// function xorshf
//***********************************************
  function xorshf($Key0, $Key1)
  {
          $trace = true;
          if ($trace) echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;START xorshf func<br/>";
		// Key0 systemcode
    // Key1 imei
		  $KEY_1 = 382636069;  //Fixed key data
		  //settype($x, "unsiged integer");
		  $x = 0;
		  //settype($y, "unsiged integer");
		  $y = 0;
		  //settype($z, "unsiged integer");
		  $z = 0;
		  //settype($t, "unsiged integer");
		  $t = 0;
      if (($Key0 != 0) && ($Key1 != 0)) {
        $x = $Key0;		//systemcod
        $y = $KEY_1;
        $z = $Key1; 	//imei
        //echo "Y =" . dechex($y) . "<br>";
        //echo $KEY_1  . "<br>";
      }
		  $x ^= ($x << 16) & 0xffffffff;;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;x << 16: ".dechex($x) . "<br>";
      $x ^= ($x >> 5) & 0xffffffff;;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;x >> 5: ".dechex($x) . "<br>";
      $x ^= ($x << 1) & 0xffffffff;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;x << 1: ".dechex($x) . "<br>";
		    //echo "-----------------------------<br>";
      $t = $x;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;t: ".dechex($t) . "<br>";
      $x = $y;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;x: ".dechex($x) . "<br>";
      $y = $z;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;x: ".dechex($y) . "<br>";
      $z = ($t ^ $x ^ $y ) & 0xffffffff;
		  //$z = $t ^ $x ^ $y;
		      if ($trace) echo "&nbsp;&nbsp;&nbsp;&nbsp;z = t ^ x ^ y: ".dechex($z);
//        echo "<br/>xorshf: ".dechex($z);
          if ($trace) echo "<br/>&nbsp;&nbsp;&nbsp;&nbsp;END xorshf func";

      return $z;
  }

//***********************************************
// function shiftKey
//***********************************************
  function shiftKey($imei,$systemcode)
  {
        $trace = true;
        if ($trace) echo "<br/>&nbsp;&nbsp;START shiftKey func";
        if ($trace) echo "<br/>&nbsp;&nbsp;imei: ".$imei;
    $syscodeKey = (int)($systemcode/10000); //integer
        if ($trace) echo "<br/>&nbsp;&nbsp;systemcode/10000: ".$syscodeKey;
    //if ($imei == 0) // Blir inte 0 enligt definitionen av IMEI(?)
    //   $key_0 = xorshf(11111111,$syscodeKey);
    //else
    $key_0 = xorshf($imei,$syscodeKey);
        if ($trace) echo "<br/>&nbsp;&nbsp;Key_0: ".$key_0;
    $key_1 = xorshf(0,0);
        if ($trace) echo "<br/>&nbsp;&nbsp;Key_1: ".$key_1;
    $key_2 = xorshf(0,0);
        if ($trace) echo "<br/>&nbsp;&nbsp;Key_2: ".$key_2;
    $key_3 = xorshf(0,0);
        if ($trace) echo "<br/>&nbsp;&nbsp;Key_3: ".$key_3;
    $key = $key_0; // Lägga till $key_1 $key_2 och $key_3
    $key = setUpKey($key_0,$key_1,$key_2,$key_3);
        if ($trace) echo "<br/>&nbsp;&nbsp;shiftedKey: ".$key;
        if ($trace) echo "<br/>&nbsp;&nbsp;END shiftKey func";

    return $key;
  }

//***********************************************
// function test
//***********************************************
  function test($imei,$systemcode)
  {
    echo "<br/>=================================================";
    echo "<br/>Systemcode: ".$systemcode;
    echo "<br/>IMEI: ".$imei;

    $key = 0;
    $key = shiftKey($imei, $systemcode);
    //echo "<br/>Key: ".hexdec($key);
    echo "<br/>Key hex: ".$key;

    $kdata = setUpKData("106256","106257","1234","0");
    echo "<br/>kdata: ".$kdata;

    //var_dump($kdata);

    $encryptdata = encrypt($key,$kdata);
    echo "<br/>Encryptdata: ".$encryptdata;
       // var_dump($encryptdata);
    $decryptdata = decrypt( $key,$encryptdata);
    echo "<br/>Decryptdata: ".$decryptdata;

  }

 //***********************************************
// main
//***********************************************
  test(123456789,61000);
  //test(987654321,41000);
  //test(44445555,61000);
  //test(1112345,771000);
?>

</body>
</html>
		