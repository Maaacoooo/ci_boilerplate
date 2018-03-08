<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * --------------------------------------------------------------
 * Directory Helper
 * --------------------------------------------------------------
 * 
 */


/**
* Checks and Creates a directory from a String Request
* @param  String   $path           the path to be created; e.g: dir1/subdir1/supersub1
* @param  String   $upload_folder  the main upload folder
* @return String   The path created
*/
function checkDir($path) {

        $exp_path = explode('/', $path);

        foreach ($exp_path as $key => $value) {

            $addr[] = $value; //compile path
            $dir_path = implode('/', $addr); //glue parts

            //checks if path already exist
            if(!is_dir($dir_path)) {
                //Create a Path
                if (mkdir($dir_path)) {     
                    write_file($dir_path.'/index.html', ''); //creates an index HTML for random path access security - (Is this even the correct term?) 
                } else {               
                    return FALSE; // if error occurs
                }
            } 
        }

        return $dir_path;

}


/**
* Simply checks the existence of the file
* @param  String   $file   The file path.
* @return Boolean          Returns true if exists. U NO SAY????
*/
function filexist($file) {

        if(file_exists($file)) {
            return TRUE;
        } else {
            return FALSE;
        }
}