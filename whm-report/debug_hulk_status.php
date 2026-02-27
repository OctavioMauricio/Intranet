<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/includes/WhmApi.php';
$whm = new WhmApi();

echo "--- CPHULK DETAILED STATUS ---\n\n";

// 1. Check if cPHulk is running
echo "1. cPHulk Configuration:\n";
$config = $whm->call('get_cphulk_config');
print_r($config);

// 2. Check Blacklist
echo "\n2. Blacklist (Permanent Blocks):\n";
$black = $whm->call('read_cphulk_records', ['list_name' => 'black']);
if (isset($black['data']['ips_in_list'])) {
    echo "Count: " . count($black['data']['ips_in_list']) . "\n";
    print_r(array_slice($black['data']['ips_in_list'], 0, 10)); // Show first 10
} else {
    print_r($black);
}

// 3. Check Brutes (Temporary Blocks)
echo "\n3. Brutes (Temporary Blocks):\n";
$brutes = $whm->call('get_cphulk_brutes');
if (isset($brutes['data']['brutes'])) {
    echo "Count: " . count($brutes['data']['brutes']) . "\n";
    print_r(array_slice($brutes['data']['brutes'], 0, 10)); // Show first 10
} else {
    print_r($brutes);
}

// 4. Check Whitelist (just for context)
echo "\n4. Whitelist:\n";
$white = $whm->call('read_cphulk_records', ['list_name' => 'white']);
if (isset($white['data']['ips_in_list'])) {
    echo "Count: " . count($white['data']['ips_in_list']) . "\n";
} else {
    print_r($white);
}

echo "\n--- FIN ---";
?>
