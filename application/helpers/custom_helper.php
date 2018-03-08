<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 




    /**
     * Removes unnecessary characters.
     * @param  [type] $str [description]
     * @return [type]      [description]
     */
    function safelink($str) {
       return preg_replace("/[^a-zA-Z]/", "", $str);
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
     * Returns a pretty ID. 
     * @param  int       $str    the String to be prettified
     * @param  int       $digits the number of digits to fulfill; default is 5
     * @return Double           returns 000001 
     */
    function prettyID($str, $digits = 5) {
        return str_pad($str,$digits,"0",STR_PAD_LEFT);
    }



    /**
     * Gets the Row ID 
     * @param  String      $str     the ID of the item. i.e    ABCD-01-01-00001 
     * @return String               the actual ROW ID          1;
     */
    function getRowID($str) {
        $str = explode("-", $str);

        if(sizeof($str) > 1) {
            sscanf($str[(sizeof($str)-1)], "%d", $result);
        } else {
            sscanf($str[0], "ITEM%d", $result);
        }

        return $result;

    }


