<?php
require_once __DIR__ . '/includes/WhmApi.php';
$whm = new WhmApi();
echo "--- SECURITY APPS ---\n";
$res = $whm->call('applist');
$relevant = [];
if (isset($res['data']['app'])) {
    foreach ($res['data']['app'] as $app) {
        if (preg_match('/(firewall|csf|hulk|deny|block|security|iptables|guard|shield)/i', $app)) {
            $relevant[] = $app;
        }
    }
}
echo "Relevant apps found: " . count($relevant) . "\n";
print_r($relevant);
?>
