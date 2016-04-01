<?php

namespace phputils;

use phputils\command\PHPUtilsCommand;
use phputils\task\QueryPocketMineTask;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class PHPUtils extends PluginBase{
    const NOT_FOUND = -1;
    const DISABLED = 0;
    const ENABLED = 1;
    /** @var CommandSender[] */
    private $active = [];
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->getServer()->getCommandMap()->register("phputils", new PHPUtilsCommand($this));
    }
    /**
     * @param string $name
     * @return int
     */
    public function isCommandEnabled($name){
        if($this->getConfig()->exists($name, true)){
            if($this->getConfig()->get(strtolower($name))){
                return self::ENABLED; //Command found, and is enabled
            }
            return self::DISABLED; //Command found, and is disabled
        }
        return self::NOT_FOUND; //Command not found
    }
    /**
     * @return array
     */
    public function getAlgorithms(){
        $algoCount = 0;
        $algoNames = "";
        foreach(hash_algos() as $algo){
            $algoNames .= $algo.", ";
            $algoCount++;
        }
        return [$algoCount, $algoNames];
    }
    /**
     * @return array
     */
    public function getExtensions(){
        $extCount = 0;
        $extNames = "";
        foreach(get_loaded_extensions() as $extension){
            $extNames .= $extension.", ";
            $extCount++;
        }
        return [$extCount, $extNames];
    }
    /**
     * @param CommandSender $sender
     */
    public function sendPHPInfo(CommandSender $sender){
        $info = [
            "CWD" => getcwd(),
            "GID" => getmygid(),
            "PID" => getmypid(),
            "UID" => getmyuid(),
            "Memory-usage" => memory_get_usage(true),
            "Memory-peak-usage" => memory_get_peak_usage(true),
            "PHP-version" => phpversion(),
            "Zend-version" => zend_version()
        ];
        foreach($info as $key => $value){
            $sender->sendMessage($key.": ".$value);
        }
    }
    /**
     * @param string $sender
     * @param mixed $data
     */
    public function sendPluginInfo($sender, $data){
        $sender = $this->active[strtolower($sender)];
        if(is_array($data)){
            $sender->sendMessage(TextFormat::GREEN."Successfully retrieved information on ".$data["title"]." by ".$data["author_username"]."!"); //Server is online, the plugin was found
            foreach($data as $name => $info){
                $sender->sendMessage($name.": ".$info);
            }
            //var_dump($data);
        }
        else{
            switch($data){
                case QueryPocketMineTask::SERVER_OFFLINE:
                    $sender->sendMessage(TextFormat::RED."Failed to retrieve data, either you or the server is offline."); //Server is offline
                    break;
                case QueryPocketMineTask::PLUGIN_NOT_FOUND:
                    $sender->sendMessage(TextFormat::RED."Failed to retrieve data, the plugin wasn't found."); //Server is online, but the plugin wasn't found
                    break;
                default:
                    $sender->sendMessage(TextFormat::RED."Failed to retrieve data, an unknown error occurred."); //This will most likely never happen
                    break;
            }
        }
        $this->removeActive($sender);
    }
    /**
     * @param CommandSender $sender
     */
    public function addActive(CommandSender $sender){
        if(isset($this->active[strtolower($sender->getName())])){
            $sender->sendMessage(TextFormat::RED."Please wait until the current search finishes.");
        }
        else{
            $this->active[strtolower($sender->getName())] = $sender;
        }
    }
    /**
     * @param CommandSender $sender
     */
    public function removeActive(CommandSender $sender){
        if(isset($this->active[strtolower($sender->getName())])){
            unset($this->active[strtolower($sender->getName())]);
        }
    }
}