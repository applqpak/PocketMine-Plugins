<?php

namespace globalshield;

use globalshield\command\GlobalShieldCommand;
use globalshield\event\GlobalShieldListener;
use globalshield\level\ShieldStorage;
use pocketmine\plugin\PluginBase;

class GlobalShield extends PluginBase{
    /** @var ShieldStorage */
    private $storage;
    public function onEnable(){
        $this->saveDefaultConfig();
        $this->storage = new ShieldStorage($this);
        foreach($this->getServer()->getLevels() as $level){
            $this->getStorage()->addShield($level);
        }
        $this->getServer()->getCommandMap()->register("globalshield", new GlobalShieldCommand($this));
    	$this->getServer()->getPluginManager()->registerEvents(new GlobalShieldListener($this), $this);
    }
    public function onDisable(){
        foreach($this->getServer()->getLevels() as $level){
            $this->getStorage()->removeShield($level);
        }
    }
    /**
     * @return ShieldStorage
     */
    public function getStorage(){
        return $this->storage;
    }
}
