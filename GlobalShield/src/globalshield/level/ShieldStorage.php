<?php

namespace globalshield\level;

use globalshield\level\Shield;
use globalshield\GlobalShield;
use pocketmine\level\Level;
use pocketmine\utils\Config;

class ShieldStorage{
    /** @var GlobalShield */
    private $plugin;
    /** @var Shield[] */
    private $shields;
    /**
     * @param GlobalShield $plugin
     */
    public function __construct(GlobalShield $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @param Level $level
     */
    public function addShield(Level $level){
        $shield = $this->shields[strtolower($level->getName())];
        if(isset($shield)){
            $shield->setEnabled(true);
            $this->plugin->getServer()->getLogger()->info("Reloaded shield for level \"".$level->getName()."\".");
        }
        else{
            $ilevel = strtolower($level->getName());
            $this->shields[$ilevel] = new Shield($level, new Config($this->plugin->getDataFolder().$ilevel.".yml", Config::YAML, [
                "breakBlock" => false,
                "burnEntity" => false,
                "consumeItem" => false,
                "damageEntity" => false,
                "decayLeave" => false,
                "dropItem" => false,
                "editGamemode" => false,
                "editInventory" => false,
                "editSign" => false,
                "emptyBucket" => false,
                "explosion" => false,
                "fillBucket" => false,
                "interaction" => false,
                "placeBlock" => false,
                "regainHealth" => false,
                "sendChat" => true,
                "teleportation" => false,
                "bannedItems" => [],
                "bannedPlayers" => []
            ]));
            $this->plugin->getServer()->getLogger()->info("Loaded shield for level \"".$level->getName()."\".");
        }
    }
    /**
     * @param Level $level
     */
    public function removeShield(Level $level){
        $shield = $this->shields[strtolower($level->getName())];
        if(isset($shield)){
            if($shield->isEnabled()){
                $shield->setEnabled(false);
                $this->plugin->getServer()->getLogger()->info("Unloaded shield for level \"".$level->getName()."\".");
            }
        }
    }
    /**
     * @param Level $level
     * @return Shield|null
     */
    public function getShield(Level $level){
        if(isset($this->shields[strtolower($level->getName())])){
            return $this->shields[strtolower($level->getName())];
        }
        return null;
    }
    /**
     * @param string $name
     * @return Shield|null
     */
    public function getShieldByName($name){
        if(isset($this->shields[strtolower($name)])){
            return $this->shields[strtolower($name)];
        }
        return null;
    }
}