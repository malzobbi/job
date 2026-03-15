<?php
$url = "https://www.seek.com.au/help-desk-support-jobs?jobId=89510916&type=promoted";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0 Safari/537.36");
$html = curl_exec($ch);

curl_close($ch);

if (!$html) {
    
    die("Failed to fetch content");
}
libxml_use_internal_errors(true);
$dom = new DOMDocument();
$dom->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($dom);

$nodes = $xpath->query('//div[@data-automation="jobAdDetails"]');

if ($nodes->length === 0) {
    echo "No jobAdDetails div found.";
    exit;
}

$jobDetailsDiv = $nodes->item(0);

foreach ($jobDetailsDiv->childNodes as $section) {
    // Only text or tag nodes
    if ($section->nodeType === XML_ELEMENT_NODE) {
        echo $dom->saveHTML($section);
        echo "\n----------------------\n";
    }
}

echo trim($jobDetailsDiv->textContent);

