<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 


include APPPATH.'libraries/Moment/Moment.php';
include APPPATH.'libraries/Moment/MomentLocale.php';
include APPPATH.'libraries/Moment/MomentPeriodVo.php';
include APPPATH.'libraries/Moment/MomentHelper.php';
include APPPATH.'libraries/Moment/MomentFromVo.php';
include APPPATH.'libraries/Moment/MomentException.php';

function MonthOnly($mysql_date) {

	$date = mysql_to_unix($mysql_date);

	return mdate('%M', $date);
}

function DayOnly($mysql_date) {

	$date = mysql_to_unix($mysql_date);

	return mdate('%d', $date);
}

function NumDays($start, $end) {
	$date = new \Moment\Moment($start);
	$date->subtractDays(1);
    return $date->from($end)->getDays();

}

function toHours($sec) {
	return sprintf('%02d:%02d:%02d', ($sec/3600),($sec/60%60), $sec%60);
}