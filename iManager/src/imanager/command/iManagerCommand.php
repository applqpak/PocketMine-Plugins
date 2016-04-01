<?php

namespace imanager\command;

use imanager\iManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use pocketmine\Player;

class iManagerCommand extends Command{
    /** @var iManager */
    private $plugin;
    /**
     * @param iManager $plugin
     */
    public function __construct(iManager $plugin){
        parent::__construct("imanager", "Shows all iManager commands", null, ["im"]);
        $this->setPermission("imanager.command.imanager");
    	$this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     */
    private function sendCommandHelp(CommandSender $sender){
    	$sender->sendMessage("iManager commands:");
    	$sender->sendMessage("/imanager addexempt: Adds a player's name to exempt.txt");
    	$sender->sendMessage("/imanager addip: Adds a player's IP address to ip.txt");
    	$sender->sendMessage("/imanager addresslist: Lists every player's IP address and port");
    	$sender->sendMessage("/imanager attackall: Attacks all the players in the server");
        $sender->sendMessage("/imanager block: Gets info about a block");
    	$sender->sendMessage("/imanager burn: Burns a player");
    	$sender->sendMessage("/imanager delexempt: Removes a player's name from exempt.txt");
    	$sender->sendMessage("/imanager delip: Removes a player's IP address from ip.txt");
    	$sender->sendMessage("/imanager deopall: Revokes all the player's OP status");
        $sender->sendMessage("/imanager entity: Gets info about an entity");
    	$sender->sendMessage("/imanager giveall: Gives the specified item to all players in the server");
    	$sender->sendMessage("/imanager heal: Heals a players");
    	$sender->sendMessage("/imanager help: Shows all iManager commands");
    	$sender->sendMessage("/imanager info: Gets all the information about a player");
    	$sender->sendMessage("/imanager kickall: Kicks all the players without EXEMPT status from the server");
    	$sender->sendMessage("/imanager killall: Kills all the players without EXEMPT status in the server");
        $sender->sendMessage("/imanager level: Gets info about a level");
    	$sender->sendMessage("/imanager opall: Grants OP status to everyone in the server");
    	$sender->sendMessage("/imanager ops: Lists all the OPs");
        $sender->sendMessage("/imanager player: Gets info about a player");
    	$sender->sendMessage("/imanager server: Gets info about the server");
        $sender->sendMessage("/imanager transferall: Transfers all players in the server without EXEMPT status to the specified server");
    	$sender->sendMessage("/imanager warpall: Teleports all players in the server without EXEMPT status to the given location");
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
                case "help":
                    $this->sendCommandHelp($sender);
                    return true;
                case "level":
                    if(isset($args[1])){
                        if($level = $sender->getServer()->getLevelByName($args[1])){
                            $this->plugin->getInfoFetcher()->sendLevelInfo($sender, $level);
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."Failed to get information due to invalid level name.");
                        } 
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a level name.");
                    }
                    return true;
                case "player":
                    if(isset($args[1])){
                        if($player = $sender->getServer()->getPlayer($args[1])){
                            $this->plugin->getInfoFetcher()->sendPlayerInfo($sender, $player);
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."Failed to get information due to invalid recipient.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a recipient.");
                    }
                    return true;
                default:
                    $sender->sendMessage("Usage: /imanager <sub-command> [parameters]");
                    return false;
    	    }
    	}
    	else{
	    $this->sendCommandHelp($sender);
            return false;
    	}
    }
}
