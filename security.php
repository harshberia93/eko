<?php
function make_safe($variable) 
{
    $variable = strip_tags(mysql_real_escape_string(trim($variable)));
   	return $variable; 
}
?>
