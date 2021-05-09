<?php

include __DIR__.'/vendor/autoload.php';

ini_set('memory_limit', '-1');

use Discord\Discord;
use Discord\WebSockets\Intents;
use Discord\WebSockets\Event;
use Discord\Parts\Embed\Embed;


$bot = new Discord([
    'token' => '####',
    'loadAllMembers' => true,
    'intents' => Intents::getDefaultIntents() | Intents::GUILD_MEMBERS,
]);

$bot->on('ready', function ($discord){
    echo "Bot Started.", PHP_EOL;

    $discord->on(Event::MESSAGE_CREATE, function ($message){
        $param = explode(' ', $message->content);

        /*if($param[0] == "!stats"){
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

        }*/
    });

    $discord->on('message', function ($message, $discord) {
        $param = explode(' ', $message->content);

        if($param[0] == "ng!track") {

            /*param 1 = what to track
             param 2 = ur ign idiot
             param 3 = time to track for
            */

            if (array_key_exists(1, $param)) {
                if (array_key_exists(2, $param)) {
                    if (array_key_exists(3, $param)) {
                        if ($param[1] == "kills" or $param[1] == "bwWins" or $param[1] == "bwKills" or $param[1] == "wins" or $param[1] == "bwDeaths" or $param[1] == "deaths") {
                            $response = file_get_contents("https://apiv2.nethergames.org/players/" . $param[2] . "/stats");
                            $info = json_decode($response);
                            if (is_object($info)) {
                                if(is_numeric($param[3])) {
                                    if($param[3] > "300") {
                                        $embed = new Embed($discord);
                                        $embed->setTitle("Success");
                                        $embed->setDescription("Now tracking your " . $param[1] . " stats on the IGN " . $param[2] . " for " . $param[3] . " seconds.\nMake sure your DMs are on so we can send your tracked stats in the time given!");
                                        $embed->setColor("#16FF00");
                                        $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                        $embed->setTimestamp();
                                        $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                                        var_dump($info, $response);


                                        $GLOBALS['timer-'.$message->user->id] = $discord->getLoop()->addPeriodicTimer($param[3], function () use ($message, $param, $info, $discord) {
                                            $newresponse = file_get_contents("https://apiv2.nethergames.org/players/" . $param[2] . "/stats");
                                            $newinfo = json_decode($newresponse);
                                            $finalRes = $newinfo->{$param[1]} - $info->{$param[1]};
                                            $id = $message->author->id;
                                            $message->user->sendMessage("<@$id> We successfully tracked your stats!\n\n**INFO:**\nFinal Results of Tracked Stats\nGained " . $param[1] . ": " . $finalRes . "\n\n**Other Info:**\nIGN: " . $param[2] . "\nTime tracked for: " . $param[3] . " seconds.\nOld Stats: " . $info->{$param[1]} . "\nNew stats: " . $newinfo->{$param[1]});
                                            $discord->getLoop()->cancelTimer($GLOBALS['timer-' . $message->user->id]);
                                        });

                                    }else{
                                        $message->reply("The seconds counter cannot be less than 300 seconds (5 Minutes) because of nethergame's caching system. Please put a value higher than 300 seconds.");
                                    }
                                }else{
                                    $embed = new Embed($discord);
                                    $embed->setTitle("Error");
                                    $embed->setDescription("Please use numeric digits for how much time to track. (Specified in Seconds. E.G: ng!track bwWins thebarii 3)");
                                    $embed->setColor("#FF0000");
                                    $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                    $embed->setTimestamp();
                                    $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                                }
                            } else{
                                $embed = new Embed($discord);
                                $embed->setTitle("Error");
                                $embed->setDescription("Something went wrong.\nPlease check the IGN for typos.");
                                $embed->setColor("#FF0000");
                                $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                $embed->setTimestamp();
                                $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                            }


                        } else {
                            $embed = new Embed($discord);
                            $embed->setTitle("Error");
                            $embed->setDescription("Not a valid game-statistic to track! Get a list of stats we can track using **ng!tracklist**");
                            $embed->setColor("#FF0000");
                            $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                            $embed->setTimestamp();
                            $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                        }
                    } else {
                        $embed = new Embed($discord);
                        $embed->setTitle("Error");
                        $embed->setDescription("You must specify how long you want to track for (Seconds)! Get a list of stats we can track using **ng!tracklist**");
                        $embed->setColor("#FF0000");
                        $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                        $embed->setTimestamp();
                        $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                    }
                } else {
                    $embed = new Embed($discord);
                    $embed->setTitle("Error");
                    $embed->setDescription("You must put your IGN! Get a list of stats we can track using **ng!tracklist**");
                    $embed->setColor("#FF0000");
                    $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                    $embed->setTimestamp();
                    $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                }
            } else {
                $embed = new Embed($discord);
                $embed->setTitle("Error");
                $embed->setDescription("You must specify what mode you want to track! Get a list of stats we can track using **ng!tracklist**");
                $embed->setColor("#FF0000");
                $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                $embed->setTimestamp();
                $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
            }
        }elseif($param[0] == "ng!tracklist"){
            $message->reply("We can only track these statistics at the moment. Capitalizations must be used.\n\n**LIST:**\nbwKills\nbwDeaths\nbwWins\nwins\nkills\ndeaths");
        }elseif($param[0] == "ng!source"){
            $message->reply("My source code can be found at https://github.com/BariPlayzYT/nethergames-tracker-bot on github written in PHP by Bari!");
        }elseif($message->content == "ng!tracker help"){
            $message->reply("**COMMANDS:**\nng!tracker help (this command)\nng!track [What To Track, use ng!tracklist to see what you can track.] [IGN] [Time to track for (in seconds)]\nng!source (get the source code)");
        }
    });

});


$bot->run();




