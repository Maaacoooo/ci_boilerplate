<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

/**
 * Link Helper
 */

/**
* Removes unnecessary characters.
* @param  [type] $str [description]
* @return [type]      [description]
*/
function safelink($str) {
  return preg_replace("/[^a-zA-Z]/", "", $str);
}