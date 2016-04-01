<?php

namespace rapidcmd\utils;

use pocketmine\command\CommandSender;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\Server;

class ActionParser{
    /**
     * @param CommandSender $sender
     * @param string $line
     */
    public function run(CommandSender $sender, $line){
        $part = explode(";", $line);
        switch(strtolower($part[0])){
            case "run":
                Server::getInstance()->dispatchCommand(new ConsoleCommandSender(), $part[1]);
                break;
        }
    }
}