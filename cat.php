<?

include('config.php');
include('formation.php');

$db = new SQLite3($db_address,SQLITE3_OPEN_READONLY);
$poet_id = $_GET['id'];

function extract_cat_info($_id, $_text)
{
	global $db,$poet_id;
	$catresults = $db->query('SELECT * FROM cat WHERE poet_id='.$poet_id.' AND parent_id='.$_id);
	$result_string  = "";
	$result_string .= "<li> ";
	$childs_result = "";
	while ($catInfo = $catresults->fetchArray())
	{
		$childs_result .= extract_cat_info($catInfo['id'],$catInfo['text']);
	}
	if ($childs_result == "")
	{
		$result_string .= $_text . "<br>\n";
		$result_string .= extract_poem_detail($_id);
	}else{
		$result_string .= $_text . "<br>\n";	
		$result_string .= "<ul>\n" . $childs_result . "</ul>\n";
	}
	$result_string .= "</li>\n";
	return $result_string;
}

function extract_poem_detail($cat_id)
{
	global $db;
	$poemresults = $db->query('SELECT * FROM poem WHERE cat_id=' . $cat_id);
	$result_string = "";
	while ($poemInfo = $poemresults->fetchArray())
	{
		$result_string .= "<li> <a href=\"poem.php?poem_id=". $poemInfo['id'] . "\">" .$poemInfo['title']."</a> </li>\n";
	}
	if ($result_string == "")
	{
		return "no entry!";
	}else{
		return "<ul>\n" . $result_string . "</ul>\n";
	}
}

$catresults = $db->query('SELECT * FROM cat WHERE poet_id='.$poet_id.' AND parent_id=0');

gen_header("جستجوی آثار","<script type=\"text/javascript\" src=\"simpletreemenu.js\"></script>\n");
echo "<ul id=\"cattree\" class=\"treeview\" rel=\"open\">\n";
while ($catInfo = $catresults->fetchArray()) {
	echo extract_cat_info($catInfo['id'],$catInfo['text']);	
}
echo "</ul>\n";
echo <<<SCRIPT
<script type="text/javascript">
streemenu.createTree("cattree", true, 5)
</script>
SCRIPT;
gen_footer();
?>
