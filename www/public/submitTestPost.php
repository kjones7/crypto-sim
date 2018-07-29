<?php

require dirname(__DIR__) . '/vendor/autoload.php';

$data = array(
    "category" => "kittensCategory",
    "title" => "Kittens Galore"
);

$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
$socket->connect("tcp://127.0.0.1:5555");

$socket->send(json_encode($data));