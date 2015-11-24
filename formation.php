<?

function color($_r,$_g,$_b)
{
	if($_r > 0xFF) $_r = 0xFF;
	if($_g > 0xFF) $_g = 0xFF;
	if($_b > 0xFF) $_b = 0xFF;
	if($_r < 0x00) $_r = 0x00;
	if($_g < 0x00) $_g = 0x00;	
	if($_b < 0x00) $_b = 0x00;		
	return sprintf("#%02X%02X%02X",intval($_r),intval($_g),intval($_b));
}

$base_r = 0x50;
$base_g = 0x50;
$base_b = 0x50;

function gen_header($_title="",$_other_headings="")
{
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf8\">\n";
	echo "<title>گنجورخوان" . ($_title==""?"":" » ".$_title) . "</title>\n";
	echo "<link href=\"formation.php?action=css\" type=\"text/css\" rel=\"stylesheet\">\n";
	echo $_other_headings;
	echo "</head>\n";
	echo "<body>\n";
	echo "<div class=\"mainheader\">گنجورخوان</div>\n";
}

function gen_footer($_additional_footer_links = array())
{
	$footer_text = "<div class=\"mainfooter\">";
	$footer_links = array("صفحه نخست" => "index.php","جستجو" => "search.php");
	$footer_links = array_merge($footer_links, $_additional_footer_links);
	$tmp_footers = array();
	foreach($footer_links as $title => $link)
	{
		$tmp_footers[] = "<a href=\"".$link."\">".$title."</a>";
	}
	$footer_text .= implode("&nbsp;|&nbsp;", $tmp_footers);
	$footer_text .= "</div>\n";
	echo $footer_text;
	echo "<div class=\"mainfooter\">حقوق برای آرش بهمند محفوظ است.</div>\n";
	echo "</body>\n";
	echo "</html>\n";
}

function gen_css()
{
	global $base_r,$base_g,$base_b;
	header("Content-Type: text/css");
	echo "body{\n";
	echo "\tmargin:0; padding:0;\n";
	echo "\tdirection: rtl;\n";
	echo "\tfont-size: 12px;\n";
	echo "\tcolor: ".color($base_r,$base_g,$base_b).";\n";
	echo "\tfont-family: tahoma;\n";
	echo "\tbackground-color: ".color(0xFF-$base_r,0xFF-$base_g,0xFF-$base_b).";\n";
	echo "}\n";
	echo "a{\n";
	echo "\ttext-decoration: none;\n";
	echo "\tcolor: ".color($base_r*0.68,$base_g*0.68,$base_b*0.68).";\n";
	echo "}\n";
	echo "a:hover{\n";
	echo "\tcolor: ".color($base_r*0.68,$base_g*0.68,$base_b*1.68).";\n";
	echo "}\n";
	echo "td.verseRight{\n";
	echo "\ttext-align:right;\n";
	echo "\tfont-size: 12px;\n";
	echo "\twidth: 45%\n";
	echo "}\n";
	echo "td.verseLeft{\n";
	echo "\ttext-align:left;\n";
	echo "\tfont-size: 12px;\n";
	echo "\twidth: 45%\n";
	echo "}\n";
	echo "td.spacer{\n";
	echo "\twidth: 5%\n";
	echo "}\n";
	echo "td.verseCenter{\n";
	echo "\ttext-align:center;\n";
	echo "\tfont-size: 12px;\n";
	echo "\twidth: 100%\n";
	echo "}\n";
	echo "div.header{\n";
	echo "\tfont-size: 13px;\n";
	echo "}\n";
	echo ".treeview ul{\n";
	echo "\tmargin: 0;\n";
	echo "\tpadding: 0;\n";
	echo "}\n";
	echo ".treeview li{\n";
	echo "\tbackground: url(list.gif) no-repeat right center;\n";
	echo "\tlist-style-type: none;\n";
	echo "\tpadding-right: 22px;\n";
	echo "\tmargin-bottom: 3px;\n";
	echo "}\n";
	echo ".treeview li.submenu{\n";
	echo "\tbackground: url(closed.gif) no-repeat right 1px;\n";
	echo "\tcursor: hand !important;\n";
	echo "\tcursor: pointer !important;\n";
	echo "}\n";
	echo ".treeview li.submenu ul{\n";
	echo "\tdisplay: none;\n";
	echo "}\n";
	echo ".treeview .submenu ul li{\n";
	echo "\tcursor: default;\n";
	echo "}\n";
	echo ".mainheader{\n";
	echo "\tmargin: 5;\n";
	echo "\ttext-align: center;\n";
	echo "\tfont-size: 15px;\n";
	echo "\tfont-weight: bold;\n";
	echo "}\n";
	echo ".mainfooter{\n";
	echo "\tmargin: 3;\n";
	echo "\ttext-align: center;\n";
	echo "\tfont-size: 10px;\n";
	echo "}\n";
	echo ".sform p{\n";
	echo "\twidth: 300px;\n";
	echo "\tclear: right;\n";
	echo "\tmargin: 5;\n";
	echo "\tpadding: 5px 0 8px 0;\n";
	echo "\tpadding-right: 155px; /*width of right column containing the label elements*/\n";
	echo "\tborder-top: 2px dashed gray;\n";
	echo "\theight: 1%;\n";
	echo "}\n";
	echo ".sform label{\n";
	echo "\tfloat: right;\n";
	echo "\tmargin-right: -155px;\n";
	echo "\twidth: 150px;\n";
	echo "}\n";
	echo ".sform input[type=\"text\"]{\n";
	echo "\tmargin: 0;\n";
	echo "\twidth: 180px;\n";
	echo "}\n";

}

if ((isset($_GET['action'])) && ($_GET['action']=="css"))
{
	gen_css();
}
?>
