<?php

function get_asstes_files($module_assets_files, $module_name, $controller, $function)
{
	$load_files = array();
    foreach ($module_assets_files as $key => $file) {
        $load_files['module_name'] = $key;
        if(isset($file['js-files']) && count($file['js-files']) > 0){
            $js_files = $file['js-files'];
            for($i = 0; $i < count($js_files); $i++){
                for($j = 0; $j < count($js_files[$i]['location']); $j++){

                    if(strpos($js_files[$i]['file'], 'http')  !== false){
                        $load_files['js_files']['file'][] = $js_files[$i]['file'];
                    } else {
                        $load_files['js_files']['file'][] = module_base_path() . $key . '/' .$js_files[$i]['file'];
                    }

                    if(strpos($js_files[$i]['location'][$j], '/')){
                        $cn_fn_arr = explode('/', $js_files[$i]['location'][$j]);
                    	$load_files['js_files']['controller'][] = $cn_fn_arr[0];
                    	$load_files['js_files']['function'][] = $cn_fn_arr[1];
                    } else {
                        $load_files['js_files']['controller'][] = '*';
                        $load_files['js_files']['function'][] = '*';
                    }
                }
            }
        }

        if(isset($file['css-files']) && count($file['css-files']) > 0){
            $css_files = $file['css-files'];
            for($k = 0; $k < count($css_files); $k++){
                for($l = 0; $l < count($css_files[$k]['location']); $l++){

                    if(strpos($css_files[$k]['file'], 'http')  !== false){
                        $load_files['css_files']['file'][] = $css_files[$k]['file'];
                    } else {
                        $load_files['css_files']['file'][] = module_base_path() . $key . '/' .$css_files[$k]['file'];
                    }

                    if(strpos($css_files[$k]['location'][$l], '/')){
                        $cn_fn_arr = explode('/', $css_files[$k]['location'][$l]);
                    	$load_files['css_files']['controller'][] = $cn_fn_arr[0];
                    	$load_files['css_files']['function'][] = $cn_fn_arr[1];
                    } else {
                        $load_files['css_files']['controller'][] = '*';
                        $load_files['css_files']['function'][] = '*';
                    }
                }
            }
        }
    }

	$files_array = array();
    foreach ($load_files as $key => $value) {
    	if($key == 'js_files'){
    		for($i = 0; $i < count($value['file']); $i++){
    			if(($controller == $value['controller'][$i] && $function == $value['function'][$i]) || ($value['controller'][$i] == '*' && $value['function'][$i] == '*')){
    				$files_array['js_files'][$i] = $value['file'][$i];
    			}
    		}
    	}
    	if($key == 'css_files'){
    		for($i = 0; $i < count($value['file']); $i++){
    			if(($controller == $value['controller'][$i] && $function == $value['function'][$i]) || ($value['controller'][$i] == '*' && $value['function'][$i] == '*')){
    				$files_array['css_files'][$i] = $value['file'][$i];
    			}
    		}
    	}
    }

    $files_array['js_files'] = isset($files_array['js_files']) && $files_array['js_files'] ? array_values($files_array['js_files']) : array();
    $files_array['css_files'] = isset($files_array['css_files']) && $files_array['css_files'] ? array_values($files_array['css_files']) : array();

    return $files_array;
}

function module_base_path()
{
	$CI =& get_instance();
	// return $CI->config->site_url().$CI->config->item('module_location').$CI->router->fetch_module();
	return $CI->config->site_url().$CI->config->item('module_location');
}

function check_active_extensions($module_name, $company_id) {

    $CI = & get_instance();
    if(!$CI->session->userdata('activated_modules')){
        $extensions = $CI->Extension_model->get_extensions(null, $company_id);
    
        $extensions_name = array();
        if($extensions){
            foreach($extensions as $extension)
            {
                if($extension['is_active'] == 1)
                    $extensions_name[] = $extension['extension_name'];
            }
        }
        
    } else {
        $extensions_name = $CI->session->userdata('activated_modules');
    }

    if(in_array($module_name, $extensions_name)){
        return true;
    } else {
        return false;
    }
}

function show_registration_link()
{
    $CI = & get_instance();

    $extensions = $CI->Extension_model->get_active_extensions(null, 'reseller_package');

    if($extensions && count($extensions) > 0) {
        return true;
    } else {
        return false;
    }
}

function auto_fill_credentials()
{
    $CI = & get_instance();

    $extensions = $CI->Extension_model->get_active_extensions(null, 'auto_populate_credentials');

    if($extensions && count($extensions) > 0) {
        return true;
    } else {
        return false;
    }
}

// Convert $change's date intervals of changes into a range of dates in the correct format
function get_array_with_range_of_dates($changes, $ota_id = null)
{
    $date_ranges = array();
    switch ($ota_id) {
        case SOURCE_ONLINE_WIDGET: // Roomsy's Online Booking Engine
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
        case SOURCE_BOOKING_DOT_COM: // Booking.com
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
        case SOURCE_EXPEDIA: // Expedia
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, TRUE);break;
        case SOURCE_MYALLOCATOR:
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
        case SOURCE_AGODA:
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
        case SOURCE_SITEMINDER:
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, TRUE);break;
        case SOURCE_CHANNEX:
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
        default:
            $date_ranges = get_array_with_range_of_dates_iso8601($changes, FALSE);break;
    }
    return $date_ranges;
}

function get_array_with_range_of_dates_iso8601($changes, $end_date_inclusive)
    {
        if (!isset($changes))
        {
            return null;
            
        } elseif (sizeof($changes) < 1)
        {
            return null;
        }
        
        $changes_indexed_by_date = array(); 
        $date_start = null;
        $last_change = null;
        foreach ($changes as $change)
        {   
            
            if ($last_change != null)
            {
                $change_detected = false;
                foreach ($change as $key => $value)
                {
                    if ($key != 'date')
                    {
                            // compare the actual number value to 2 decimal digits.
                            $change_in_two_decimal_digits = number_format(floatval($change[$key]), 2, ".", "");
                            $last_change_in_two_decimal_digits = number_format(floatval($last_change[$key]), 2, ".", "");
                            if ($change_in_two_decimal_digits != $last_change_in_two_decimal_digits) 
                            {
                                $change_detected = true;
                            }
                        
                    }
                }
                if (    !$change_detected   &&
                        $change['date'] == Date('Y-m-d', strtotime("+1 day", strtotime($last_change['date'])))
                )
                {
                    $last_change = $change;
                    continue;
                }
            }
            
            if ($date_start == null)
            {
                $date_start = $change['date'];
            }
            else
            {
                $changes_indexed_by_date[] = array('date_start'=>$date_start, 'date_end'=> $change['date']) + $last_change;
                $date_start = $change['date'];
                
            }
            $last_change = $change;     
            
        }
        $changes_indexed_by_date[] = array('date_start'=>$date_start, 'date_end'=> $last_change['date'])+$last_change ;

        return $changes_indexed_by_date;    
    }

function timeAgo($time_ago)
{

    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "1 hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
   
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
           $date = date("d M Y H:i:s",$time_ago);
            return "$date";
        }
    }
    
}

?>