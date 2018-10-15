<?php

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}


/******************************** Start of Assigning TWOGHZ and FIVEGHZ status true or false based on run-time modes. ************************************/

	$configTWOGHZ = Array('TWOGHZ' => array('status' => false));
        $configFIVEGHZ = Array('FIVEGHZ' => array('status' => false));
        $configMODE11N = Array('MODE11N' => array('status' => false));
	$b0 = Array('B0' => array('status' => false));
	$bg0 = Array('BG0' => array('status' => false));
	$ng0 = Array('NG0' => array('status' => false));
	$a0 = Array('A0' => array('status' => false));
	$na0 = Array('NA0' => array('status' => false));
	$b1 = Array('B1' => array('status' => false));
	$bg1 = Array('BG1' => array('status' => false));
	$ng1 = Array('NG1' => array('status' => false));
	$a1 = Array('A1' => array('status' => false));
	$na1 = Array('NA1' => array('status' => false));


        function getModeList($mode)
        {
                $str = "";
                $str = conf_get('system:monitor:modeList:wlan'.$mode);
                $tmp=explode(' ', $str);
                $modeListArr11 = explode(';',$tmp[1]);
                for($i=0; $i < sizeof($modeListArr11); $i++){
                      $modeListArr[$i]=substr($modeListArr11[$i],2);
                }
		return array_filter($modeListArr);
        }
	
	$mode_list0=getModeList(0);
	$mode_list1=getModeList(1);
	
	if( sizeof($mode_list0) > 0 ) { 
	       for( $i = 0; $i < sizeof($mode_list0); $i++ ){
                        if( $mode_list0[$i] == 'b' || $mode_list0[$i] == 'bg' ){
                                $configTWOGHZ = Array('TWOGHZ' => array('status' => true));
                        }
			if( $mode_list0[$i] == 'ng'|| $mode_list0[$i] == 'na' ){
                                $configMODE11N = Array('MODE11N' => array('status' => true));
                        }
			if( $mode_list0[$i] == 'b'){
                                $b0 = Array('B0' => array('status' => true));
			}
			if( $mode_list0[$i] == 'bg'){
                                $bg0 = Array('BG0' => array('status' => true));
                        }
			if( $mode_list0[$i] == 'ng'){
                                $ng0 = Array('NG0' => array('status' => true));
                        }
			if( $mode_list0[$i] == 'a'){
                                $a0 = Array('A0' => array('status' => true));
                        }
			if( $mode_list0[$i] == 'na'){
                                $na0 = Array('NA0' => array('status' => true));
                        }
                }	
        }

        if( sizeof($mode_list1) > 0 ){
                for( $i = 0; $i < sizeof($mode_list1); $i++){
                        if( $mode_list1[$i] == 'a' ){
                                $configFIVEGHZ = Array('FIVEGHZ' => array('status' => true));
                        }
			if( $mode_list1[$i] == 'ng' || $mode_list1[$i] == 'na' ){
                                $configMODE11N = Array('MODE11N' => array('status' => true));
                        }
			if( $mode_list1[$i] == 'b'){
                                $b1 = Array('B1' => array('status' => true));
                        }
                        if( $mode_list1[$i] == 'bg'){
                                $bg1 = Array('BG1' => array('status' => true));
                        }
                        if( $mode_list1[$i] == 'ng'){
                                $ng1 = Array('NG1' => array('status' => true));
                        }
                        if( $mode_list1[$i] == 'a'){
                                $a1 = Array('A1' => array('status' => true));
                        }
                        if( $mode_list1[$i] == 'na'){
                                $na1 = Array('NA1' => array('status' => true));
                        }
	       }
        }

        $config = array_merge($config, $configTWOGHZ, $configFIVEGHZ, $configMODE11N, $b0, $bg0, $ng0, $a0, $na0, $b1, $bg1, $ng1, $a1, $na1 );
/************************ End of Assigning TWOGHZ and FIVEGHZ status true or false based on run-time modes. *****************************/


if (isset($_REQUEST['json']) && $_REQUEST['json'] == 'true') {
    if (getProductID() == 'WG102') {
        echo "var config=".json_encode($config)."; var config2=".json_encode($config2 =       Array(
                'WG102'      =>      array('status'  =>      true)));
    }
    else {
        echo "var config=".json_encode($config)."; var config2=".json_encode($config2 =       Array(
                'WG102'      =>      array('status'  =>      false)));
    }
}

function getProductID() {
        $confdEnable = true;
        if ($confdEnable) {
                $productIdArr = explode(' ', conf_get('system:monitor:productId'));
                $productId = $productIdArr[1];
        }
        else {
                $productId = "WG102";
        }
        return $productId;
}
       
?>
