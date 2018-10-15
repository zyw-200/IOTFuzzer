<?php
/**                                                                    *
	* Authors------------------- B.Suresh Kumar                          *
	* File Description---------- Template Generator based on CFLG & CGCT *
	* Created Date--------- 29-07-2005                              *
	*                                                                    *
*/
define('X_FACTOR', 4);

define('M_FACTOR', 1);
define('STATIC_BORDER', '');
error_reporting(0);

class TemplateFieldGenerator {

	private $RequiredAttrs = array
		(
			"form"     => array
			(
				"method" => "method",
				"action" => "cdata",
				"name"   => "cdata",
				"id"     => "cdata"
			),
			"text"     => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"password" => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"file"     => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"date"     => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"select"   => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"checkbox" => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			), // Yes, I know, this also violates W3C standard..
			"radio"    => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"hidden"   => array
			(
				"name" => "cdata",
				"id"   => "cdata"
			),
			"image"    => array("src" => "uri"),
			"textarea" => array
			(
				"name" => "cdata",
				"rows" => "number",
				"cols" => "number",
				"id"   => "cdata"
			)
		);

	private $html_loop     = array();
	private $params        = array();
	private $globalparams  = array();


	function GenerateTemplateField($params) {
//		$this->params	=array_unique($params);
		$this->params	=	$params;
		$actcell		="";
		$this->CreateHTML($this->params);
		//print_r($this);
		return $this->html_loop;
	}

	function CreateHTML($actloop) {
		switch ($actloop['type'])
		{
			case "hidden":
				$this->html_loop =	$this->CreateInput($actloop);
				break;

			case "text":
			case "password":
				$this->html_loop = $this->CreateInput($actloop);
				break;

			case "value":
				$this->html_loop = $actloop['value'];
				break;

			case "checkbox":
				$this->html_loop = $this->CreateCheckBox($actloop);
				break;

			case "radio":
				$this->html_loop = $this->CreateRadio($actloop);
				break;

			case "submit":
			case "reset":
			case "file":
			case "image":
				$this->html_loop = $this->CreateInput($actloop);
				break;

			case "button":
				$this->html_loop = $this->CreateButton($actloop);
				break;

			case "ipfield":
				$this->html_loop = $this->CreateIPField($actloop);
				break;
				
			case "ipv6field":
				$this->html_loop = $this->CreateIPv6Field($actloop);
				break;

			case "textarea":
				$this->html_loop = $this->CreateTextarea($actloop);
				break;

			case "select":
				$this->html_loop = $this->CreateSelect($actloop);
				break;

		}
	}

	function AppendAttributes($reqa, $opta, $attrs) {
		$type=$attrs['type'];
		$retv="";
		unset($attrs['type']);

		foreach ($reqa as $act_ran => $act_rat) {
			// Check whether it's supplied..
			if (!isset($attrs[$act_ran])) {
				//print_r($attrs);
				die("<b>Fatal Error</b>: required field for {$type} missing!");
				return "<!-- bad {$type} field! //-->";
			}

			// Check for correct value..
			if (!$this->ValidateAttributes($act_rat, $attrs[$act_ran])) {
				die("<b>Fatal Error</b>: required field for {$type} has bad value supplied!");
				return "<!-- bad {$type} field! //-->";
			}

			$retv.=' ' . $this->ParseAttributes($act_rat, $act_ran, $attrs[$act_ran]);
		}
		ksort($opta);
		foreach ($opta as $act_oan => $act_oat) {
			// Check whether it's supplied..
			if (!isset($attrs[$act_oan])) {
				continue;
			}

			// Check for correct value..
			if (!$this->ValidateAttributes($act_oat, $attrs[$act_oan])) {
				//        echo ("<b>Fatal Error</b>: optional field for {$type} has bad value supplied!");
				continue;
			}

			$retv.=' ' . $this->ParseAttributes($act_oat, $act_oan, $attrs[$act_oan]);
		}

		return $retv;
	}

	function GetOptionalAttributes($type) {
		if (!in_array($type, array("textarea","table","form")))
			$type="default";

		$OptionalAttrs = array
				(
				"textarea" => array
					(
					"disabled"  => "null",
					"readonly"  => "null",
					"taborder"  => "number",
					"accesskey" => "char",
					"onfocus"   => "script",
					"onblur"    => "script",
					"onselect"  => "script",
					"onchange"  => "script"
					),
				"table"    => array
					(
					"summary"     => "cdata",
					"width"       => "cdata",
					"border"      => "cdata",
					"frame"       => "tframe",
					"rules"       => "trules",
					"cellpadding" => "cdata",
					"cellspacing" => "cdata"
					),
				"form"     => array
					(
					"enctype"        => "contenttype",
					"accept"         => "contenttypes",
					"name"           => "cdata",
					"onsubmit"       => "script",
					"onreset"        => "script",
					"accept-charset" => "charsets"
					),
				"default"  => array
					(
					"value"          => "cdata",
					"checked"        => "null",
					"disabled"       => "null",
					"readonly"       => "null",
					"size"           => "cdata",
					"maxlength"      => "number",
					"alt"            => "cdata",
					"taborder"       => "number",
					"accesskey"      => "char",
					"onfocus"        => "script",
					"onblur"         => "script",
					"onselect"       => "script",
					"onchange"       => "script",
					"tabindex"       => "number",
					"label"          => "cdata",
					"AcceptChar"     => "cdata",
					"masked"         => "cdata"
					)
				);

		$optattrs=$OptionalAttrs[$type];
		$reqattrs=is_array($this->RequiredAttrs[$type]) ? $this->RequiredAttrs[$type] : array();

		foreach ($reqattrs as $actran => $actrtype) {
			if (isset($optattrs[$actran]))
				unset($optattrs[$actran]);
		}

		$GlobalOptionalAttrs = array (
				"class"				=> "cdata",
				"style"				=> "cdata",
				"title"				=> "cdata",
				"validate"			=> "validate",
				"lang"				=> "cdata", // FIXME?!
				"dir"				=> "dir",
				"onclick"			=> "script",
				"ondblclick"		=> "script",
				"onmousedown"		=> "script",
				"onmouseup"			=> "script",
				"onmouseover"		=> "script",
				"onmousemove"		=> "script",
				"onmouseout"		=> "script",
				"onkeypress"		=> "script",
				"onkeydown"			=> "script",
				"onkeyup"			=> "script",
				"onpaste"			=> "script",
				"tooltipText"		=>	"cdata",
				"enableForm"		=>	"cdata"
			);

		return array_merge($optattrs, $GlobalOptionalAttrs);
	}

	function CreateTextarea($textarea) {
		$textarea['onkeydown'] = 'setActiveContent();' . $textarea['onkeydown'];
		$retv    ="<textarea";
		$reqAttrs=is_array($this->RequiredAttrs["textarea"]) ? $this->RequiredAttrs["textarea"] : array();
		$optAttrs=$this->GetOptionalAttributes("textarea");
		$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $textarea);
		$retv.=">";
		$retv.=$textarea['name'];
		$retv.="</textarea>\n";
		return $retv;
	}

	function CreateSelect($select) {
		unset($select['maxlength']);
		$optionsList=$select["options"];
		unset($select["options"]);
		$selected=$select["selected"];
		unset($select["selected"]);
		$select['class'] = 'select ' . $select['class'];
		$select['onchange'] = 'setActiveContent();' . $select['onchange'];

		$retv    ="<select";
		$reqAttrs=is_array($this->RequiredAttrs["select"]) ? $this->RequiredAttrs["select"] : array();
		$optAttrs=$this->GetOptionalAttributes("select");
		$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $select);

		$retv.= ">\n";
		foreach ($optionsList as $key => $val) {
			$retv .= "<option value=\"$key\"";
			if ($key==$selected) {
				$retv .= " selected=\"selected\"";
			}
			$retv .= ">$val</option>";
		}
//FIXME -- Add options list here...
		$retv.="</select>\n";
		return $retv;
	}

	function CreateInput($input) {
		$type       =$input['type'];
		$input['class'] = $input['class']. ' input';
		if (strtolower($type) == 'file') {
			$input['onchange'] = 'setActiveContent();' . $input['onchange'];
		}
		else {
			$input['onkeydown'] = 'setActiveContent();' . $input['onkeydown'];
		}
		if ($type != 'text' && $type != 'password') {
			unset($input['maxlength']);
		}
		$retv    ="<input type='{$type}' ";

		$reqAttrs=is_array($this->RequiredAttrs[$type]) ? $this->RequiredAttrs[$type] : array();
		$optAttrs=$this->GetOptionalAttributes($type);
		$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $input);
		$retv.=">";
		return $retv;
	}

	function CreateRadio($input) {
       	$opt=explode(',',$input['options']);
		$input['onclick'] = 'setActiveContent();' . $input['onclick'];
		$cnt = count($opt);
    	foreach ($opt as $val) {
    		$optSet=explode('-',$val);
    		$options[$optSet[0]]=$optSet[1];
    	}
    	unset($input['options']);
    	
    	$retv = '<table class="tableStyle" cellspacing="0" cellpadding="0"><tr>';
        $inlineCount = 0;
	    foreach ($options as $key => $val) {
			$type       =$input['type'];
			$input['value']=$key;

			$retv .= '<td class="spacer5Percent" style="padding: 0px;">';
			$retv    .="<input type='{$type}'";

			$reqAttrs=is_array($this->RequiredAttrs[$type]) ? $this->RequiredAttrs[$type] : array();
			$optAttrs=$this->GetOptionalAttributes($type);
			$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $input);

			eval('$res = $input["value"]'.$input['selectCondition'].';');
			if ($res) {
				$retv .= " checked = 'checked'";
			}

			$retv.=">";
			$inlineCount++;
			if ($inlineCount == $cnt) {
				$retv .= '</td><td class="spacer'.(100-($inlineCount-1)*20).'Percent" style="padding: 0px; padding-right: 14px;">';
			}
			else {
				$retv .= '</td><td class="spacer20Percent" style="padding: 0px; padding-right: 14px;">';
			}
			$retv .= $val;
			$retv .= '</td>';
	    }
	    $retv .= '</tr></table>';

		return $retv;
	}

	function CreateCheckBox($input) {
		$type       =$input['type'];
		$input['onclick'] = 'setActiveContent();' . $input['onclick'];
		$inputHidden['name'] = $input['name'];

		$inputHidden['value'] = $input['value'];
		$inputHidden['id'] = $input['id'];
		$inputHidden['type'] = 'hidden';

		$input['onclick'] = '$(\''.$inputHidden['id'].'\').value=(this.checked)?\'1\':\'0\';'.$input['onclick'];
		$input['name']='cb_' . $inputHidden['id'];
		$input['id']='cb_' . $inputHidden['id'];

		$retv    .="<input type='{$type}' ";

		$reqAttrs=is_array($this->RequiredAttrs[$type]) ? $this->RequiredAttrs[$type] : array();
		$optAttrs=$this->GetOptionalAttributes($type);
		$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $input);

		eval('$res = $input["value"]'.$input['selectCondition'].';');
		if ($res) {
			$retv .= " checked = 'checked'";
		}

		$retv.=">";

		$retv .= $this->CreateInput($inputHidden);

		$retv .= $val;
		return $retv;
	}

	function CreateButton($input) {
		$validations=$input['validations'];
		$src        =$input['src'];
		unset($input['maxlength']);
		//$input['style']=$input['style']."margin:0px; padding-left:1px;padding-right:2px; overflow:visible;";

		//$retv = "<button {\$FieldOptions.".$input['name']."} ";
		$reqAttrs=is_array($this->RequiredAttrs[$type]) ? $this->RequiredAttrs[$type] : array();
		$optAttrs=$this->GetOptionalAttributes($type);
		$retv.="<img src='"."'";
		$retv.=$this->AppendAttributes($reqAttrs, $optAttrs, $input);
		$retv.=">";
		return $retv;
	}

	function CreateIPField($input) {
		$input['type']='text';
//		$value=explode('.', $input['value']);
		$retv='';
/*		for($i = 1; $i <= 4; $i++) {
			$phone['type']	=	'text';
			$phone['name']	=	$input['name'] . '[' . $i . ']';
			$phone['id']	=	$input['id'] . $i;
			$phone['value']	=	$value[$i-1];
//			$phone['validate'] = "Numericality, (( onlyInteger: true, minimum: 0, maximum: 255, insertAfterWhatNode: \$('".$input['id']."4').parentNode ))^Presence";
			$phone['validate'] = "Numericality, (( onlyInteger: true, minimum: 0, maximum: 255 ))^Presence";
			$phone['size']	= 3;
			$phone['maxlength']	= 3;


			$retv .= $this->CreateInput($phone);
			if ($i != 4)
				$retv .= '.';
		}*/

		$retv .= $this->CreateInput($input);
		return $retv;
	}
	function CreateIPv6Field($input) {
		$input['type']='text';
		$input['value']=str_replace("-", ":", $input['value']); 
		$retv='';
/*		for($i = 1; $i <= 4; $i++) {
			$phone['type']	=	'text';
			$phone['name']	=	$input['name'] . '[' . $i . ']';
			$phone['id']	=	$input['id'] . $i;
			$phone['value']	=	$value[$i-1];
//			$phone['validate'] = "Numericality, (( onlyInteger: true, minimum: 0, maximum: 255, insertAfterWhatNode: \$('".$input['id']."4').parentNode ))^Presence";
			$phone['validate'] = "Numericality, (( onlyInteger: true, minimum: 0, maximum: 255 ))^Presence";
			$phone['size']	= 3;
			$phone['maxlength']	= 3;


			$retv .= $this->CreateInput($phone);
			if ($i != 4)
				$retv .= '.';
		}*/

		$retv .= $this->CreateInput($input);
		return $retv;
	}	

	function ValidateAttributes($type, $value) {
		switch ($type)
			{
			case "cdata":
			case "validate": return true;
//			case "cdata": return is_string($value);

//			Commented to validate numeric values...


			case "null":
			case "": return TRUE;

			case "number": return (preg_match("/^[+-]?[0-9]+(\.[0-9]+)?$/", (string)$value) > 0);

			case "char": return (is_string($value) && strlen($value) == 1);

			case "script": return is_string($value); // TODO: Suggestions?

			case "uri": return (preg_match(
									"/^([a-z]{2,}:\/\/([-_.0-9a-zA-Z]+(:.+)?@)?"
										. "([-0-9a-zA-Z]+\.)+[0-9a-zA-Z]+(:[0-9]+)?)?"
										. "(\/?[-%+_.a-zA-Z0-9]+)*(\?[a-z0-9A-Z]+(=[a-z0-9A-Z]+)?"
										. "(&[a-z0-9A-Z]+(=[0-9a-zA-Z]+)?)*)?$/",
									$value) > 0);

			case "method": return (preg_match("/^(get|post)$/i", $value) > 0);

			case "contenttype": return (preg_match("/^[a-zA-Z0-9]+\/[-0-9a-zA-Z]+$/", $value) > 0);

			case "contenttypes": return (preg_match(
											 "/^[a-zA-Z0-9]+\/[-0-9a-zA-Z]+" . "(,[a-zA-Z0-9]+\/[-0-9A-Za-z]+)*$/",
											 $value) > 0);

			case "charsets": return (preg_match("/^[\w_]+(\ [\w_]+)*$/i", $value) > 0);

			case "tframe": return (preg_match("/^(void|above|below|hsides|lhs|rhs|vsides|box|border)$/i", $value) > 0);

			case "trules": return (preg_match("/^(none|groups|rows|cols|all)$/i", $value) > 0);

			default: return FALSE; // We are paranoid ;-)
			}

		// Never reached, though..
		return FALSE;
	}

	function ParseAttributes($type, $name, $value) {
		switch ($type)
			{
			case "uri":
			case "cdata":
			case "number":
			case "char":
			case "script":
			case "method":
			case "contenttype":
			case "contenttypes":
			case "charsets":
			case "tframe":
			case "trules": return $name . "=\"" . $value . "\"";

			case "validate": return $name . "=\"" . str_replace('((','{',str_replace('))','}',$value)) . "\"";

			case "null": return $name;

			default: return ""; // We are paranoid again ;-)
			}

		// Never reached, though..
		return "";
	}

	function ParseStyleAttributes($tag, $actloop) {
		$retv="";

		if (isset($actloop[$tag . '-valign'])) {
			$retv.=" valign='" . $actloop[$tag . '-valign'] . "'";
		}
		else if ((isset($this->globalparams[$tag . '_valign_top_on_bigger']) && in_array($actloop['type'], array
			(
			"textarea",
			"multiform"
			))) || (isset($this->globalparams[$tag . '_valign_top_on_bigger']) && $actloop['type'] == 'select' && isset(
																													  $actloop['multiple'])))
			{
			$retv.=" valign='top'";
		}

		if (isset($actloop[$tag . '-align'])) {
			$retv.=" align='" . $actloop[$tag . '-align'] . "'";
		}

		if (isset($actloop[$tag . '-style'])) {
			$retv.=" style='" . $actloop[$tag . '-style'] . "'";
		}

		if (isset($actloop[$tag . '-class'])) {
			$retv.=" class='" . $actloop[$tag . '-class'] . "'";
		}
		else if (isset($this->globalparams[$tag . '_class'])) {
			$retv.=" class='" . $this->globalparams[$tag . '_class'] . "'";
		}

		return $retv;
	}
}
?>