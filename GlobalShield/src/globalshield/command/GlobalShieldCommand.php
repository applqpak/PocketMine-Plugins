<?php

namespace globalshield\command;

use globalshield\GlobalShield;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class GlobalShieldCommand extends Command{
    /** @var GlobalShield */
    private $plugin;
    /**
     * @param GlobalShield $plugin
     */
    public function __construct(GlobalShield $plugin){
        parent::__construct("globalshield", "Shows all GlobalShield commands", null, ["gs"]);
        $this->plugin = $plugin;
    }
    private function sendCommandHelp(CommandSender $sender){
        $commands = [
            "help" => "Shows all GlobalShield commands",
            "load" => "Enables a shield for the specified world",
            "unload" => "Disables the shield of the specified world"
        ];
        $sender->sendMessage("GlobalShield commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage("/globalshield ".$name.": ".$description);
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
            $storage = $this->plugin->getStorage();
            switch(strtolower($args[0])){
                case "help":
                    $this->sendCommandHelp($sender);
                    return true;
                case "l":
                case "load":
                    if(isset($args[1])){
                        if($level = $sender->getServer()->getLevelByName($args[1])){
                            $storage->addShield($level);
                            $sender->sendMessage(TextFormat::GREEN."Loaded shield for level \"".$args[1]."\".");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That shield couldn't be found.");
                        }
                    }
                    return true;
                case "u":
                case "unload":
                    if(isset($args[1])){
                        if($level = $sender->getServer()->getLevelByName($args[1])){
                            $storage->removeShield($level);
                            $sender->sendMessage(TextFormat::GREEN."Unloaded shield for level \"".$args[1]."\".");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That shield couldn't be found.");
                        }
                    }
                    return true;
                default:
                    $sender->sendMessage("/globalshield <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
    }
}