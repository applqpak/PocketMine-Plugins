<?php

namespace queryfacade\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;
use queryfacade\QueryFacade;

class QueryFacadeCommand extends Command{
    /** @var QueryFacade */
    private $plugin;
    /**
     * @param QueryFacade $plugin
     */
    public function __construct(QueryFacade $plugin){
        parent::__construct("queryfacade", "Shows all QueryFacade commands", null, ["qf"]);
        $this->setPermission("queryfacade.command.queryfacade");
        $this->plugin = $plugin;
    }
    /** 
     * @param CommandSender $sender 
     */
    private function sendCommandHelp(CommandSender $sender){
        $commands = [
            "addplayer" => "Adds a player to the player list",
            "addplugin" => "Adds a plugin to the plugin list",
            "help" => "Shows all QueryFacade commands",
            "map" => "Changes the server's current default map name",
            "maxplayercount" => "Changes the server's max player count",
            "playercount" => "Changes the server's player count",
            "players" => "Returns a list of players being sent in query",
            "plugins" => "Returns a list of plugins being sent in query",
            "removeplayer" => "Removes the specified player from the player list",
            "removeplugin" => "Removes the specified plugin from the plugin list"
        ];
        $sender->sendMessage("QueryFacade commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage("/queryfacade ".$name.": ".$description);
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
            $modifier = $this->plugin->getModifier();
            switch(strtolower($args[0])){
                case "apr":
                case "addplayer":
                    if(isset($args[1])){
                        $modifier->addPlayer($args[1], isset($args[2]) ? $args[2] : "DUMMY", isset($args[3]) ? $args[3] : 19132);
                        $sender->sendMessage(TextFormat::GREEN."Added \"".$args[1]."\" to the player list.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Failed to add player, no name specified.");
                    }
                    return true;
                case "apn":
                case "addplugin":
                    if(isset($args[1]) and isset($args[2])){
                        $modifier->addPlugin($args[1], $args[2]);
                        $sender->sendMessage(TextFormat::GREEN."Added \"".$args[1]."\" to the plugin list.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Failed to add plugin, no name/version specified.");
                    }
                    return true;
                case "help":
                    $this->sendCommandHelp($sender);
                    return true;
                case "map":
                    if(isset($args[1])){
                        $modifier->setWorld($args[1]);
                        $sender->sendMessage(TextFormat::GREEN."Set map name to \"".$args[1]."\".");
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current map name is \"".$modifier->getWorld()."\".");
                    }
                    return true;
                case "mpc":
                case "maxplayercount":
                    if(isset($args[1])){
                        if(is_numeric($args[1])){
                            $modifier->setMaxPlayerCount($args[1]);
                            $sender->sendMessage(TextFormat::GREEN."Set max player count to ".$args[1].".");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."The specified amount is not an integer.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current max player count is ".$modifier->getMaxPlayerCount().".");
                    }
                    return true;
                case "pc":
                case "playercount":
                    if(isset($args[1])){
                        if(is_numeric($args[1])){
                            $modifier->setPlayerCount($args[1]);
                            $sender->sendMessage(TextFormat::GREEN."Set player count to ".$args[1].".");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."The specified amount is not an integer.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::YELLOW."Current player count is ".$modifier->getPlayerCount().".");
                    }
                    return true;
                case "pr":
                case "players":
                    $sender->sendMessage(TextFormat::YELLOW."There are currently ".count($modifier->getPlayers())." player(s)".(count($modifier->getPlayers()) > 0 ? ": ".$modifier->listPlayers() : "").".");
                    return true;
                case "pn":
                case "plugins":
                    $sender->sendMessage(TextFormat::YELLOW."There are currently ".count($modifier->getPlugins())." plugin(s)".(count($modifier->getPlugins()) > 0 ? ": ".$modifier->listPlugins() : "").".");
                    return true;
                case "rpr":
                case "removeplayer":
                    if(isset($args[1])){
                        if($modifier->removePlayer($args[1])){
                            $sender->sendMessage(TextFormat::GREEN."Removed \"".$args[1]."\" from the player list.");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That player couldn't be found.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a player.");
                    }
                    return true;
                case "rpn":
                case "removeplugin":
                    if(isset($args[1])){
                        if($modifier->removePlugin($args[1])){
                            $sender->sendMessage(TextFormat::GREEN."Removed \"".$args[1]."\" from the plugin list.");
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."That plugin couldn't be found.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a plugin.");
                    }
                    return true;
                default:
                    $sender->sendMessage("Usage: /queryfacade <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
    }
}
