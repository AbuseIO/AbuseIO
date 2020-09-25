<?php
/*
 * This file allows you to use the old ASH tokens and exchange them for a new ASH token
 * by redirecting the client. You can add this /legacy dir the old ash-abuseio.domain.tld
 * document root in the old vhost configuration.
 *
 * You should remove this vhost after a successfull migration where the old tickets are not
 * used anymore, e.g. after a few months.
 */

$configFile = __DIR__.'/../../.env';

if (!is_file($configFile)) {
    exit('500 - No configuration');
} else {
    $configLines = file(__DIR__.'/../../.env');
    foreach ($configLines as $configLine) {
        if (!empty(trim($configLine))) {
            $configData = explode('=', trim($configLine), 2);
            define($configData[0], $configData[1]);
        }
    }
}

if (empty($_GET['id']) ||
   empty($_GET['token']) ||
   !is_numeric($_GET['id'])
) {
    exit('403 - 1');
}

$conn3 = new mysqli(V3DB_HOST, V3DB_USERNAME, V3DB_PASSWORD, V3DB_DATABASE);
if ($conn3->connect_error) {
    exit('500 - Connection v3 failed ');
}

$conn4 = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($conn4->connect_error) {
    exit('500 - Connection v4 failed ');
}

// Get token from v3
$sql = "SELECT * FROM Reports WHERE ID = '{$_GET['id']}';";
$result = $conn3->query($sql);

if ($result->num_rows === 1) {
    $ticket = $result->fetch_assoc();
    $oldToken = md5($ticket['ID'].$ticket['IP'].$ticket['Class']);
} else {
    exit('403 - 2');
}

// Get token from v4
$sql = "SELECT * FROM tickets WHERE id = '{$_GET['id']}';";
$result = $conn4->query($sql);

if ($result->num_rows === 1) {
    $ticket = $result->fetch_assoc();
    $newToken = md5($ticket['id'].$ticket['ip'].$ticket['ip_contact_reference']);
} else {
    exit('403 - 2');
}

if (!empty($_GET['token']) &&
    $_GET['token'] == $oldToken
) {
    header('HTTP/1.1 301 Moved Permanently');
    header("Location: https://abuseio.bit.nl/ash/collect/{$_GET['id']}/{$newToken}");
} else {
    exit('403 - 3');
}

$conn3->close();
$conn4->close();
