<?php
$db = new PDO('sqlite:C:/Users/Vivian Elsya Velyana/ukk-perpustakaan/database/database.sqlite');
foreach($db->query("PRAGMA table_info('users')") as $col) {
    echo $col['name']."\n";
}

echo "--- rows ---\n";
foreach($db->query('SELECT * FROM users') as $row) {
    echo json_encode($row)."\n";
}
