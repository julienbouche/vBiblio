<html>
<head>
<title>amazon_functions example</title>
</head>
<body link=#000000 alink=#000000 vlink=#000000>
<font color=black size=2 face=verdana>

<?php

# Include amazon_functions
include('functions/amazon_functions.php');




# Display BrowseNodes
$BrowseNodes=amazon_browsenodes(books);
echo "<table border=0 align=left>\n";
while(list($Key,$Value)=each($BrowseNodes)) {
  echo "<tr>\n";
  echo "<td bgcolor='#f1f1f1' OnMouseOver=\"this.style.backgroundColor='#e1e1e1';\" OnMouseOut=\"this.style.backgroundColor='#f1f1f1';\"><a href=\"".$PHP_SELF."?BrowseNode=$Key&Mode=books\">$Value</a></td>\n";
  echo "</tr>\n";
 }
echo "</table><p><br>\n\n";




# Display searchform
echo amazon_create_searchform();
echo "<p><br>";



# Search results
if($SearchKeyword || $BrowseNode) {

  # Perform search
  if($BrowseNode)
   { $Data=amazon_browse_node($BrowseNode,$Mode); }
  else
   { $Data=amazon_search($SearchKeyword,$SearchType,$SearchMode); }

   # Transform searchresult-array to HTML-Table
   $HTMLTable=amazon_create_productlist($Data,10,"example1_productinfo.php");

   echo $HTMLTable;
 }


php?>

</font>
</body>
</html>