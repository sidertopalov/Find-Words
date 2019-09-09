<?php
include_once 'functions.php';
include_once 'helpers/file.php';


$fileNew = new File();
$fileNew->load('bg_BG_new.txt');

$doc = new DOMDocument();

$totalWords = 0;
$requests = 85;

$sleepStep = 60;
$nextSleep = $requests + $sleepStep;

$startFrom = 'аба'; // аба

// synonymous | unilingual | pravopisen-rechnik
$domain = 'https://slovored.com/search/scrollwords?isUTF8=true&dictionary=synonymous&direction=next&word=';
die('exit');

$txt = '';
while ($content = file_get_contents_utf8(($domain . $startFrom))) {
    $requests++;
    echo $domain . $startFrom . '<br/>';
    $doc->loadHTML($content); 
    $selector = new DOMXPath($doc);
    $result = $selector->query('//a');
    
    foreach($result as $node) {
        $temp = trim(mb_convert_encoding($node->nodeValue, "auto"));
        if(strpos($temp, ' ') !== false) {
            continue;
        }

        $txt = $temp;
        if($fileNew->isExist($temp)) {
            continue;
        }
        $totalWords++;

        // echo $txt . '<br/>';
        $fileNew->write($txt);
    }

    if($startFrom == $txt) {
        break;
    }
    $startFrom = $txt;
    // var_dump($startFrom);
    // die;
    if($requests >= $nextSleep) {
        $nextSleep = $nextSleep + $sleepStep;
        sleep(0.2);
    }
}
echo "<h2> TOTAL WORDS: " . $totalWords . '</h2>';
echo "<h2> TOTAL REQUESTS: " . $requests . '</h2>';
die;


?>