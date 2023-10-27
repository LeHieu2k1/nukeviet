<?php
 
/** 
* @Project NUKEVIET 4.5
* @Author VINADES.,JSC (contact@vinades.vn) 
* @Copyright (C) 2015 VINADES.,JSC. All rights reserved 
* @License GNU/GPL version 2 or any later version 
* @Createdate Tue, 02 Jun 2015 07:53:31 GMT 
*/
 
if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

 
// Code xử lý ở đây

$page_title = $lang_module['statistical'];

$current_month_num = date('n', NV_CURRENTTIME);
$current_year = date('Y', NV_CURRENTTIME);
$module_name='news';
$op='statistical';



//Thong ke theo nam
$max = 0;
$year_min=$current_year;
$total = 0;
$year_list = [];
$data_year = [];
$result = $db->query('SELECT publtime FROM nv4_vi_news_rows');
while (list($time) = $result->fetch(3)) {
    $year=nv_date('Y', $time);
    if (empty($data_year)) { 
        $data_year[$year]=1;
    }  
    else {
        foreach ($data_year as $key => $val) {
            if ($key == $year) {
                $data_year[$year] = $val + 1;
            } 
            else $data_year[$year] = 1;
        }
    }
}


foreach($data_year as $key => $val) {
    $year_list[$key] = $val;
    if ($val > $max) {
        $max = $val;
    }
    if($year_min>$key) {
        $year_min=$key;
    }
    $total = $total + $val;
}

$ctsy = [];
$ctsy['caption'] = $year_min;
$ctsy['rows'] = $year_list;
$ctsy['current_year'] = $current_year;
$ctsy['max'] = $max;
$ctsy['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

// theo thang
$month_list = [];
$month_list['1'] = ['fullname' => $lang_global['january'], 'count' => 0];
$month_list['2'] = ['fullname' => $lang_global['february'], 'count' => 0];
$month_list['3'] = ['fullname' => $lang_global['march'], 'count' => 0];
$month_list['4'] = ['fullname' => $lang_global['april'], 'count' => 0];
$month_list['5'] = ['fullname' => $lang_global['may'], 'count' => 0];
$month_list['6'] = ['fullname' => $lang_global['june'], 'count' => 0];
$month_list['7'] = ['fullname' => $lang_global['july'], 'count' => 0];
$month_list['8'] = ['fullname' => $lang_global['august'], 'count' => 0];
$month_list['9'] = ['fullname' => $lang_global['september'], 'count' => 0];
$month_list['10'] = ['fullname' => $lang_global['october'], 'count' => 0];
$month_list['11'] = ['fullname' => $lang_global['november'], 'count' => 0];
$month_list['12'] = ['fullname' => $lang_global['december'], 'count' => 0];

$month_list2 = array_chunk($month_list, $current_month_num, true);
$month_list2 = $month_list2[0];
$month_list2 = "'" . implode("','", array_keys($month_list2)) . "'";

$max = 0;
$total = 0;
$y = $nv_Request->get_title('y', 'get', '');
$y = str_replace('+', ' ', $y);
$yhtml = nv_htmlspecialchars($y);
$where = [];
$page = $nv_Request->get_int('page', 'get', 1);
$checkss = $nv_Request->get_title('checkss', 'get', '');
if(empty($y)) $y_search = $current_year;
else $y_search = $yhtml;
if($y_search > $current_year) $y_search = $current_year;
if($y_search < $year_min) $y_search = $year_min; 

$sql = 'SELECT publtime FROM nv4_vi_news_rows';
$result = $db->query($sql);

while (list($time) = $result->fetch(3)) {
    $year=nv_date('Y', $time);
    $month=nv_date('m', $time);
    if($year==$y_search) {
        if(array_keys($month_list)[$month-1] == $month) {
            $month_list[ltrim($month, '0')]['count'] = $month_list[ltrim($month, '0')]['count']+1;
        } 
        else{
            $month_list[ltrim($month, '0')]['count'] = 1;
        } 
    }
}

$m = 1;
while ($m <= 12) {
    $count = $month_list[$m]['count'];
    if ($count > $max) {
        $max = $count;
    }
    $total = $total + $count;
    $m++;
}


$ctsm = [];
$ctsm['caption'] = sprintf($y_search, $current_year);
$ctsm['rows'] = $month_list;
$ctsm['current_month'] = date('M', NV_CURRENTTIME);
$ctsm['max'] = $max;
$ctsm['total'] = [$lang_global['total'], number_format($total, 0, ',', '.')];

global $module_info, $lang_module, $lang_global;

$xtpl = new XTemplate('statistical.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

// Thống kê tháng của năm
$xtpl->assign('CTS', $ctsm);
$xtpl->assign('LANG_DATA', NV_LANG_DATA);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('Y', $yhtml);

$data_label = [];
$data_value = [];

foreach ($ctsm['rows'] as $key => $m) {
    $data_label[] = $m['fullname'];
    $data_value[] = $m['count'];
}

$xtpl->assign('DATA_LABEL', '"' . implode('", "', $data_label) . '"');
$xtpl->assign('DATA_VALUE', implode(', ', $data_value));
$xtpl->assign('year_min', $year_min);
$xtpl->assign('year_max', $current_year);

$xtpl->parse('main.month');

// Thống kê theo năm
$xtpl->assign('CTS', $ctsy);
$xtpl->assign('DATA_LABEL', '"' . implode('", "', array_keys($ctsy['rows'])) . '"');
$xtpl->assign('DATA_VALUE', implode(', ', $ctsy['rows']));

$xtpl->parse('main.year');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
