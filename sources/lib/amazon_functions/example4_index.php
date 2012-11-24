<?php

include('functions/amazon_functions.php');

$Products=amazon_search_author("Tom Clancy");

echo "<p><b>Books by Tom Clancy:</b></p>";

while(list($Key,$Value)=each($Products[Details])) {
  echo "<p>Title: ".$Value[ProductName][0]."<br>";
  echo "Author: ".$Value[Authors][0][Author][0]."</p>";
 }

php?>
