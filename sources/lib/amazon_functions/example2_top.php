<html>
<head>
<title></title>
<style>
select,input {font-face:verdana;font-size:12px;height:20px; }
font { font-face:verdana;font-size:12px; }
.small { font-face:verdana;font-size:10px; }
.Marquee {FILTER: Alpha(opacity=90,finishopacity=0, style=1); }
body {background-image: url(example2_top.jpg);background-repeat:no-repeat;background-position:right;}
.Table {border-bottom-style:solid;border-bottom-color:#FFD53F;border-bottom-width:2px;}
a {font-weight:700;}
</style>
</head>

<body leftmargin=0 marginwidth=0 topmargin=0 marginheight=0 bgcolor="#FFCC33" link="#FF3333" alink="#FF3333" vlink="#FF3333">

<table border="0" width="100%" height="59" cellpadding=0 cellspacing=0 class="Table">
<form method=get action="example2_content.php" target="content">
<tr>
<td valign=bottom align=left height=55 width=60%><font size="2" face="verdana">



<?php

include('functions/amazon_functions.php');

if (substr_count(getenv("HTTP_USER_AGENT"),"MSIE")) {
  echo "<marquee direction=left width=75% align=left scrollamount=2 scrolldelay=1 class=\"Marquee\">";

  $Data=amazon_browse_node("1000","books");
  for($x=0;$x<=10;$x++) {
     if($Data[Details][$x][ProductName][0]) {
       echo "<b><a href=\"example2_content.php?ASIN=".$Data[Details][$x][Asin][0]."\" target=\"content\">";
       echo "<img align=\"absolute\" src=\"".$Data[Details][$x][ImageUrlSmall][0]."\" border=\"0\" height=\"50\"> ";
       echo $Data[Details][$x][ProductName][0]."</a></b> by ";
       echo $Data[Details][$x][Authors][0][Author][0]." - ".$Data[Details][$x][OurPrice][0].str_repeat("&nbsp;",10);
      }
   }
  echo "</marquee>";
}
php?>
</font></td>
<td width=40% align=right valign=bottom><font size="1" face="verdana"><span class='small'>
<a href="example2_content.php?Searchform=1" target="content">Extended search</a><br>
Search for: <input type=text size=20 name='SearchKeyword'><br>
<select name='SearchMode' size=1>

<?php
$Modes=amazon_modes();
while(list($Key,$Value)=each($Modes)) {
  echo "<option value=\"$Key\">$Value</option>\n";
 }
php?>

</select>
<input type=submit value='Go!'>
</span></font></td>
<input type=hidden name='Search' value='1'>
<input type=hidden name='SearchType' value='keyword'>
</tr>
<tr>
<td height="4"></td>
</tr>
</form>
</table>

</font>
</body>
</html>