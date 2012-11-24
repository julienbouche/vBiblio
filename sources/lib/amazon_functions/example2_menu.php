<html>
<head>
<title></title>
<style>
select {font-face:verdana;font-size:12px;height:20px; }
font { font-face:verdana;font-size:10px; }
a {text-decoration:none;font-weight:700;}
</style>
</head>
<body bgcolor="#FFCC33">

<?php

include('functions/amazon_functions.php');

if(!$Mode) { $Mode="books"; }

$Modes=amazon_modes();

echo "<form>\n<select name=\"Mode\" size=\"1\" OnChange=\"document.location.href='example2_menu.php?Mode='+this[this.selectedIndex].value;\">\n";
while(list($Key,$Value)=each($Modes)) {
  echo "<option value=\"$Key\"";
  if($Mode==$Key) echo " selected";
  echo ">$Value</option>\n";
 }
echo "</select>\n</form>\n<p>\n\n";


if($Mode) {
  $BrowseNodes=amazon_browsenodes($Mode);
  if($BrowseNodes) {
    echo "<table border=\"0\">\n";
    while (list($Key,$Value)=each($BrowseNodes)) {
      echo "<tr>\n<td bgcolor=\"#FF3333\" OnMouseOver=\"this.style.backgroundColor='#FF7171';\" OnMouseOut=\"this.style.backgroundColor='#FF3333';\"><a href=\"example2_content.php?Mode=$Mode&BrowseNode=$Key&BrowseNodeName=$Value\" target=\"content\"><font size=\"2\" face=\"verdana\" color=\"#FFFFFF\">$Value</font></a></td>\n</tr>\n";
     }
    echo "</table>\n\n";
  }
 }

php?>

</body>
</html>