<?php

# Filzhut.net amazon_functions for PHP
# Version 0.906 beta 23:48 15.04.2004
# Download:   http://associatesshop.filzhut.net/download/
#
# Free for non-commercial websites with referral fees up to 50$/quarter
# All others please contact info@filzhut.de for registering
#
# We are not responsible for any loss of information
# or money caused by this application.
# Before using this script please make sure that your
# associates-id is inserted correctly in all positions.
#
# Copyright by Daniel Filzhut 2002 and following
# Web:   http://www.filzhut.net
# Web:   http://www.filzhut.de
# eMail: info@filzhut.de




#########
# Setup #
#########

define ("DEVTOKEN", "AKIAJIUJ4RDS3YZCFBDQ");			# Your developers token
define ("ASSOCIATESID", "vbiblio-20");	# Your associates-id
define ("AMAZONCACHEDIR", "functions/cache/");	# Your caching-directory
define ("AMAZONCACHEMAXTIME", "86400");		# Cache for how long (seconds)?



###############################
# No changes beyond this line #
###############################

if(!DEVTOKEN) {
  echo "<p><b>Warning!</b> Your scripts won't work without an amzon.com developers token. Visit <a href='http://www.amazon.com/webservices/' target='_blank'>http://www.amazon.com/webservices/</a> to get one for free. Afterwards please edit the file 'amazon_functions.php' and insert the token as described in the <a href='http://associatesshop.filzhut.net/download/amazon_functions/documentation/index.html' target='_blank'>amazon_functions documentation</a>.</p>";
}

# ProductList
$amazon_productinfo_table_width=500;
$amazon_productinfo_table_align="left";
$amazon_productlist_table_width=400;
$amazon_productlist_table_align="left";
$amazon_productlist_table_items=10;
$amazon_teaserlist_table_bordercolor="black";
$amazon_teaserlist_table_width=150;
$amazon_teaserlist_table_align="right";
$amazon_teaserlist_table_items=5;
$amazon_font_face="verdana";
$amazon_font_size="2";
$amazon_font_color="black";

# Return savings
function amazon_calculate_savings($ListPrice,$OurPrice) {
  if($ListPrice && $OurPrice && $ListPrice!=$OurPrice) {
    $ListPrice=str_replace("$","",$ListPrice);
    $OurPrice=str_replace("$","",$OurPrice);
    $Saved_Money=$ListPrice-$OurPrice;
    $Saved_Percentage=$Saved_Money/$ListPrice*100;
    #$Saved_Percentage=substr($Saved_Percentage,0,strpos($Saved_Percentage,".")+3);
    $Saved_Percentage=round($Saved_Percentage);
    $ReturnArray[0]=$Saved_Money;
    $ReturnArray[1]=$Saved_Percentage;
   }
  else { $ReturnArray=array("",""); }
  return $ReturnArray;
 }


# Returns searchform
function amazon_create_searchform ($URL='',$Image='') {
  $Font="<font color=\"".$GLOBALS["amazon_font_color"]."\" size=\"".$GLOBALS["amazon_font_size"]."\" face=\"".$GLOBALS["amazon_font_face"]."\">";
  if(!$URL)
   { $URL=$PHP_SELF; }
  $SearchForm="<!--
Created using Filzhut.net amazon_functions for PHP
Download: http://associatesshop.filzhut.net/download/
//-->
<table border=\"0\">
<form name=\"SearchForm\" method=\"POST\" action=\"$URL\">
<tr>
<td>$Font"."Type:</font></td>
<td><select name=\"SearchType\" onChange=\"changemodes();\">
<option value=\"author\">Author</option>
<option value=\"artist\">Artist</option>
<option value=\"actor\">Actor</option>
<option value=\"director\">Director</option>
<option value=\"keyword\" selected>Keyword</option>
<option value=\"manufacturer\">Manufacturer</option>
</select></td>
</tr>
<tr>
<td>$Font"."Mode:</font></td>
<td><select name=\"SearchMode\">
<option value=\"books\">Books</option>
</select></td>
</tr>
<tr>
<td>$Font"."Search for:</font></td>
<td><input type=\"text\" size=\"20\" name=\"SearchKeyword\"> <input type=";
  if($Image) { $SearchForm.="image src=\"$Image\""; }
  else { $SearchForm.="submit"; }
  $SearchForm.=" value=\"Search\"></td>
</tr>
<input type=hidden name='Search' value='1'>
</form>
</table>

<script language=\"Javascript\">
changemodes();
function setfield (Select,No,FieldText,FieldValue) {
  Select[No].text=FieldText;
  Select[No].value=FieldValue;
 }
function changemodes() {
  var a=document.SearchForm.SearchType.options;
  var b=document.SearchForm.SearchMode.options;
  var i=a.selectedIndex;
  if(a[i].value==\"author\") {
    b.length = 1;
    setfield(b,\"0\",\"Books\",\"books\")
   }
  if(a[i].value==\"artist\") {
    b.length = 2;
    setfield(b,\"0\",\"Popular music\",\"music\")
    setfield(b,\"1\",\"Classical music\",\"classical\")
   }
  if(a[i].value==\"actor\" || a[i].value==\"director\") {
    b.length = 2;
    setfield(b,\"0\",\"DVD\",\"dvd\")
    setfield(b,\"1\",\"VHS\",\"vhs\")
   }
  if(a[i].value==\"keyword\" || a[i].value==\"manufacturer\") {
    b.length = 17;
    setfield(b,\"0\",\"Books\",\"books\")
    setfield(b,\"1\",\"Classical Music\",\"classical\")
    setfield(b,\"2\",\"DVD\",\"dvd\")
    setfield(b,\"3\",\"Popular Music\",\"music\")
    setfield(b,\"4\",\"Video\",\"vhs\")
    setfield(b,\"5\",\"---------\",\"books\")
    setfield(b,\"6\",\"Baby\",\"baby\")
    setfield(b,\"7\",\"Camera & Photo\",\"photo\")
    setfield(b,\"8\",\"Computers\",\"pc-hardware\")
    setfield(b,\"9\",\"Computer & Video Games\",\"videogames\")
    setfield(b,\"10\",\"Electronics\",\"electronics\")
    setfield(b,\"11\",\"Kitchen & Housewares\",\"kitchen\")
    setfield(b,\"12\",\"Magazines\",\"magazines\")
    setfield(b,\"13\",\"Outdoor Living\",\"garden\")
    setfield(b,\"14\",\"Software\",\"software\")
    setfield(b,\"15\",\"Tools & Hardware\",\"universal\")
    setfield(b,\"16\",\"Toys & Games\",\"toys\")

   }
  b[0].selected = true
 }
</script>";
  return $SearchForm;
 }


# Returns Product-List
function amazon_create_productlist ($Array,$Items='',$Link='',$Align='',$Width='') {
  if(!$Array || !is_array($Array))
   { return ""; }
  if(!$Align) { $Align=$GLOBALS["amazon_productlist_table_align"]; }
  if(!$Width) { $Width=$GLOBALS["amazon_productlist_table_width"]; }
  if(!$Items) { $Items=$GLOBALS["amazon_productlist_table_items"]; }
  if($Items && sizeof($Array[Details])>$Items) { $DisplyItems=$Items;  }
  else { $DisplyItems=sizeof($Array[Details]); }
  $Font="<font color=\"".$GLOBALS["amazon_font_color"]."\" size=\"".$GLOBALS["amazon_font_size"]."\" face=\"".$GLOBALS["amazon_font_face"]."\">";
  $List="<!--
Created using Filzhut.net amazon_functions for PHP
Download: http://associatesshop.filzhut.net/download/
//-->";
  $List.="<table border=\"0\" width=\"$Width\" align=\"$Align\">\n";
  for ($x=0;$x<=$DisplyItems-1;$x++) {
    $Image=$Array[Details][$x][ImageUrlMedium][0];
    $ProductName=$Array[Details][$x][ProductName][0];
    if($Author=$Array[Details][$x][Authors][0][Author]) {}
    elseif($Author=$Array[Details][$x][Artists][0][Artist]) {}
    else { $Author="";}
    $ReleaseDate=$Array[Details][$x][ReleaseDate][0];
    $Manufacturer=$Array[Details][$x][Manufacturer][0];
    $ListPrice=$Array[Details][$x][ListPrice][0];
    $OurPrice=$Array[Details][$x][OurPrice][0];
    $UsedPrice=$Array[Details][$x][UsedPrice][0];
    $Asin=$Array[Details][$x][Asin][0];
    $AssociatesID=ASSOCIATESID;
    if(!$AssociatesID) { $AssociatesID="leannrimesfansit"; }
    if($Link) {
      if(substr_count($Link,"?")) { $LinkURL=$Link."&ASIN=$Asin"; }
      else { $LinkURL=$Link."?ASIN=$Asin"; }
      $LinkTarget="_self";
     }
    else { $LinkURL="http://www.amazon.com/exec/obidos/ASIN/$Asin/$AssociatesID"; $LinkTarget="_blank"; }
    $LinkStartStr="<a href=\"$LinkURL\" target=\"$LinkTarget\">";
    $LinkEndStr="</a>";
    $List.="<tr>\n";
    $List.="<td valign=top width=\"150\">";
    if($Image)
     { $List.="$LinkStartStr<img align=right src=\"$Image\" border=\"0\">$LinkEndStr"; }
    $List.="</td>\n<td valign=top>$Font\n";
    $List.="$LinkStartStr<big><b>$ProductName</b></big>$LinkEndStr";
    if(is_array($Author)) {
      $List.="<br>by ";
      for ($y=0;$y<=sizeof($Author)-1;$y++) {
        $List.=$Author[$y];
        if($y+1<=sizeof($Author)-1) { $List.=", "; }
       }
     }
    $List.="<p>";
    if($Manufacturer) { $List.="$Manufacturer<br>"; }
    if($ReleaseDate) { $List.="Released: $ReleaseDate"; }
    $List.="<p>";
    if($ListPrice!=$OurPrice) {
      $List.="List Price: ";
      if($OurPrice) { $List.="<strike>"; }
      else { $List.="<b>"; }
      $List.="$ListPrice<br>";
      if($OurPrice) { $List.="</strike>"; }
      else { $List.="</b>"; }
     }
    if($OurPrice) { $List.="Our Price: <b>$OurPrice</b><br>"; }
    if($UsedPrice) { $List.="Used Price: $UsedPrice<br>"; }
    $List.="<p></p><br>";
    $List.="";
    $List.="</font></td>\n";
    $List.="</tr>\n";
   }
  $List.="</table>\n\n";
  return $List;
 }



# Returns small Product-List
function amazon_create_teaserlist ($Array,$Items='',$Link='',$Align='',$Description='') {
  if(!$Array || !is_array($Array))
   { return ""; }
  if(!$Align) { $Align=$GLOBALS["amazon_teaserlist_table_align"]; }
  if(!$Width) { $Width=$GLOBALS["amazon_teaserlist_table_width"]; }
  if(!$Items) { $Items=$GLOBALS["amazon_teaserlist_table_items"]; }
  if(!$BorderColor) { $BorderColor=$GLOBALS["amazon_teaserlist_table_bordercolor"]; }
  if($Items && sizeof($Array[Details])>$Items) { $DisplyItems=$Items;  }
  else { $DisplyItems=sizeof($Array[Details]); }
  $Font="<font color=\"".$GLOBALS["amazon_font_color"]."\" size=\"".$GLOBALS["amazon_font_size"]."\" face=\"".$GLOBALS["amazon_font_face"]."\">";
  $List="<!--
Created using Filzhut.net amazon_functions for PHP
Download: http://associatesshop.filzhut.net/download/
//-->";
  $List.="<table border=\"0\" width=\"$Width\" align=\"$Align\" style=\"border-style:solid;border-width:1px;border-color:'$BorderColor';\">\n";
  if($Description) { $List.="<tr><td colspan=2 valign=top align=center style=\"border-bottom-style:solid;border-bottom-color:$BorderColor;border-bottom-width:1px;\">$Font<b>$Description</b></font></td>\n</tr>\n"; }
  for ($x=0;$x<=$DisplyItems-1;$x++) {
    $Image=$Array[Details][$x][ImageUrlSmall][0];
    $ProductName=$Array[Details][$x][ProductName][0];
    if($Author=$Array[Details][$x][Authors][0][Author]) {}
    elseif($Author=$Array[Details][$x][Artists][0][Artist]) {}
    else { $Author="";}
    $ListPrice=$Array[Details][$x][ListPrice][0];
    $OurPrice=$Array[Details][$x][OurPrice][0];
    $Asin=$Array[Details][$x][Asin][0];
    $AssociatesID=ASSOCIATESID;
    if(!$AssociatesID) { $AssociatesID="leannrimesfansit"; }
    if($Link) {
      if(substr_count($Link,"?")) { $LinkURL=$Link."&ASIN=$Asin"; }
      else { $LinkURL=$Link."?ASIN=$Asin"; }
      $LinkTarget="_self";
     }
    else { $LinkURL="http://www.amazon.com/exec/obidos/ASIN/$Asin/$AssociatesID"; $LinkTarget="_blank"; }
    $LinkStartStr="<a href=\"$LinkURL\" target=\"$LinkTarget\">";
    $LinkEndStr="</a>";
    $List.="<tr>\n";
    $List.="<td valign=top>";
    if($Image)
     { $List.="$LinkStartStr<img align=right src=\"$Image\" border=\"0\">$LinkEndStr"; }
    $List.="</td>\n<td valign=top>$Font<small>\n";
    $List.="$LinkStartStr<b>$ProductName</b>$LinkEndStr";
    if($Author[0]) { $List.="<br>by $Author[0]<br>"; }
    if($OurPrice) { $List.="<br>Our Price: <b>$OurPrice</b><br>"; }
    $List.="</small></font></td>\n";
    $List.="</tr>\n";
   }
  $List.="</table>\n\n";
  return $List;
 }



# Return 'AddTo'-Form
function amazon_create_cartform ($ASIN,$Action='Cart',$Image='') {
  $Action=strtolower($Action);
  $PossibleActions=array(
                         "submit.add-to-cart"=>"cart",
                         "submit.add-to-registry.wishlist"=>"wishlist",
                         "submit.add-to-registry.wedding"=>"wedding"
                        );
  $SubmitValues=array(
                         "Buy from Amazon.com"=>"cart",
                         "Add to Amazon.com Wishlist"=>"wishlist",
                         "Add to Amazon.com Wedding Registry"=>"wedding"
                        );
  if(!in_array($Action,$PossibleActions))
   { return ""; }
  if(!$ASIN)
   { return ""; }

  $AssociatesID=ASSOCIATESID;
  if(!$AssociatesID)
   { $AssociatesID="leannrimesfansit"; }

  $SubmitValues_flip=array_flip($SubmitValues);
  $PossibleActions_flip=array_flip($PossibleActions);

  # Old version
  #$Form="<form method=\"POST\" action=\"http://www.amazon.com/o/dt/assoc/handle-buy-box=$ASIN/stores/detail/one-click-thank-you-confirm\" target=\"_blank\">\n";
  #$Form.="<input type=\"hidden\" name=\"asin\" value=\"$ASIN\">\n";
  #$Form.="<input type=\"hidden\" name=\"asin.$ASIN\" value=\"1\">\n";
  #$Form.="<input type=\"hidden\" name=\"$ASIN\" value=\"1\" size=\"1\">\n";
  #$Form.="<input type=\"hidden\" name=\"tag-value\" value=\"$AssociatesID\">\n";
  #$Form.="<input type=\"hidden\" name=\"dev-tag-value\" value=\"".DEVTOKEN."\">\n";
  #$Form.="<input type=\"hidden\" name=\"template-name\" value=\"stores/detail/one-click-thank-you-confirm\">\n";

  # Newer Version
  $Form="<!--
Created using Filzhut.net amazon_functions for PHP
Download: http://associatesshop.filzhut.net/download/
//-->\n";
  $Form.="<form method=\"POST\" action=\"http://www.amazon.com/o/dt/assoc/handle-buy-box=$ASIN\" target=\"_blank\">\n";
  $Form.="<input type=\"hidden\" name=\"asin.$ASIN\" value=\"1\">\n";
  $Form.="<input type=\"hidden\" name=\"tag-value\" value=\"$AssociatesID\">\n";
  $Form.="<input type=\"hidden\" name=\"tag_value\" value=\"$AssociatesID\">\n";
  $Form.="<input type=\"hidden\" name=\"dev-tag-value\" value=\"".DEVTOKEN."\">\n";
  $Form.="<input type=\"";
  if($Image)
   { $Form.="image\" src=\"$Image"; }
  else
   { $Form.="submit"; }
  $Form.="\" name=\"".$PossibleActions_flip[$Action]."\" value=\"".$SubmitValues_flip[$Action]."\">\n";
  $Form.="</form>\n";
  return $Form;
 }

# Search by author
function amazon_search_author($Query,$Type='lite',$Page='1',$Format='xml') {
  $Data=amazon_search($Query,"author","books",$Type,$Page,$Format);
  return $Data;
 }

# Search by artist
function amazon_search_artist($Query,$Mode='music',$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array("music","classical");
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($Query,"artist",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Search by actor
function amazon_search_actor($Query,$Mode='dvd',$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array("dvd","vhs","video");
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($Query,"actor",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Search by director
function amazon_search_director($Query,$Mode='dvd',$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array("dvd","vhs","video");
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($Query,"director",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Search by manufacturer/publisher/lable
function amazon_search_manufacturer($Query,$Mode,$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array_flip(amazon_modes());
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($Query,"manufacturer",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Browse a listmania-list
function amazon_browse_listmania($BrowseID,$Type='lite',$Format='xml') {
  $Data=amazon_search($BrowseID,"listmania",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Browsenode
function amazon_browse_node($BrowseID,$Mode,$Type='lite',$Page='1',$Format='xml') {
  $Data=amazon_search($BrowseID,"browsenode",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Keyword
function amazon_search_keyword($Query,$Mode,$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array_flip(amazon_modes());
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($Query,"keyword",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# Similarity
function amazon_search_similarity($Query,$Type='lite',$Page='1',$Format='xml') {
  $Data=amazon_search($Query,"similarity",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# ASIN
function amazon_search_asin($ASINS,$Type='lite',$Format='xml') {
  $ASINS=str_replace(" ",",",$ASINS);
  $ASINS=str_replace(";",",",$ASINS);
  $Data=amazon_search($ASINS,"asin",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# ISBNs
function amazon_search_isbn($ISBNS,$Type='lite',$Format='xml') {
  $ISBNS=str_replace(" ",",",$ISBNS);
  $ISBNS=str_replace(";",",",$ISBNS);
  $Data=amazon_search($ISBNS,"asin","books",$Type,$Page,$Format);
  return $Data;
 }

# UPC
function amazon_search_upc($UPCS,$Mode='music',$Type='lite',$Format='xml') {
  $UPCS=str_replace(" ",",",$UPCS);
  $UPCS=str_replace(";",",",$UPCS);
  $OptionalModes=array("music","classical");
  if(!in_array($Mode,$OptionalModes))
   { return ""; }
  $Data=amazon_search($UPCS,"upc",$Mode,$Type,$Page,$Format);
  return $Data;
 }

# General search function
function amazon_search ($Query,$SearchType,$Mode,$Type='lite',$Page='1',$Format='xml') {
  $OptionalModes=array_flip(amazon_modes());
  if($Mode && !in_array($Mode,$OptionalModes))
   { return ""; }
  $Type=strtolower($Type);
  if($Type!="lite" && $Type!="heavy")
   { return ""; }
  $SearchType=strtolower($SearchType);
  $AvailibleSearchTypes=array(
			"KeywordSearch"=>"keyword",
			"BrowseNodeSearch"=>"browsenode",
			"AsinSearch"=>"asin",
			"UpcSearch"=>"upc",
			"AuthorSearch"=>"author",
			"ArtistSearch"=>"artist",
			"ActorSearch"=>"actor",
			"DirectorSearch"=>"director",
			"ManufacturerSearch"=>"manufacturer",
			"ListManiaSearch"=>"listmania",
			"SimilaritySearch"=>"similarity",
                        );
  if(!in_array($SearchType,$AvailibleSearchTypes))
   { return ""; }

  $AssociatesID=ASSOCIATESID;
  if(!$AssociatesID)
   { $AssociatesID="leannrimesfansit"; }

  # Create URL
  $URL="http://xml.amazon.com/onca/xml3?v=3.0&t=".urlencode($AssociatesID)."&dev-t=".urlencode(DEVTOKEN)."&";
  $AvailibleSearchTypes_flip=array_flip($AvailibleSearchTypes);
  $URL.=$AvailibleSearchTypes_flip[$SearchType]."=";
  $URL.=str_replace("%2C",",",urlencode(strtoupper($Query)));
  if($SearchType!="listmania" && $SearchType!="asin")
   { $URL.="&mode=".urlencode($Mode); }
  $URL.="&type=$Type";
  if($SearchType!="listmania" && $SearchType!="asin" && $SearchType!="upc")
   { $URL.="&page=".urlencode($Page); }
  $URL.="&f=".urlencode($Format);
  $Data=amazon_xml_ParseXML($URL);
  $Data=$Data[ProductInfo][0];
  return $Data;
 }


# Modes
function amazon_modes() {
	$Modes=array(
		"books"=>"Books",
		"magazines"=>"Magazines",
		"music"=>"Popular Music",
		"classical"=>"Classical Music",
		"vhs"=>"Video",
		"dvd"=>"DVD",
		"toys"=>"Toys & Games",
		"baby"=>"Baby",
		"videogames"=>"Computer & Video Games",
		"electronics"=>"Electronics",
		"software"=>"Software",
		"universal"=>"Tools & Hardware",
		"garden"=>"Outdoor Living",
		"kitchen"=>"Kitchen & Housewares",
		"photo"=>"Camera & Photo",
		"pc-hardware"=>"Computers",
                );
  return $Modes;
 }

# BrowseNodes
function amazon_browsenodes($BrowseNode='0') {

	$BrowseNodes[books]=array(
		"1000"=>"Top Selling",
		"265040"=>"Accessories",
		"1"=>"Arts",
		"44"=>"Audiobooks",
		"2"=>"Biographies",
		"3"=>"Business",
		"67240"=>"Calendars",
		"301731"=>"Español",
		"4"=>"Children's Books",
		"5"=>"Computers",
		"6"=>"Cooking",
		"86"=>"Entertainment",
		"301889"=>"Gay & Lesbian",
		"9"=>"History",
		"48"=>"Home & Garden",
		"49"=>"Horror",
		"17"=>"Literature",
		"18"=>"Mystery",
		"53"=>"Nonfiction",
		"290060"=>"Outdoors",
		"20"=>"Parenting",
		"173507"=>"Professional",
		"22"=>"Religion",
		"23"=>"Romance",
		"75"=>"Science",
		"25"=>"Science Fiction",
		"26"=>"Sports",
		"28"=>"Teens",
		"45"=>"Today's Deals",
		"27"=>"Travel",
		"551440"=>"e-Books",
		);
	$BrowseNodes[vhs]=array(
		"404274"=>"Top Selling",
		"141"=>"Action & Adventure",
		"301597"=>"African American Cinema",
		"712260"=>"Animation",
		"281300"=>"Anime & Manga",
		"126"=>"Art House & International",
		"290770"=>"Awards",
		"501232"=>"Boxed Sets",
		"226151"=>"Christian",
		"127"=>"Classics",
		"128"=>"Comedy",
		"162482"=>"Cult Movies",
		"300374"=>"Disney Home Video",
		"508530"=>"Documentary",
		"129"=>"Drama",
		"169660"=>"Fitness",
		"301665"=>"Gay & Lesbian",
		"131"=>"Horror",
		"589542"=>"Independently Distributed",
		"132"=>"Kids & Family",
		"586154"=>"Military & War",
		"133"=>"Music Video & Concerts",
		"508526"=>"Musicals & Performing Arts",
		"512026"=>"Mystery & Suspense",
		"144"=>"Science Fiction & Fantasy",
		"541660"=>"Spanish Language",
		"135"=>"Special Interests",
		"169798"=>"Sports",
		"285081"=>"Studio Specials",
		"136"=>"Television",
		"292355"=>"Today's Deals in Video",
		"139725"=>"Westerns",
		"692184"=>"Widescreen",
		"184904"=>"Yoga",
		);
	$BrowseNodes[music]=array(
		"301668"=>"Top Selling",
		"30"=>"Alternative Rock",
		"31"=>"Blues",
		"291920"=>"Box Sets",
		"265640"=>"Broadway",
		"173425"=>"Children's",
		"173429"=>"Christian",
		"67204"=>"Classic Rock",
		"85"=>"Classical",
		"16"=>"Country",
		"7"=>"Dance",
		"32"=>"Folk",
		"67207"=>"Hard Rock",
		"701208"=>"Imports",
		"226023"=>"Indie Music",
		"33"=>"International",
		"34"=>"Jazz",
		"289122"=>"Latin Music",
		"35"=>"Miscellaneous",
		"36"=>"New Age",
		"37"=>"Pop",
		"39"=>"R&B",
		"38"=>"Rap",
		"40"=>"Rock",
		"42"=>"Soundtracks",
		"287454"=>"Today's Deals",
		);
	$BrowseNodes[dvd]=array(
		"404276"=>"Top Selling",
		"163296"=>"Action/Adventure",
		"538708"=>"African American",
		"712256"=>"Animation",
		"517956"=>"Anime & Manga",
		"163313"=>"Art House",
		"408126"=>"Awards",
		"501230"=>"Boxed Sets",
		"163345"=>"Classics",
		"163357"=>"Comedy",
		"466674"=>"Cult Movies",
		"300381"=>"Disney",
		"508532"=>"Documentary",
		"163379"=>"Drama",
		"301667"=>"Gay & Lesbian",
		"163396"=>"Horror",
		"901596"=>"Independently",
		"163414"=>"Kids & Family",
		"586156"=>"Military",
		"163420"=>"Music Video",
		"508528"=>"Musicals",
		"512030"=>"Mystery",
		"163431"=>"Science Fiction",
		"163448"=>"Special Interest",
		"467970"=>"Sports",
		"468374"=>"Studio Specials",
		"163450"=>"Television",
		"409298"=>"Today's Deals",
		"163312"=>"Westerns",
		);
	$BrowseNodes["pc-hardware"]=array(
		"565118"=>"Top Selling",
		"602286"=>"AMD",
		"565124"=>"Apple",
		"565120"=>"HP",
		"603128"=>"IBM",
		"565122"=>"Intel",
		"565126"=>"Sony",
		"598398"=>"Toshiba",
		);
	$BrowseNodes[software]=array(
		"491286"=>"Top Selling",
		"229636"=>"Communication",
		"229614"=>"Graphics",
		"290562"=>"Linux",
		"229653"=>"Operating Sys",
		"531448"=>"Downloadable",
		"229672"=>"utilities",
		"229535"=>"Business",
		"229563"=>"Education",
		"229624"=>"Home/Hobby",
		"229643"=>"Mac",
		"229540"=>"Finance",
		"229663"=>"Handhelds",
		"497022"=>"Video",
		"229548"=>"Childrens",
		"229575"=>"Games",
		"497026"=>"Language/Travel",
		"229637"=>"Networking",
		"229667"=>"Programming",
		"497024"=>"Web Dev",
		);
	$BrowseNodes[toys]=array(
		"491290"=>"Top Selling",
		"171859"=>"Crafts",
		"171569"=>"Dolls",
		"171689"=>"Games",
		"171960"=>"Outdoor",
		"171662"=>"Action Figures",
		"569472"=>"Bikes",
		"720366"=>"Electronics",
		"171992"=>"Stuffed Animals",
		"171911"=>"Learning",
		"171814"=>"Building",
		"172790"=>"Furniture",
		"171744"=>"Puzzles",
		"171600"=>"Vehicles",
		);
	$BrowseNodes[videogames]=array(
		"471280"=>"Top Selling",
		"541022"=>"Game Cube",
		"301712"=>"Play Station 2",
		"229783"=>"Game Boy",
		"229647"=>"Mac",
		"229575"=>"PC",
		"541020"=>"Game Boy Advance",
		"537504"=>"XBox",
		);
	$BrowseNodes[kitchen]=array(
		"491864"=>"Top Selling",
		"289742"=>"Coffee/Tea",
		"289814"=>"Cookware",
		"289913"=>"Appliances",
		"289668"=>"Baking",
		"510080"=>"Housewares",
		"289891"=>"Tableware",
		"289728"=>"Bar",
		"289754"=>"Gadgets",
		"289851"=>"Knives",
		);
	$BrowseNodes[universal]=array(
		"468240"=>"Top Selling",
		"495266"=>"Electrical",
		"495346"=>"H/C",
		"495224"=>"Lighting",
		"553294"=>"Automotive",
		"551238"=>"hand tools",
		"551240"=>"Equipment",
		"551236"=>"Power Tools",
		"923468"=>"Models",
		"511228"=>"Hardware",
		"551242"=>"Lawn/Garden",
		);
	$BrowseNodes[garden]=array(
		"468250"=>"Top Selling",
		"553648"=>"Gifts",
		"915484"=>"L/G Tools",
		"553844"=>"Pest Control",
		"553632"=>"Birding",
		"553760"=>"Grills",
		"892986"=>"Camping",
		"553788"=>"Decor",
		"553778"=>"Heat/Light",
		"553824"=>"Furniture",
		);
	$BrowseNodes[magazines]=array(
		"599872"=>"Top Selling",
		"602324"=>"Computer/Internet",
		"602330"=>"Family",
		"602336"=>"Games",
		"602342"=>"History",
		"602348"=>"Lifestyle",
		"602354"=>"Music",
		"602360"=>"Pets",
		"1040158"=>"Espanol",
		"602370"=>"Travel",
		"602314"=>"Arts",
		"602320"=>"Business",
		"602326"=>"Electronics",
		"602332"=>"Fashion",
		"602344"=>"Home/Garden",
		"602350"=>"Literary",
		"1040160"=>"Newspapers",
		"602362"=>"Religion",
		"602366"=>"Sport",
		"602372"=>"Womens",
		"602316"=>"Automotive",
		"602322"=>"Childrens",
		"602328"=>"Entertain",
		"602334"=>"Food",
		"602340"=>"Health",
		"602346"=>"International",
		"602352"=>"Mens",
		"602358"=>"News/Politics",
		"602364"=>"Science/Nature",
		"602368"=>"Teen",
		);
	$BrowseNodes[baby]=array(
		"542456"=>"Backpacks & Carriers",
		"541560"=>"Car Seats",
		"541562"=>"Strollers",
		"542442"=>"Travel Systems",
		"542468"=>"Playards",
		"541574"=>"Bedding",
		"541576"=>"Furniture",
		"541568"=>"Breast-feeding",
		"541566"=>"Bottle Feeding",
		"541570"=>"Solid Feeding",
		"542302"=>"Highchairs",
		"548050"=>"Play Centers",
		"542470"=>"Swings & Bouncers",
		"731816"=>"Toys: Birth - 12 months",
		"731876"=>"Toys: 12 - 24 months",
		"731924"=>"Toys: 2 years",
		);
	$BrowseNodes[electronics]=array(
		"172282"=>"Top Selling",
		"301793"=>"Outlet",
		"281407"=>"Accessories & Supplies",
		"226184"=>"Car Accessories",
		"509280"=>"Clocks & Clock Radios",
		"172455"=>"Computer Add-Ons",
		"172514"=>"DVD Players",
		"172517"=>"Gadgets",
		"172526"=>"GPS & Navigation",
		"172594"=>"Handhelds & PDAs",
		"172531"=>"Home Audio",
		"172574"=>"Home Office",
		"172592"=>"Home Video",
		"172606"=>"Phones",
		"172623"=>"Portable Audio & Video",
		"172635"=>"Printers",
		"172659"=>"TVs",
		"172669"=>"VCRs & DVRs",
		);

	$BrowseNodes[photo]=array(
		"513080"=>"Top Selling",
		"172435"=>"Accessories",
		"297842"=>"Binoculars",
		"172421"=>"Camcorders",
		"281052"=>"Digital Cameras",
		"499106"=>"Film Cameras",
		"499176"=>"Frames & Albums",
		"499328"=>"Printers & Scanners",
		"525462"=>"Projectors",
		"660408"=>"Telescopes & Microscopes",
		);
  $BN=$BrowseNodes[$BrowseNode];
  return $BN;
 }

function amazon_create_productinfo ($Data) {
if(!$Align) { $Align=$GLOBALS["amazon_productinfo_table_align"]; }
if(!$Width) { $Width=$GLOBALS["amazon_productinfo_table_width"]; }
$ThisItem=$Data[Details][0];
if($ThisItem[ProductName][0]) {
if($ThisItem[Authors][0][Author]) { $ByArray=$ThisItem[Authors][0][Author]; }
if($ThisItem[Directors][0][Director]) { $ByArray=$ThisItem[Directors][0][Director]; }
if($ThisItem[Artists][0][Artist]) { $ByArray=$ThisItem[Artists][0][Artist]; }
$Font="<font size=2 face=verdana color=black>";

$ProductInfo="<!--
Created using Filzhut.net amazon_functions for PHP
Download: http://associatesshop.filzhut.net/download/
//-->";
$ProductInfo.="<table border=0 align=\"$Align\" width=\"$Width\">\n";
$ProductInfo.="<tr><td align=left valign=top width=75%>$Font";
$ProductInfo.="<small>".$ThisItem[Catalog][0]."</small><br>";
$ProductInfo.="<big><b>".$ThisItem[ProductName][0]."</b></big><br>\n";
for($x=0;$x<=sizeof($ByArray)-1;$x++) {
  if($x==0) { $ProductInfo.="by "; }
  $ProductInfo.=$ByArray[$x];
  if($x+1<=sizeof($ByArray)-1) { $ProductInfo.=", "; } else { $ProductInfo.="<br>"; }
 }

if(is_array($ThisItem[Starring][0][Actor])) {
  for($x=0;$x<=sizeof($ThisItem[Starring][0][Actor])-1;$x++) {
    if($x==0) { $ProductInfo.="starring "; }
    $ProductInfo.=$ThisItem[Starring][0][Actor][$x];
    if($x+1<=sizeof($ThisItem[Starring][0][Actor])-1) { $ProductInfo.=", "; } else { $ProductInfo.="<br>"; }
   }
 }

$ProductInfo.="<p></font></td>";
$ProductInfo.="<td align=right valign=top width=100>".amazon_create_cartform($ThisItem[Asin][0])."</td>";
$ProductInfo.="</tr>";

$ProductInfo.="<tr><td colspan=2>$Font\n";
$ProductInfo.="<table border=0>\n";
$ProductInfo.="<tr>\n";
$ThisItem[ImageUrlLarge][0]=str_replace($ThisItem[Asin][0],strtoupper($ThisItem[Asin][0]),$ThisItem[ImageUrlLarge][0]);
$ThisItem[ImageUrlMedium][0]=str_replace($ThisItem[Asin][0],strtoupper($ThisItem[Asin][0]),$ThisItem[ImageUrlMedium][0]);
$ProductInfo.="<td align=center rowspan=10 valign=top>$Font<a href=\"#\" OnClick=\"window.open('".$ThisItem[ImageUrlLarge][0]."','ImageWindow','width=525,height=525,scrolling=yes,resizable=yes');\"><img src='".$ThisItem[ImageUrlMedium][0]."' border=0></font></td>\n";
$ProductInfo.="<td width=15 rowspan=10></td>\n";
#$ProductInfo.="<td colspan=3 align=right>$Font".amazon_create_cartform($ThisItem[Asin][0])."</font></td>\n";
$ProductInfo.="</tr>\n";
$ProductInfo.="<tr>\n";
$ProductInfo.="<td align=left><nobr>$Font";
if($ThisItem[ListPrice][0]!=$ThisItem[OurPrice][0]) { $ProductInfo.="<strike>"; }
$ProductInfo.="<b>List Price:</b> ".$ThisItem[ListPrice][0]."</strike></nobr></font></td>\n";
$ProductInfo.="<td rowspan=3 width=15></td>";
$ProductInfo.="<td align=left rowspan=3 valign=bottom>$Font";
if($ThisItem[Manufacturer][0]) { $ProductInfo.="<b>Publisher:</b> ".$ThisItem[Manufacturer][0]."<br>"; }
if($ThisItem[SalesRank][0]) { $ProductInfo.="<b>Salesrank:</b> ".$ThisItem[SalesRank][0]."<br>"; }
if($ThisItem[ReleaseDate][0]) { $ProductInfo.="<b>Released:</b> ".$ThisItem[ReleaseDate][0]."<br>"; }
if($ThisItem[TheatricalReleaseDate][0]) { $ProductInfo.="<b>Theatrical-Release:</b> ".$ThisItem[TheatricalReleaseDate][0]."<br>"; }
$ProductInfo.="</font></td></tr>\n";
$ProductInfo.="<tr><td align=left><nobr>$Font"."<b>Our Price:</b> ".$ThisItem[OurPrice][0]."</font></nobr></td></tr>\n";
$ProductInfo.="<tr><td align=left><nobr>$Font";
if($ThisItem[UsedPrice][0]) { $ProductInfo.="<b>Used Price:</b> ".$ThisItem[UsedPrice][0]; }
$ProductInfo.="&nbsp;</font></nobr></td></tr>\n";
$ProductInfo.="<tr><td height=10 colspan=3></td></tr>\n";
$ProductInfo.="<tr><td colspan=3>$Font";
if($ThisItem[EsrbAgeRating][0]) { $ProductInfo.="<b>EsrbAgeRating:</b> ".$ThisItem[EsrbAgeRating][0]."<br>"; }
if($ThisItem[MpaaRating][0]) { $ProductInfo.="<b>MpaaRating:</b> ".$ThisItem[MpaaRating][0]."<br>"; }
if($ThisItem[Media][0]) {
  $ProductInfo.="<b>Media:</b> ".$ThisItem[Media][0];
  if($NumberofItems=$ThisItem[NumberofItems][0]) { $ProductInfo.=" ($NumberofItems Items)"; }
  $ProductInfo.="<br>";
 }
if($ThisItem[Platform][0]) { $ProductInfo.="<b>Platform:</b> ".$ThisItem[Platform][0]."<br>"; }
$ProductInfo.="</td></tr>\n";
$ProductInfo.="<tr><td height=10 colspan=3></td></tr>\n";
$ProductInfo.="<tr><td colspan=3>$Font";
$ProductInfo.="<b>Availibility:</b> ".$ThisItem[Availability][0];
if($Rating=$ThisItem[Reviews][0][AverageRating][0][0])
 { $ProductInfo.="<br><b>Costumer Rating:</b> <img src='http://g-images.amazon.com/images/G/01/detail/stars-".$Rating."-0.gif' widith=64 height=12 align=absolute>"; }
$ProductInfo.="</font></td></tr>\n";
$ProductInfo.="</table><p align=justify><br>\n";

if(is_array($ThisItem[Features][0][Feature])) {
  $ProductInfo.="<big><b>Features:</b></big><br>";
  for ($x=0;$x<=sizeof($ThisItem[Features][0][Feature])-1;$x++) {
    $ProductInfo.="<li>".$ThisItem[Features][0][Feature][$x];
   }
  $ProductInfo.="<p align=justify>";
 }

if(is_array($ThisItem[Tracks][0][Track])) {
  $ProductInfo.="<big><b>Tracklisting:</b></big><br>";
  for ($x=0;$x<=sizeof($ThisItem[Tracks][0][Track])-1;$x++) {
    $ProductInfo.=($x+1).". ".$ThisItem[Tracks][0][Track][$x]." - ".$ThisItem[Tracks][0][ByArtist][$x]."<br>";
   }
  $ProductInfo.="<p align=justify>";
 }

if(is_array($ThisItem[Reviews][0][CustomerReview])) {
  $ProductInfo.="<big><b>Customer Reviews:</b></big><br>";
  for ($x=0;$x<=sizeof($ThisItem[Reviews][0][CustomerReview])-1;$x++) {
    $ProductInfo.="<b>".$ThisItem[Reviews][0][CustomerReview][$x][Summary][0]."</b>";
    if($Rating=$ThisItem[Reviews][0][CustomerReview][$x][Rating][0][0])
     { $ProductInfo.=" <img src='http://g-images.amazon.com/images/G/01/detail/stars-".$Rating."-0.gif' widith=64 height=12 align=absolute>"; }
    $ProductInfo.="<br>".$ThisItem[Reviews][0][CustomerReview][$x][Comment][0]."<p align=justify>";
   }
  $ProductInfo.="<p align=justify>";
 }

$ProductInfo.="</font></td>\n";
$ProductInfo.="</tr>\n";

$AssociatesID=ASSOCIATESID;
if(!$AssociatesID)
 { $AssociatesID="leannrimesfansit"; }

$ProductInfo.="<tr><td height=80 colspan=2 align=center><center><a href='http://www.amazon.com/exec/obidos/redirect-home/$AssociatesID' target='_blank'><img align=center src='http://images.amazon.com/images/G/01/associates/home-logo-130x60w.gif' width=130 height=60 border=0></a></center></td></tr>";
$ProductInfo.="</table>\n";
 }
 else {
  $ProductInfo.="<i>no such product found</i>";
 }
  return $ProductInfo;
 }

# Mainfunction to parse the XML defined by URL
function amazon_xml_parsexml ($URL) {
  $String=amazon_xml_loadxml($URL,$String);
  $Encoding=amazon_xml_encoding($String);
  $String=amazon_xml_deleteelements($String,"?");
  $String=amazon_xml_deleteelements($String,"!");
  $Data=amazon_xml_readxml($String,$Data,$Encoding);
  return($Data);
 }

# Get encoding of xml
function amazon_xml_encoding($String) {
  if(substr_count($String,"<?xml")) {
    $Start=strpos($String,"<?xml")+5;
    $End=strpos($String,">",$Start);
    $Content=substr($String,$Start,$End-$Start);
    $EncodingStart=strpos($Content,"encoding=\"")+10;
    $EncodingEnd=strpos($Content,"\"",$EncodingStart);
    $Encoding=substr($Content,$EncodingStart,$EncodingEnd-$EncodingStart);
   }
  else { $Encoding=""; }
  return $Encoding;
 }


# Delete elements
function amazon_xml_deleteelements($String,$Char) {
  while(substr_count($String,"<$Char")) {
    $Start=strpos($String,"<$Char");
    $End=strpos($String,">",$Start+1)+1;
    $String=substr($String,0,$Start).substr($String,$End);
   }
  return $String;
 }


# Read XML and transform into array
function amazon_xml_readxml($String,$Data,$Encoding='') {
  while($Node=amazon_xml_nextnode($String)) {
    $TmpData="";
    $Start=strpos($String,">",strpos($String,"<$Node"))+1;
    $End=strpos($String,"</$Node>",$Start);
    $ThisContent=trim(substr($String,$Start,$End-$Start));
    $String=trim(substr($String,$End+strlen($Node)+3));
    if(substr_count($ThisContent,"<")) {
      $TmpData=amazon_xml_readxml($ThisContent,$TmpData,$Encoding);
      $Data[$Node][]=$TmpData;
     }
    else {
      if($Encoding=="UTF-8") { $ThisContent=utf8_decode($ThisContent); }
      $ThisContent=str_replace("&gt;",">",$ThisContent);
      $ThisContent=str_replace("&lt;","<",$ThisContent);
      $ThisContent=str_replace("&quote;","\"",$ThisContent);
      $ThisContent=str_replace("&#39;","'",$ThisContent);
      $ThisContent=str_replace("&amp;","&",$ThisContent);
      $Data[$Node][]=$ThisContent;
     }
   }
  return $Data;
 }


# Get next node
function amazon_xml_nextnode($String) {
  if(substr_count($String,"<") != substr_count($String,"/>")) {
    $Start=strpos($String,"<")+1;
    while(substr($String,$Start,1)=="/") {
      if(substr_count($String,"<")) { return ""; }
      $Start=strpos($String,"<",$Start)+1;
     }
    $End=strpos($String,">",$Start);
    $Node=substr($String,$Start,$End-$Start);
    if($Node[strlen($Node)-1]=="/") {
      $String=substr($String,$End+1);
      $Node=amazon_xml_nextnode($String);
     }
    else {
      if(substr_count($Node," "))
       { $Node=substr($Node,0,strpos($String," ",$Start)-$Start); }
    }
   }
  return $Node;
 }

# Function to load the XML from File/URL
function amazon_xml_loadxml($URL,$String) {
  # Define location of local file
  $LocalFile=AMAZONCACHEDIR.md5($URL).".dat";
  # Check if file is availible and maximal file age is not larger than defined
  if((file_exists($LocalFile)) && (($TmpTime=time())-filemtime($LocalFile)<AMAZONCACHEMAXTIME) && AMAZONCACHEDIR)
   {
    # Load local file as string
    $String=@file($LocalFile);
    if(is_array($String))
     { $String=implode("",$String); }
   }
  else
   {
    $timeout=10;
    $URL=str_replace("http://","",$URL);
    $Host=substr($URL,0,strpos($URL,"/"));
    $Path=substr($URL,strpos($URL,"/"));
    $fp = fsockopen($Host, "80");
    if ($fp) {
      fputs($fp, "GET " . $Path . " HTTP/1.0\r\nHost: " . $Host . "\r\n\r\n");
      while(!feof($fp))
        $String.=fgets($fp, 128);
    }

    if($String && file_exists(AMAZONCACHEDIR) && AMAZONCACHEDIR)
     { fwrite(fopen("$LocalFile", "w"),$String,strlen($String)); }
   }
  return $String;
 }

php?>
