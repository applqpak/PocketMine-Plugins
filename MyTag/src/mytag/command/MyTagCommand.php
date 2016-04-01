<?php

namespace mytag\command;

use mytag\MyTag;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class MyTagCommand extends Command{
    /** @var MyTag */
    private $plugin;
    /**
     * @param MyTag $plugin
     */
    public function __construct(MyTag $plugin){
        parent::__construct("mytag", "Shows all MyTag commands", null, ["mt"]);
        $this->setPermission("mytag.command.mytag");
    	$this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     */
    private function sendCommandHelp(CommandSender $sender){
    	$sender->sendMessage("MyTag commands:");
    	$sender->sendMessage("/mytag address: Shows IP address and port number on the name tag");
    	$sender->sendMessage("/mytag chat: Shows the last message spoken on the name tag");
    	$sender->sendMessage("/mytag health: Shows health on the name tag");
    	$sender->sendMessage("/mytag help: Shows all MyTag commands");
    	$sender->sendMessage("/mytag hide: Hides the name tag");
    	$sender->sendMessage("/mytag restore: Restores current name tag to the default name tag");
    	$sender->sendMessage("/mytag set: Sets the name tag to whatever is specified");
    	$sender->sendMessage("/mytag view: Shows the name tag with a message");
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
	    	case "address":
                    return true;
	    	case "chat":
                    return true;
	    	case "health":
                    return true;
	    	case "help":
                    return true;
	    	case "hide":
                    return true;
	      	case "restore":
                    return true;
	        case "set":
                    return true;
	        case "view":
                    return true;
                default:
                    $sender->sendMessage("Usage: /mytag <sub-command> [parameters]");
                    return false;
	    }
    	}
    	else{
	    $this->sendCommandHelp($sender);
            return false;
    	}
    }
}
