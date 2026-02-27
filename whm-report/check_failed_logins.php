<?php
require_once __DIR__ . '/includes/WhmApi.php';
$whm = new WhmApi();
echo "--- CPHULK SUSPICIOUS ACTIVITY ---\n";

echo "1. Failed Logins:\n";
$failed = $whm->call('get_cphulk_failed_logins');
if (isset($failed['data']['failed_logins'])) {
    echo "Count: " . count($failed['data']['failed_logins']) . "\n";
    print_r(array_slice($failed['data']['failed_logins'], 0, 10));
} else {
    print_r($failed);
}

echo "\n2. Excessive Brutes:\n";
$excessive = $whm->call('get_cphulk_excessive_brutes');
if (isset($excessive['data']['brutes'])) {
    echo "Count: " . count($excessive['data']['brutes']) . "\n";
    print_r($excessive['data']['brutes']);
} else {
    print_r($excessive);
}
?>
