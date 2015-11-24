<?

include('config.php');
include('formation.php');

gen_header("صفحه نخست");
$db = new SQLite3($db_address,SQLITE3_OPEN_READONLY);
$results = $db->query('SELECT * FROM poet');
echo "<ul>\n";
while ($poetData = $results->fetchArray()) {
	echo "<li> <a href=\"cat.php?id=" . $poetData['id'] . "&catid=" . $poetData['cat_id'] . "\">" . $poetData['name'] . "</a> </li>\n";
}
echo "</ul>\n";
gen_footer();

?>

