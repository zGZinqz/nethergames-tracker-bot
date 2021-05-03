<?php

include __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;


    $bot = new Discord([
    'token' => 'ODM4NDQ2NTY0MjU5MDA0NDU2.YI7ONg.QVoaaNVU9cqXLc4JBwIszP5BHMQ',
    ]);

    $bot->on('ready', function ($discord){
    echo "Bot Started.", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function ($message, $discord){
        if($message->content == "ok"){
            echo "no";
        }
      });
    });


    $bot->run();




