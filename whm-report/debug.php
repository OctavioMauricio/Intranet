<?php
header('Content-Type: text/plain; charset=utf-8');

echo "=== DEBUG DIRECTO ===\n\n";

// 1. Verificar que WhmApi.php tiene la version correcta
$whmApiContent = file_get_contents(__DIR__ . '/includes/WhmApi.php');
echo "WhmApi.php contiene 'cacertPaths': " . (strpos($whmApiContent, 'cacertPaths') !== false ? 'SI (version nueva)' : 'NO (version vieja!)') . "\n";
echo "WhmApi.php contiene '/home/icontel': " . (strpos($whmApiContent, '/home/icontel') !== false ? 'SI' : 'NO') . "\n";
echo "WhmApi.php tamaño: " . strlen($whmApiContent) . " bytes\n\n";

// 2. Verificar cacert.pem
$paths = [
    '/home/icontel/public_html/intranet/whm-report/cacert.pem',
    __DIR__ . '/cacert.pem',
    realpath(__DIR__ . '/cacert.pem'),
];
foreach ($paths as $p) {
    echo "cacert [$p]: " . (($p && file_exists($p)) ? filesize($p) . ' bytes OK' : 'NO EXISTE') . "\n";
}

// 3. Test directo con la ruta absoluta que SABEMOS que funciona
echo "\n--- TEST DIRECTO (misma logica que test_whm v3) ---\n";
$cacert = '/home/icontel/public_html/intranet/whm-report/cacert.pem';
$url = 'https://cleveland.icontel.cl:2087/json-api/version?api.version=1';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_CAINFO, $cacert);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: WHM root:4RPASEKM68R74H3G7CX66S7ZEAB6X8BM'
]);
$response = curl_exec($ch);
$error = curl_error($ch);
$ssl = curl_getinfo($ch, CURLINFO_SSL_VERIFYRESULT);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: $http | SSL: $ssl\n";
if ($error) {
    echo "ERROR: $error\n";
} else {
    $data = json_decode($response, true);
    echo "RESULTADO: " . ($data['data']['version'] ?? substr($response, 0, 100)) . "\n";
}

// 4. Ahora test usando la clase WhmApi
echo "\n--- TEST VIA CLASE WhmApi ---\n";
require_once __DIR__ . '/includes/WhmApi.php';
$whm = new WhmApi();
$test = $whm->testConnection();
echo "Resultado: " . json_encode($test) . "\n";

echo "\n=== FIN ===\n";
