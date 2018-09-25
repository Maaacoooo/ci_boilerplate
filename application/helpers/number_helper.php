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


/**
* Returns a pretty ID. 
* @param  int       $str    the String to be prettified
* @param  int       $digits the number of digits to fulfill; default is 5
* @return Double           returns 000001 
*/
function prettyID($str, $digits = 5) {
     return str_pad($str,$digits,"0",STR_PAD_LEFT);
}



/**
* Returns the age. This is stupidly coded for some reasons
* @param  String   $date   a MySQL Date format str
* @param  String   $range  the range to be calculated
* @return int              the Age
*/
function getAge($age_sql, $range) {

    $str = "#".timespan(mysql_to_unix($age_sql . '00:00:00'), $range, 1);

    sscanf($str,"#%d",$age);

    return $age;
}


/**
* This cleans a string to simply get the INT id 
* @param  String   $str    the string starting with # . e.g  "#000143-- John Jones Smith"
* @return int              the int ID. e.g     "143"
*/
function cleanId($str) {

    sscanf($str,"#%d",$id);

    return $id;
}