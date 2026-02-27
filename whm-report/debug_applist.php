<?php
require_once __DIR__ . '/includes/WhmApi.php';
$whm = new WhmApi();

echo "--- WHM APPLIST ---\n";
$res = $whm->call('applist');
header('Content-Type: application/json');
echo json_encode($res, JSON_PRETTY_PRINT);
?>
