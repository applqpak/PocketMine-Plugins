<?php

namespace rapidcmd;

use pocketmine\plugin\PluginBase;
use rapidcmd\command\RCMDStorage;
use rapidcmd\command\RapidCMDCommand;

class RapidCMD extends PluginBase{
    /** @var RCMDStorage */
    private $storage;
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->storage = new RCMDStorage($this);
        $this->getServer()->getCommandMap()->register("rapidcmd", new RapidCMDCommand($this));
        $this->getCommandStorage()->registerDefaults();
    }
    /**
     * @return RCMDStorage
     */
    public function getCommandStorage(){
        return $this->storage;
    }
}