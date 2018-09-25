<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


    function DenyAccess() {
        show_error('Oops! Your account does not have the privilege to view the content. Please Contact the Administrator', 403, 'Access Denied!');
        die();
    }


