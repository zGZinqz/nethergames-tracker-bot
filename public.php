<?php

include __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;


    $bot = new Discord([
    'token' => '#####',
    ]);

    $bot->on('ready', function ($discord){
    echo "Bot Started.", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function ($message, $discord){
        if($message->content == "ok"){
            echo "{$message->author->username} ran {$message->content}";
            $message->channel->sendMessage("no");
        }
      });
    });


    $bot->run();




