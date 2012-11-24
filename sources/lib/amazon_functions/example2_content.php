<html>
<head>
<title></title>
<style>
select,input {font-face:verdana;font-size:12px;height:20px; }
font { font-face:verdana;font-size:12px; }
.para1 { margin-top: -45px; margin-left: 55px; margin-right: 10px; font-family: "verdana"; font-size: 30px; line-height: 35px; text-align: left; font-weight:600; color: #FF3333; }
.para2 { margin-top: 15px; margin-left: 45px; margin-right: 50px; font-family: "font1, Arial Black"; font-size: 40px; line-height: 40px; text-align: left; color: #FFD53F; }
a {font-weight:700;}
</style>
</head>

<?php

include('functions/amazon_functions.php');

if($BrowseNode) {
  $Data=amazon_browse_node($BrowseNode,$Mode);
  $Output=amazon_create_productlist($Data,10,"example2_content.php");
  if($BrowseNodeName) { $Title=$BrowseNodeName; }
  else { $Title="BrowseNode"; }
 }
elseif($ASIN && $Plain) {
echo "---$ASIN---";

  $Data=amazon_search_asin($ASIN,"HEAVY");
  $Title=$Data[Details][0][Catalog][0];
  print_r($Data);
 }
elseif($ASIN) {
  $Data=amazon_search_asin($ASIN,"HEAVY");
  $Output=amazon_create_productinfo($Data);
  $Title=$Data[Details][0][Catalog][0];
 }
elseif($Search) {
  $Data=amazon_search($SearchKeyword,$SearchType,$SearchMode,"LITE");
  $Output=amazon_create_productlist($Data,10,"example2_content.php");
  $Title="Search Result";
 }
elseif($Searchform) {
  $Output=amazon_create_searchform();
  $Title="Extended Search";
 }
else {
  $Output="Welcome to our online-store!<br>We hope you will enjoy our selection of products specially composed for your convenience. If you have any product-suggestions for our little shop, please feel free to contact us at <a href=\"mailto:info@filzhut.net\">info@filzhut.net</a>. Have a good time and hope to see you again soon.<p>Wanna create such a shop or another application based on amazons' products on your own? <a href=\"http://associatesshop.filzhut.net/download/\" target=\"_blank\">Download the amazon_functions</a> for PHP to get easy access to the amazon.com&trade; XML-API.";
  $Title="Welcome";
 }

php?>

<body background="example2_bg.gif" bgcolor="#FFCC33" link="#FF3333" alink="#FF3333" vlink="#FF3333">
<font size="2" face="verdana">

<div CLASS="para2" align="center">
<p>
<?php
echo  $Title;
php?>
</p>
</div>
<div CLASS="para1" align="center">
<p>
<?php
echo  $Title;
php?>
</p>
</div>

<?php
echo $Output;
php?>

</font>
</body>
</html>