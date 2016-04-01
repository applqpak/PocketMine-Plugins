<?php

namespace rapidcmd\command;

use rapidcmd\command\RCMD;
use rapidcmd\RapidCMD;

class RCMDStorage{
    /** @var RapidCMD */
    private $plugin;
    /**
     * @param RapidCMD $plugin
     */
    public function __construct(RapidCMD $plugin){
        $this->plugin = $plugin;
    }
    public function registerDefaults(){
        if(is_array($commands = $this->plugin->getConfig()->get("commands"))){
            $count = 0;
            foreach($commands as $command){
                if(!$this->isCommandRegistered($command["name"])){
                    $rcmd = new RCMD(strtolower($command["name"]), isset($command["description"]) ? $command["description"] : "", isset($command["usage"]) ? $command["usage"] : "", (isset($command["aliases"]) and is_array($command["aliases"])) ? $command["aliases"] : []);
                    $this->addCommand($rcmd);
                    $count++;
                }
            }
            $this->plugin->getServer()->getLogger()->info("Loaded ".$count."/".count($commands)." RCMD(s).");
        }
        else{
            $this->plugin->getServer()->getLogger()->critical("Failed to load RCMD(s), please make sure the config file is properly set up.");
        }
    }
    /**
     * @param RCMD $command
     * @return bool
     */
    public function addCommand(RCMD $command){
        if(!$this->isCommandRegistered($command->getName())){
            $this->plugin->getServer()->getCommandMap()->register($command->getName(), $command);
            return true;
        }
        return false;
    }
    /**
     * @param string $command
     * @return bool
     */
    public function removeCommand($command){
        if($this->isCommandRegistered($command)){
            $this->getCommand($command)->unregister($this->plugin->getServer()->getCommandMap());
            return true;
        }
        return false;
    }
    /**
     * @return RCMD[]
     */
    public function getCommands(){
        $rcmds = [];
        foreach($this->plugin->getServer()->getCommandMap()->getCommands() as $command){
            if($this->isCommandRegistered($command->getName())){
                $rcmds[] = $command;
            }
        }
        return $rcmds;
    }
    /**
     * @param string $name
     * @return RCMD|bool
     */
    public function getCommand($name){
        if($this->isCommandRegistered($name)){
            return $this->plugin->getServer()->getCommandMap()->getCommand($name);
        }
        return false;
    }
    /**
     * @param string $name
     * @return bool
     */
    public function isCommandRegistered($name){
        $rcmd = $this->plugin->getServer()->getCommandMap()->getCommand(strtolower($name));
        return $rcmd instanceof RCMD and $rcmd->isRegistered();
    }
}