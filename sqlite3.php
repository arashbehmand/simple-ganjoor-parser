<?
error_reporting(E_ALL);

$db = new SQLite3('/ganjoor.s3db',SQLITE3_OPEN_READONLY);
$results = $db->query('SELECT * FROM poet');
while ($row = $results->fetchArray()) {
    var_dump($row);
}
?>

