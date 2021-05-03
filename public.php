<?php

include __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;


$bot = new Discord([
    'token' => '######',
]);

$bot->on('ready', function ($discord){
    echo "Bot Started.", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function ($message){
        $param = explode(' ', $message->content);
        if($param[0] == "!stats"){

            if(!is_null($param[1])) {
                echo "{$message->author->username} ran {$message->content}";
                $response = file_get_contents("https://apiv2.nethergames.org/players/".$param[1]."/stats");
                $info = json_decode($response);
                if(is_object($info)){
                    $message->reply($param[1]."'s wins: " . $info->wins);
                }else{
                    $message->reply("Something went wrong.\nPlease check the IGN for typos.");
                }
            }else{
                $message->reply("Please input an username.");
            }

        }
    });
});


$bot->run();




