<?php

namespace phputils\command;

use phputils\task\QueryPocketMineTask;
use phputils\PHPUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class PHPUtilsCommand extends Command{
    /** @var PHPUtils */
    private $plugin;
    /**
     * @param PHPUtils $plugin
     */
    public function __construct(PHPUtils $plugin){
        parent::__construct("phputils", "Shows all PHPUtils commands", null, ["pu"]);
        $this->setPermission("phputils.command.phputils");
        $this->plugin = $plugin;
    }
    /**
     * @param CommandSender $sender
     */
    private function sendCommandHelp(CommandSender $sender){
        $commands = [
            "algos" => "Lists all the registered hashing algorithms",
            "class" => "Checks if the specified class exists",
            "extens" => "Lists all the loaded PHP extensions",
            "func" => "Checks if the specified function exists",
            "hash" => "Returns a hash the specified string using the specified hashing algorithm",
            "help" => "Shows all PHPUtils commands",
            "php" => "Gets info about the PHP software the system is using",
            "plugin" => "Sends info about the specified plugin retrieved from the PocketMine server",
            "shell" => "Executes a command in the command shell",
        ];
        $sender->sendMessage("PHPUtils commands:");
        foreach($commands as $name => $description){
            $sender->sendMessage(($this->plugin->isCommandEnabled($name) ? TextFormat::GREEN : TextFormat::RED)."/phputils ".$name.": ".$description);
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
            if($this->plugin->isCommandEnabled($args[0]) === PHPUtils::NOT_FOUND){
                $sender->sendMessage(TextFormat::RED."Invalid sub-command specified, please use \"/phputils help\".");
                return false;
            }
            if($this->plugin->isCommandEnabled($args[0]) === PHPUtils::DISABLED){
                $sender->sendMessage(TextFormat::RED."That command is disabled.");
                return false;
            }
            switch(strtolower($args[0])){
                case "algos":
                    $algo = $this->plugin->getAlgorithms();
                    $sender->sendMessage("Algorithms (".$algo[0]."): ".$algo[1]);
                    return true;
                case "class":
                    if(isset($args[1])){
                        $sender->sendMessage("Class ".$args[1]." ".(class_exists($args[1], false) ? "was" : "was not")." found.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a class path.");
                    }
                    return true;
                case "extens":
                    $ext = $this->plugin->getExtensions();
                    $sender->sendMessage("Extensions (".$ext[0]."): ".$ext[1]);
                    return true;
                case "func":
                    if(isset($args[1])){
                        $sender->sendMessage("Function ".$args[1]." ".(function_exists($args[1]) ? "was" : "was not")." found.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a function name.");
                    }
                    return true;
                case "hash":
                    if(isset($args[1])){
                        if(isset($args[2])){
                            try{
                                $sender->sendMessage("Hashed using the ".$args[1]." algorithm: ".hash($args[1], implode(" ", array_slice($args, 2))));
                            }
                            catch(\RuntimeException $exception){
                                $sender->sendMessage(TextFormat::RED."Failed to hash, \"".$args[1]."\" isn't a registered hashing algorithm.");
                            }
                        }
                        else{
                            $sender->sendMessage(TextFormat::RED."Failed to hash due to insufficient parameters given.");
                        }
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a algorithm name.");
                    }
                    return true;
                case "help":
                    $this->sendCommandHelp($sender);
                    return true;
                case "php":
                    $this->plugin->sendPHPInfo($sender);
                    return true;
                case "plugin":
                    if(isset($args[1])){
                        $plugin = implode(" ", array_slice($args, 1));
                        $sender->sendMessage(TextFormat::GREEN."Searching for \"".$plugin."\", this may take a moment...");
                        $this->plugin->addActive($sender);
                        $sender->getServer()->getScheduler()->scheduleAsyncTask(new QueryPocketMineTask($plugin, $sender->getName()));
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a plugin name.");
                    }
                    return true;
                case "shell":
                    if(isset($args[1])){
                        $command = implode(" ", array_slice($args, 1));
                        shell_exec($command);
                        $sender->sendMessage(TextFormat::GREEN."Executed \"".$command."\" on the command shell.");
                    }
                    else{
                        $sender->sendMessage(TextFormat::RED."Please specify a command.");
                    }
                    return true;
                default:
                    $sender->sendMessage("Usage: /phputils <sub-command> [parameters]");
                    return false;
            }
        }
        else{
            $this->sendCommandHelp($sender);
            return false;
        }
    }
}