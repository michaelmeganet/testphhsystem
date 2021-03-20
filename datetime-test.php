<?php

$input = '06/10/2011 19:00:02'; 
$date = strtotime($input); 
echo date('d/M/Y h:i:s', $date); 

echo "<br> ";

$input2 =  '2021-03-01 05:09:53';
$date2 = strtotime($input2);
echo date('Y-m-d h:i:s', $date2);

echo "<br> ";

$input3 =  '';
$date3 = strtotime($input3);
echo date('Y-m-d h:i:s', $date3);
