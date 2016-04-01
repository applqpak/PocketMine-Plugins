<?php

namespace mytag;

use mytag\command\MyTagCommand;
use mytag\event\MyTagListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\Player;

class MyTag extends PluginBase{
    /** @var Config */
    private $nametags;
    public function onEnable(){
        $this->saveDefaultConfig();
        @mkdir($this->getDataFolder());
        $this->nametags = new Config($this->getDataFolder()."nametags.yml", Config::YAML);
    	$this->getServer()->getCommandMap()->register("mytag", new MyTagCommand($this));
        $this->getServer()->getPluginManager()->registerEvents(new MyTagListener($this), $this);
    }
    /**
     * @param Player $player
     * @return string
     */
    public function getSavedNameTag(Player $player){
        return $this->nametags->get(strtolower($player->getName()));
    }
    /**
     * @param Player $player
     */
    public function saveNameTag(Player $player){
        $this->nametags->set(strtolower($player->getName()), $player->getNameTag());
        $this->nametags->save();
    }
}
