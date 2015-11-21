<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty {html_hrefs} function plugin
 *
 * File:       function.html_hrefs.php<br>
 * Type:       function<br>
 * Name:       html_hrefs<br>
 * Date:       24.Feb.2003<br>
 * Purpose:    Prints out a list of href input types<br>
 * Input:<br>
 *           - hrefs      (optional) - associative array
 *			 - onclicks	  (optional) - associative array
 *			 - texts	  (optional) - associative array
 *           - separator  (optional) - ie <br> or &nbsp;
  * Examples:
 * <pre>
 * {html_hrefs hrefs=$ids separator='<br>' onclicks=$functions texts=$names}
 * </pre>
 *      (Smarty online manual)
 * @author     Christopher Kvarme <christopher.kvarme@flashjab.com>
 * @author credits to Monte Ohrt <monte at ohrt dot com>
 * @version    1.0
 * @param array
 * @param Smarty
 * @return string
 * @uses smarty_function_escape_special_chars()
 */
function smarty_function_html_hrefs($params, &$smarty)
{
    require_once $smarty->_get_plugin_filepath('shared','escape_special_chars');

    $hrefs = null;
    $onclicks = null;
    $separator = ' | ';
	$texts = null;

    $extra = '';

    foreach($params as $_key => $_val) {
        switch($_key) {
            case 'hrefs':
				$$_key = (array)$_val;
				break;
				
			case 'onclicks':
				$$_key = (array)$_val;
				break;
				
            case 'separator':
                $$_key = $_val;
                break;
				
			case 'texts':
				$$_key = (array)$_val;
				break;

            default:
                if(!is_array($_val)) {
                    $extra .= ' '.$_key.'="'.smarty_function_escape_special_chars($_val).'"';
                } else {
                    $smarty->trigger_error("html_checkboxes: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
                }
                break;
        }
    }

    if (!isset($hrefs) && !isset($texts))
        return ''; /* raise error here? */

    $_html_result = array();
    if (isset($hrefs)) {
        $n = 0;
		foreach ($hrefs as $_val){
			$_html_result[] = smarty_function_html_hrefs_output($n, $_val, $onclicks[$n], $texts[$n], $separator);
			$n = $n+1;
		}
    }

    if(!empty($params['assign'])) {
        $smarty->assign($params['assign'], $_html_result);
    } else {
        return implode("\n",$_html_result);
    }

}

function smarty_function_html_hrefs_output($n, $hrefs, $onclicks, $texts, $separator) {
    $_output = '';
	if($n > 0)
		$_output .=  $separator;
    $_output .= '<span>';
    $_output .= '<a href="'.$hrefs.'" class="check" onclick="'
        . $onclicks.'"';
    $_output .= ' />' . $texts . '</a>';
    $_output .= '</span>';	
    return $_output;
}

?>
