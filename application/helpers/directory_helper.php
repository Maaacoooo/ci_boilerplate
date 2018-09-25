<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * --------------------------------------------------------------
 * Directory / File Helper
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

/**
 * Removes the File
 * @param [type] $file [description]
 */
function RemoveFile($filepath) {
    if(filexist($filepath)) {
        return unlink($filepath); //removes the file
    } 
}


function UploadFile($path, $field, $upload_path = null, $encrypt_name = TRUE, $filetypes = 'gif|png|jpg|bmp|jpeg') {

    if (is_null($upload_path)) {
        $path = UPLOAD_DIR . $path;
    }

    if($_FILES[$field]['name'] != NULL)  {        

        $path = checkDir($path);
        $config['upload_path'] = $path;
        $config['allowed_types'] = $filetypes; 
        $config['encrypt_name'] = TRUE;         
        
        $CI =& get_instance();

        $CI->load->library('upload', $config);
        $CI->upload->initialize($config);         
        
        $CI->upload->do_upload($field);

        $upload_data = $CI->upload->data();

        $filepath = $path . $upload_data['file_name'];

        return $filepath;
            
    } else {
        return null;
    } 

    
}



include APPPATH.'libraries/php-image-resize/ImageResize.php';
include APPPATH.'libraries/php-image-resize/ImageResizeException.php';


/**
 * Resizes an Image According to its defined side
 * @param [type]  $path  [description]
 * @param integer $width [description]
 */
function Img_Resize($path, $size = 450, $side = "width") {

    if (file_exists($path)) {
        $image = new \Gumlet\ImageResize($path);

        switch ($side) {
            case 'width':
                $image->resizeToWidth($size);
                break;
            
             case 'height':
                $image->resizeToHeight($size);
                break;
        }        
        $image->save($path);
    }
}

/**
 * Center Crops an Image File
 * @param [type]  $path  [description]
 * @param integer $width [description]
 */
function ImgCropper($path, $width = 450) {
     if (file_exists($path)) {
        $image = new \Gumlet\ImageResize($path);
        $image->crop($width, $width, true, \Gumlet\ImageResize::CROPCENTER);
        $image->save($path);
    }
}

/**
 * Creates a Thumbnail copy of an Img File
 * @param [type]  $path  [description]
 * @param integer $width [description]
 */
function ImgThumb($path, $width = 300) {
    if (file_exists($path)) {
        $image = new \Gumlet\ImageResize($path);
        $image->crop($width, $width, true, \Gumlet\ImageResize::CROPCENTER);
        //Creates a new file copy
        $path = thumbPath($path);
        $image->save($path);

        return $path;
    }

    return FALSE;

} 

/**
 * Returns a filepath of the thumbnail 
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function thumbPath($path) {
    $path = explode('/', $path);
    //get file name 
    $filename = 'thumb_';
    $filename .= $path[sizeof($path)-1];
    //replace filename
    $path[sizeof($path)-1] = $filename;
    //Combine
    return implode('/', $path);
}


/**
 * Resizes all the image contents in the given path
 * @param  [type] $dir [description]
 * @return [type]      [description]
 */
function resizeExistingImages($dir) {
    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;


    foreach($ffs as $ff){
        if(is_dir($dir.'/'.$ff)) {
            resizeExistingImages($dir.'/'.$ff);
        } else {
            $path = $dir.'/'.$ff;
            if (bastard_check_img($path)) {
                Img_Resize($path);
                echo $path . '<br/>';
            }            
        }

    }
}


/**
 * Bastardly check the filetype.
 *
 * PS. This is kinda stupid, but it works
 * @param  [type] $path [description]
 * @return [type]       [description]
 */
function bastard_check_img($path) {
    $img = explode('.', $path);
    $img_filetypes = array('jpg', 'JPG', 'jpeg', 'JPEG', 'png', 'PNG', 'pneg', 'PNEG', 'gif', 'GIF');

    if (is_int(array_search($img[sizeof($img)-1], $img_filetypes))) {
        return TRUE;
    }
    return FALSE;

}


/**
 * Returns an image base link
 * if it doesn't exist, returns the no-image 
 * @param  [type] $src    [description]
 * @param  string $no_img [description]
 * @return [type]         [description]
 */
function checkImg($src, $no_img = 'assets/custom/img/no_image.gif') {

    if (file_exists($src)) {
        return base_url($src);
    } else {
        return base_url($no_img);
    }
}



function setWatermark($filepath, $text = null) {

    if (is_null($text)) {
        $text = 'Copyright '.APP_NAME.' '.date('Y');
    }

    // Set Watermark ////////////////////////////////////////////////////
    $wm_config['quality'] = '100%';
    $wm_config['wm_text'] = $text;
    $wm_config['wm_type'] = 'text';
    $wm_config['wm_font_path'] = './system/fonts/arial.ttf';
    $wm_config['wm_font_size'] = '16';
    $wm_config['wm_font_color'] = 'ffffff';
    $wm_config['wm_vrt_alignment'] = 'bottom';
    $wm_config['wm_hor_alignment'] = 'left';
    $wm_config['source_image'] = $filename; 
    /////////////////////////////////////////////////////////////////////
}
