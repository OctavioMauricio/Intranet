<?php
header('Content-Type: text/plain');

echo "--- CSF DEBUG ---\n";

echo "Whoami: " . shell_exec('whoami') . "\n";

echo "Testing 'csf -l':\n";
$out1 = shell_exec('csf -l 2>&1');
var_dump($out1);

echo "\nTesting '/usr/sbin/csf -l':\n";
$out2 = shell_exec('/usr/sbin/csf -l 2>&1');
var_dump($out2);

echo "\nTesting 'iptables -L -n':\n";
$out3 = shell_exec('iptables -L -n 2>&1');
var_dump($out3);
?>
