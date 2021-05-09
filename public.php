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

        /*
          if($param[0] == "!stats"){
                if(!is_null($param[1])) {
                    echo "{$message->author->username} ran {$message->content}";
                    $response = file_get_contents("https://apiv2.nethergames.org/players/".$param[1]."/stats");
                    $info = json_decode($response);
                    if(is_object($info)){
                        $message->reply($param[1]."'s wins: " . $info->wins);
                    } else {
                        $message->reply("Something went wrong.\nPlease check the IGN for typos.");
                    }
                } else{
                    $message->reply("Please input an username.");
            }
        }
        */
    });

    $discord->on('message', function ($message, $discord) {
        $param = explode(' ', $message->content);

        if ($param[0] == "ng!track") {

            /*
              param 1 = Stat to track
              param 2 = Player's Xbox tag
              param 3 = Amount of time to track
            */

            if (array_key_exists(1, $param)) {
                if (array_key_exists(2, $param)) {
                    if (array_key_exists(3, $param)) {

                        if ($param[1] == "kills" or $param[1] == "bwWins" or $param[1] == "bwKills" or $param[1] == "wins" or $param[1] == "bwDeaths" or $param[1] == "deaths") {
                            $response = file_get_contents("https://apiv2.nethergames.org/players/" . $param[2] . "/stats");
                            $info = json_decode($response);
                            if (is_object($info)) {
                                if (is_numeric($param[3])) {
                                    if ($param[3] > "300") {
                                        $embed = new Embed($discord);
                                        $embed->setTitle("Success");
                                        $embed->setDescription("Now tracking your " . $param[1] . " stats for **" . $param[2] . "** for **" . $param[3] . "** seconds.\nMake sure your DMs are enabled/allowed so I can send your tracked stats after the time is up!");
                                        $embed->setColor("#16FF00");
                                        $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                        $embed->setTimestamp();
                                        $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                                        var_dump($info, $response);

                                        if($param[1] == "kills" or $param[1] == "wins" or $param[1] == "deaths" or $param[1] == "level" or $param[1] == "xp" or $param[1] == "statusCredits") {
                                            $discord->getLoop()->addTimer($param[3], function () use ($message, $param, $info, $discord) {
                                                $newresponse = file_get_contents("https://apiv2.nethergames.org/players/" . $param[2] . "/stats");
                                                $newinfo = json_decode($newresponse);
                                                $finalRes = $newinfo->{$param[1]} - $info->{$param[1]};
                                                $id = $message->author->id;
                                                $message->user->sendMessage("<@$id> We successfully tracked your stats!\n\n**INFO:**\nFinal results of tracked stats:\nGained " . $param[1] . ": " . $finalRes . "\n\n**Other Info:**\nIGN: " . $param[2] . "\nTime tracked for: " . $param[3] . " seconds.\nOld Stats: " . $info->{$param[1]} . "\nNew stats: " . $newinfo->{$param[1]});
                                            });

                                        }elseif($param[1] == "bwWins" or $param[1] == "bwKills" or $param[1] == "bwDeaths" or $param[1] == "tbWins" or $param[1] == "tbKills" or $param[1] == "tbDeaths" or $param[1] == "swWins" or $param[1] == "swDeaths" or $param[1] == "swKills") {
                                            $discord->getLoop()->addTimer($param[3], function () use ($message, $param, $info, $discord) {
                                                $newresponse = file_get_contents("https://apiv2.nethergames.org/players/" . $param[2] . "/stats");
                                                $newinfo = json_decode($newresponse);
                                                $finalRes = $newinfo->extra->{$param[1]} - $info->extra->{$param[1]};
                                                $id = $message->author->id;
                                                $message->user->sendMessage("<@$id> We successfully tracked your stats!\n\n**INFO:**\nFinal results of tracked stats:\nGained " . $param[1] . ": " . $finalRes . "\n\n**Other Info:**\nIGN: " . $param[2] . "\nTime tracked for: " . $param[3] . " seconds.\nOld Stats: " . $info->extra->{$param[1]} . "\nNew stats: " . $newinfo->extra->{$param[1]});
                                            });
                                        }

                                    } else { // User sends time less than 300 seconds
                                        $message->reply("The seconds counter cannot be less than 300 seconds (5 minutes) because of NetherGames' caching system. Please put a time higher than 300.");
                                    }
                                } else { // Time is NaN (Not a Number)
                                    $embed = new Embed($discord);
                                    $embed->setTitle("Error");
                                    $embed->setDescription("The time doesn't seem like a valid number. Please specify the time you want to track **in seconds**.");
                                    $embed->setColor("#FF0000");
                                    $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                    $embed->setTimestamp();
                                    $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                                }
                            } else { // User specifies an invalid Xbox tag or something else went wrong internally
                                $embed = new Embed($discord);
                                $embed->setTitle("Error");
                                $embed->setDescription("Either you have specified an invalid Xbox tag, or something else went wrong. (Check if you made any typos in the IGN?)");
                                $embed->setColor("#FF0000");
                                $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                                $embed->setTimestamp();
                                $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                            }

                        }else { // User specifies an invalid stat
                            $embed = new Embed($discord);
                            $embed->setTitle("Error");
                            $embed->setDescription("Not a valid game stat to track! Get a list of stats by running: `ng!tracklist`");
                            $embed->setColor("#FF0000");
                            $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                            $embed->setTimestamp();
                            $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                        }
                    } else { // User does not specify a time
                        $embed = new Embed($discord);
                        $embed->setTitle("Error");
                        $embed->setDescription("You must specify the length of time to track!");
                        $embed->setColor("#FF0000");
                        $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                        $embed->setTimestamp();
                        $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                    }
                } else { // User does not specify a Xbox tag
                    $embed = new Embed($discord);
                    $embed->setTitle("Error");
                    $embed->setDescription("You did not specify an Xbox tag!");
                    $embed->setColor("#FF0000");
                    $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                    $embed->setTimestamp();
                    $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
                }
            } else { // User does not specify a stat to track
                $embed = new Embed($discord);
                $embed->setTitle("Error");
                $embed->setDescription("You must specify what stat you want to track! To see the list run: `ng!tracklist`");
                $embed->setColor("#FF0000");
                $embed->setFooter($message->author->username . ", Bot made by Bari", $av = $message->user->avatar);
                $embed->setTimestamp();
                $message->channel->sendMessage("<@" . $message->user->id . ">", false, $embed);
            }
        } else if ($param[0] == "ng!tracklist") { // tracklist command
            $message->reply("We can only track these statistics at the moment. These are **CaSe sensitive**.\n```bwKills\nbwDeaths\nbwWins\nwins\nkills\nswWins\nswKills\nswDeaths\ntbWins\ntbKills\ntbDeaths\nlevels\nxp\nstatusCredits```");
        } else if ($param[0] == "ng!source") { // source command
            $message->reply("The source code can be found at:\nhttps://github.com/BariPlayzYT/nethergames-tracker-bot");
        } else if ($message->content == "ng!tracker help") { // help command
            $message->reply("**COMMANDS:**\n`ng!track [stat*] [Xbox tag] [time**]` - Track a user for a certain amount of time\n\n`ng!source` - Sends a link to the source code\n\n\* To see the list of stats to track, run `ng!tracklist`\n** Time MUST be in seconds, and at least 301 (5 minutes and 1 second)");
        }
    });

});

$bot->run();
