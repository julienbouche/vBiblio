<?php

include('functions/amazon_functions.php');

$Data=amazon_search_asin("B00005ATQ9");
$HTMLProductInformation=amazon_create_productinfo($Data);
echo $HTMLProductInformation;

php?>
