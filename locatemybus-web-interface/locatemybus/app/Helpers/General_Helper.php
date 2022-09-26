<?php

function checkDateFormat($date, $format='Y-m-d'){
  $req_format = DateTime::createFromFormat($format, $date);
  $is_correct = ($req_format) && ($req_format->format($format) === $date);
  return $is_correct;
}

function makeDateFromString($date_string){
  $date = "";
  $date .= substr($date_string, 0, 4);
  $date .= '-';
  $date .= substr($date_string, 4, 2);
  $date .= '-';
  $date .= substr($date_string, 6, 2);
  return $date;
}

?>
