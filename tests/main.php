<?php
include __DIR__ . '/../vendor/autoload.php';

use Cclilshy\SD\SDClient;

$client = new SDClient('http://192.168.0.11:7860');


var_dump($client->txt2img([]));
