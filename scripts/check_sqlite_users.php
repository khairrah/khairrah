<?php
$db = new PDO('sqlite:C:/Users/Vivian Elsya Velyana/ukk-perpustakaan/database/database.sqlite');
$stmt = $db->query('SELECT id,email,role FROM users');
if (!$stmt) {
    echo "No rows or query failed\n";
    exit(0);
}
foreach ($stmt as $row) {
    echo $row['id'].' '.$row['email'].' '.$row['role']."\n";
}
