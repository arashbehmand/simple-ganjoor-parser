<?

include('config.php');
include('formation.php');

$db = new SQLite3($db_address,SQLITE3_OPEN_READONLY);


function find_cat_hierachy($_cat_id)
{
	global $db;
	$results = $db->query('SELECT * FROM cat WHERE id=' . $_cat_id . "");
	$catData = $results->fetchArray();
	if($catData['parent_id']!=0)
	{
		return array_merge(find_cat_hierachy($catData['parent_id']),(array)$catData['text']);
	}else{

		return array($catData['text']);
	}
}

if (!@$_GET['q'])
{

	gen_header("جستجو");
	echo "<form method=\"GET\" class=\"sform\" action=\"search.php\">\n";
	echo "<p>\n";
	echo "<label for=\"q\">جستجو برای عبارت</label>\n";
	echo "<input type=\"text\" id=\"q\" name=\"q\" value=\"\" />\n";
	echo "</p>\n";
	echo "<p>\n";
	echo "<label for=\"pid\">در آثار</label>\n";
	echo "<select id=\"pid\" name=\"pid\">\n";
	echo "<option value=\"0\">همه‌ی شاعران</option>\n";
	$results = $db->query('SELECT * FROM poet');
	while ($poetData = $results->fetchArray())
	{
		echo "<option value=\"".$poetData['id']."\">" . $poetData['name'] . "</option>\n";
	}
	echo "</select>";
	echo "<div style=\"margin-left: 150px;\">\n";
	echo "<input type=\"submit\" value=\"ارسال\" /> <input type=\"reset\" value=\"از نو\" />\n";
	echo "</div>\n";
	echo "</form>\n";
}else
{
	gen_header("جستجو برای ".$_GET['q']);
	$qstr = "SELECT verse.text,verse.vorder,verse.position,poem.id,poem.cat_id,cat.poet_id FROM verse INNER JOIN poem ON verse.poem_id=poem.id INNER JOIN cat ON poem.cat_id=cat.id WHERE verse.text LIKE '%".$_GET['q']."%'";
	$qstr .= (@$_GET['pid']==0?"":" AND cat.poet_id=".$_GET['pid']);
	$results = $db->query($qstr);
	echo "<table>\n";
	while ($data = $results->fetchArray())
	{			
		echo "<tr>\n";
		echo "<td class=\"verseright\"><a href=\"cat.php?id=".$data['poet_id']."\">".implode("»",find_cat_hierachy($data['cat_id']))."</a></td>\n";
		echo "<td class=\"verseright\"><a href=\"poem.php?poem_id=".$data['id']."\">".$data['text']."</a></td>\n";
	}
	echo "</table>\n";
}
gen_footer();

?>

