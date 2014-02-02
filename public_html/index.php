<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

$config = json_decode(file_get_contents('../config.json'), true);

function file_get_contents2($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_USERAGENT, 'UiO Realfagsbiblioteket App (+http://linode.biblionaut.net/app/)');
    curl_setopt($ch, CURLOPT_HEADER, 0); // no headers in the output
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return instead of output
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

if (!isset($_GET['cql'])) {
    // header('Content-type: text/plain; charset=utf-8');
    // echo 'Usage: ?cql=...&appver=...';
    header('Location: stats.php');
    exit();
}

$cql = $_GET['cql'];
$page = isset($_GET['page']) ? $_GET['page'] : '0';
$app_version = isset($_GET['appver']) ? $_GET['appver'] : 'unknown';

header('Content-type: application/json; charset=utf-8');
$t0 = microtime(true);
$results = trim(file_get_contents2('https://ask.bibsys.no/ask2/json/result.jsp?cql=' . rawurlencode($cql) . '&page=' . $page));
$t1 = microtime(true);
$request_time = ($t1 - $t0) * 1000; // secs to millisecs
echo $results;

$pdo = new PDO('mysql:host=' . $config['mysql']['host'] . ';dbname=' . $config['mysql']['db'], $config['mysql']['user'], $config['mysql']['pwd']);
//$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // by default, the default error mode for PDO is PDO::ERRMODE_SILENT
if ($pdo) {

    $stmt = $pdo->prepare('INSERT INTO visits (timestamp, user_agent, accept_lang, app_version, request_time, cql) VALUES(UTC_TIMESTAMP(), :user_agent, :accept_lang, :app_version, :request_time, :cql)');
    $stmt->execute(array(
        ':user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ':accept_lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
        ':app_version' => $app_version,
        ':request_time' => $request_time,
        ':cql' => $cql
    ));
}
