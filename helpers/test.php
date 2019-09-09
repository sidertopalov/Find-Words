<?php

include 'file.php';

// $file = new File('../dictionaries/bg_BG_no_duplicates.json');
// $words = json_decode(file_get_contents('../dictionaries/bg_BG.json'), true);
// $json = json_encode($words, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
// $file->write($json);
// die;

// $file = new File('../dictionaries/test.json');
// $words = json_decode(file_get_contents('../dictionaries/bg_BG_new.json'), true);
// $json = json_encode($words, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
// $file->write($json);
// die;

$file = new File('../dictionaries/merged_BG_no_duplicates.json');
$data1 = json_decode(file_get_contents('../dictionaries/bg_BG.json'), true);
$data2 = json_decode(file_get_contents('../dictionaries/bg_BG_new.json'), true);
$data = array_merge($data1, $data2);
// var_dump($data); die;
$json = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT );
$file->write($json);
die('1');