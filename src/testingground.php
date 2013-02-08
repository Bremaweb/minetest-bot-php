<?php
$string="\x04\x00\xa0\x00";
$array=unpack("cchars/nint/nint2/nint3/nint4/nint5/nint6", $string);
var_dump($array);
?>
