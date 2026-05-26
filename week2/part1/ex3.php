<?php
$str = '25.50';

$floatVal = (float)$str;
$intVal = (int)$floatVal;

echo gettype($floatVal) . "(" . $floatVal . "), ";
echo gettype($intVal) . "(" . $intVal . ")";
?>