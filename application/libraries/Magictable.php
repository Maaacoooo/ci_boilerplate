<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* MagicTable 
* 
* Generates a Pretty-Spaced Table useful for Logs, File writing, and CLI
*/
class Magictable 
{
	

	function makeTable($header, $data) {
		$size_row = sizeof($data);
        

        if(!is_null(($header))) {

        	//compare the number of columns of header and actual data
        	if(sizeof($header) > sizeof($data[0])) {
        		$col_num = sizeof($header);

        		//Rebuild array 
        		for ($f=0; $f < $size_row; $f++) { 
        			$data[$f] = array_pad($data[$f], $col_num, ' ');
        		}

        	} else {
        		$col_num = sizeof($data[0]);
        	}

        	$header = array_pad($header, $col_num, ' ');

        	// Set column sizes according to header        	
	        for ($a=0; $a < $col_num; $a++) { 
	            $col_size[$a] = sizeof(str_split($header[$a]));
	        }
		
        } else {

        	$col_num = sizeof($data[0]); //the number of columns 
        	//if no header is defined; set column sizes according to data
	        for ($b=0; $b < $col_num; $b++) { 
	            $col_size[$b] = 0;
	        }
        
       }        

        // GET THE MAX NUMBER OF CHARACTER //////////////////////////
        foreach ($data as $item) {
            for ($z=0; $z < sizeof($item); $z++) { 

                $char = sizeof(str_split($item[$z])); //count the characters of the string

                if($col_size[$z] < $char) {
                    $col_size[$z] = $char; //override space data
                }
            }
        }        

        // COMPILE COLUMNS WITH WHITESPACES ///////////////////////////////////////
        
        
        //Table Headers Data and Whitespaces ///////////////////////////////////
        if(!is_null($header)) {

        	$row_result[] = $this->divider($col_size); //table divider

        	for ($d=0; $d < sizeof($header); $d++) { 

                if($d == 0) {
                	$col_result[] = "| ". str_pad($header[$d], $col_size[$d], " ", STR_PAD_BOTH);
                } elseif($d == sizeof($header)-1) {
                	$col_result[] = str_pad($header[$d], $col_size[$d], " ", STR_PAD_BOTH) . " |";
                } else {
                	$col_result[] = str_pad($header[$d], $col_size[$d], " ", STR_PAD_BOTH);
                }          
        	} 
        	$row_result[] = implode(' | ',$col_result);
            $col_result = array(); //reset data
        }

        $row_result[] = $this->divider($col_size); //table divider

        //Table Data //////////////////////////////////

        foreach ($data as $item) {
            for ($z=0; $z < sizeof($item); $z++) { 

            	if($z == 0) {
                	$col_result[] = "| ". str_pad($item[$z], $col_size[$z], " ", STR_PAD_BOTH);
                } elseif($z == sizeof($item)-1) {
                	$col_result[] = str_pad($item[$z], $col_size[$z], " ", STR_PAD_BOTH) . " |";
                } else {
                	$col_result[] = str_pad($item[$z], $col_size[$z], " ", STR_PAD_RIGHT);
                }    
                              
            }
            $row_result[] = implode(' | ',$col_result);
            $col_result = array(); //reset data
        }

        $row_result[] = $this->divider($col_size); //table divider

        //Compile and Return as String
        return implode("\n", $row_result);
	}


	private function divider($col_size) {

		for ($i=0; $i < sizeof($col_size); $i++) { 
			//find the end of the table
			if($i == 0){
				$divider[] = "+" . str_pad('-', $col_size[$i], '-', STR_PAD_BOTH) . '--+';
			} else {
				$divider[] = str_pad('-', $col_size[$i], '-', STR_PAD_BOTH) . '--+';
			}
		}

		return implode('', $divider);
	} 

}