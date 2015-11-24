<?
include('config.php');
include('formation.php');

function find_cat_hierachy($_cat_id)
{
	global $db;
	$results = $db->query('SELECT * FROM cat WHERE id=' . $_cat_id . "");
	$catData = $results->fetchArray();
	if($catData['parent_id']!=0)
	{
		return array_merge(find_cat_hierachy($catData['parent_id']),(array)$catData['text']);
	}else{
		global $poet_info;
		$poet_info = find_poet_info($catData['poet_id']);
		return array($catData['text']);
	}
}

function find_poet_info($_poet_id)
{
	global $db;
	$results = $db->query('SELECT * FROM poet WHERE id=' . $_poet_id . "");
	return $results->fetchArray();
}

$db = new SQLite3($db_address,SQLITE3_OPEN_READONLY);
$poem_id = $_GET['poem_id'];
$poet_info;

$results = $db->query('SELECT * FROM poem WHERE id=' . $poem_id . "");
$poemData = $results->fetchArray();
$cat_hierachy = find_cat_hierachy($poemData['cat_id']);
$results = $db->query('SELECT * FROM verse WHERE poem_id=' . $poem_id . " ORDER BY vorder ASC");

$poem = array();
$i = -1;
$j = 0;
while ($verseData = $results->fetchArray()) {
	if (($verseData['position'] == 0) || ($verseData['position'] == 2) || ($verseData['position'] == 3))
	{
		$i++;
		$j = 0;
	}
	$poem[$i][$j] = $verseData["text"];
	$j++;
}

gen_header("نمایش شعر » ".$poemData['title']."");

echo "<div class=\"header\"><a href=\"cat.php?id=" . $poet_info['id'] . "&catid=" . $poet_info['cat_id']."\">".implode(" » ",$cat_hierachy)."</a> » <b>".$poemData['title']."</b></div>\n";

echo "<table align=\"center\" dir=\"rtl\">\n";

//var_dump($poem);

for ( $i = 0; $i < count($poem); $i++){
	echo "<tr>\n";
	if (count($poem[$i]) == 1)
	{
		echo "<td class=\"verseCenter\" colspan=\"3\">" . $poem[$i][0] . "</td>\n";
	}else if (count($poem[$i]) == 2)
	{
		echo "<td class=\"verseLeft\">" . $poem[$i][0] . "</td>\n";
		echo "<td class=\"spacer\">&nbsp;</td>\n";
		echo "<td class=\"verseRight\">" . $poem[$i][1] . "</td>\n";
	}else
	{
		echo "<td colspan=\"3\">ERROR</td>";
		//for ( $j = 0; $j < count($poem[$i]); $j++){
		//	echo "<td>" . $poem[$i][$j] . "</td>\n";
		//}
	}
	echo "</tr>\n";
}

echo "</table>\n";
gen_footer(array( ("نمایش آثار ".$poet_info['name'])=>("cat.php?id=" . $poet_info['id'] . "&catid=" . $poet_info['cat_id']) ));
?>

