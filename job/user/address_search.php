<?php

if (!isset($_GET['q'])) {
    die("No address query provided.");
}

$query = urlencode($_GET['q']);

//$apiKey = getenv('589fbf464dmshd3035ff877df65cp1823bejsn0edfd38c31e7'); // stored securely
                  
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => "https://addressr.p.rapidapi.com/?q={$query}&countries[]=AU",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTPHEADER => [
        "x-rapidapi-host: addressr.p.rapidapi.com",
		"x-rapidapi-key: 589fbf464dmshd3035ff877df65cp1823bejsn0edfd38c31e7"
	],
]);
$response = curl_exec($curl);

if ($response === false) {
    die("cURL Error: " . curl_error($curl));
}

curl_close($curl);

header('Content-Type: application/json');
echo "the outcome".$response;