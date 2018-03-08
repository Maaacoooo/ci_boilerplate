<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * --------------------------------------------------------------
 * Number Helper
 * --------------------------------------------------------------
 * 
 */


/**
 * Returns a money format 
 * @param  double  $digits   [description]
 * @param  [type] $currency [description]
 * @return [type]           [description]
 */
function moneytize($digits, $currency = NULL) {

        if(is_null($currency)) {
            $currency = APP_CURRENCY; //system defaults 
        }
        $digits = number_format($digits, 2, '.', ',');
        return $currency . ' ' . $digits;

    }


/**
* Converts Decimal into Char form
* @param  [type] $str [description]
* @return [type]      [description]
*/
function num_to_char($str) {

        //The array of digits with its corresponding character
        $val_arr = array(
        '1' => 'A',
        '2' => 'B',
        '3' => 'C',
        '4' => 'D',
        '5' => 'E',
        '6' => 'F',
        '7' => 'G',
        '8' => 'H',
        '9' => 'I',
        '0' => 'J'
        );

        $num_arr = str_split(round($str));

        foreach ($num_arr as $key => $value) {
            $num_to_char[] = $val_arr[$num_arr[$key]];
        }

        return implode("", $num_to_char);

}


/**
* Returns a decimal form
* @param  [type] $str [description]
* @return [type]      [description]
*/
function decimalize($str) {
        return sprintf("%1\$.2f", $str);
}