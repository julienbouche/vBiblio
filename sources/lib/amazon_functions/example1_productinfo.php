<?php

include('functions/amazon_functions.php');

$Data=amazon_search_asin($ASIN,"HEAVY");
$ProductInfoHTML=amazon_create_productinfo($Data);

echo $ProductInfoHTML;

php?>