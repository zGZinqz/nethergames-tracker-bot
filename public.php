<?php

include __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;


$bot = new Discord([
    'token' => 'urToken',
]);

$bot->on('ready', function ($discord){
    echo "Bot Started.", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function ($message){
        if($message->content == "!stats"){
            echo "{$message->author->username} ran {$message->content}";
            $response = file_get_contents("https://apiv2.nethergames.org/players/thebarii/stats");
            $info = json_decode($response);
            $message->reply("Your current wins: " .$info->wins);
        }
    });


});


$bot->run();




