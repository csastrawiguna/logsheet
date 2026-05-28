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
