<?php

namespace rapidcmd\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use rapidcmd\RapidCMD;

class RapidCMDCommand extends Command{
    /** @var RapidCMD */
    private $plugin;
    /**
     * @param RapidCMD $plugin
     */
    public function __construct(RapidCMD $plugin){
        parent::__construct("rapidcmd", "Shows all RapidCMD commands", null, ["rc"]);
        $this->setPermission("rapidcmd.command.rapidcmd");
        $this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     */
    public function sendCommandHelp(CommandSender $sender){
        $commands = [
            "addcmd" => "Creates a new RCMD",
            "cmd" => "Sends information about a command",
            "delcmd" => "Deletes a RCMD, if it exists",
            "help" => "Shows all RapidCMD commands",
            "list" => "Returns a list of names of loaded RCMDs",
        ];
        $sender->sendMessage("RapidCMD commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage("/rapidcmd ".$name.": ".$description);
        }
    }
    /**
     * @param CommandSender $sender
     * @param string $label
     * @param string[] $args
     * @return bool
     */
    public function execute(CommandSender $sender, $label, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(isset($args[0])){
            switch(strtolower($args[0])){
                case "ac":
                case "addcmd":
                    return true;
                case "cmd":
                    return true;
                case "dc":
                case "delcmd":
                    if(isset($args[1])){
                        if($this->plugin->getCommandStorage()->removeCommand($args[1])){
                            $sender->sendMessage(TextFormat::GREEN."Successfully disabled RCMD: ".strtolower($args[1]).".");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."Failed to remove due to nonexistent command specified.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a command name.");
                    }
                    return true;
                case "help":
                    $this->sendCommandHelp($sender);
                    return true;
                case "list":
                    $count = 0;
                    $names = "";
                    foreach($this->plugin->getCommandStorage()->getCommands() as $command){
                        $names .= $command->getName().", ";
                        $count++;
                    }
                    $sender->sendMessage("RCMDs (".$count."): ".substr($names, 0, -2));
                    return true;
                default:
                    $sender->sendMessage("Usage: /rapidcmd <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
    }
}