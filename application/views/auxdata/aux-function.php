<?php 

function convertToHoursMins($time, $format = '%02d:%02d:%02d') {
    if ($time < 1) {
        return 0;
    }
    $hours = floor($time / 3600);
    $minutes = floor($time % 3600) / 60;
    $second = ($time % 60);
    return sprintf($format, $hours, $minutes, $second);
}

function isohToBadge($isoh) {
    $out = '';
    if ($isoh == 1) {
        $out = '<br><span class="badge badge-success py-0 px-1">OH</span>';
    } else {
        $out = '<br><span class="badge badge-warning py-0 px-1">OT</span>';
    }
    return $out;
}

function isohToChecked($isoh, $val) {
    in_array($val, $isoh) ? $out = 'checked' : $out = '';
    return $out;
}

// for Admin/Superadmin Access
$allowedChangeAgent = in_array($this->session->userdata('role_access'), ['1', '5', '6', '9']);