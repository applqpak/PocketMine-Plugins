<?php

namespace globalshield\level;

use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\utils\Config;
use pocketmine\Player;

class Shield{
    const BREAKING = 0;
    const INTERACTING = 1;
    const PLACING = 2;
    const EMPTYING = 3;
    const FILLING = 4;
    const TELEPORTING = 5;
    const DECAYING = 6;
    const EDITING = 7;
    const ARMOR_CHANGING = 8;
    const DAMAGING = 9;
    const BURNING = 10;
    const EXPLODING = 11;
    const INVENTORY_CHANGING = 12;
    const HEALING = 13;
    const DROPPING = 14;
    const CHATTING = 15;
    const GAMEMODE_CHANGING = 16;
    const EATING = 17;
    /** @var Level */
    private $level;
    /** @var Config */
    private $config;
    /** @var bool */
    private $enabled;
    /**
     * @param Level $level
     * @param Config $config
     */
    public function __construct(Level $level, Config $config){
        $this->level = $level;
        $this->config = $config;
        $this->enabled = true;
    }
    /**
     * @return Shield
     */
    public function getLevel(){
        return $this->level;
    }
    /**
     * @param int $type
     */
    public function isAllowed($type){
        switch($type){
            case self::BREAKING:
                $key = "breakBlock";
                break;
            case self::INTERACTING:
                $key = "interaction";
                break;
            case self::PLACING:
                $key = "placeBlock";
                break;
            case self::EMPTYING:
                $key = "emptyBucket";
                break;
            case self::FILLING:
                $key = "fillBucket";
                break;
            case self::TELEPORTING:
                $key = "teleportation";
                break;
            case self::DECAYING:
                $key = "decayLeave";
                break;
            case self::EDITING:
                $key = "editSign";
                break;
            case self::ARMOR_CHANGING:
                $key = "changeArmor";
                break;
            case self::DAMAGING:
                $key = "damageEntity";
                break;
            case self::BURNING:
                $key = "burnEntity";
                break;
            case self::EXPLODING:
                $key = "explosion";
                break;
            case self::INVENTORY_CHANGING:
                $key = "editInventory";
                break;
            case self::HEALING:
                $key = "regainHealth";
                break;
            case self::DROPPING:
                $key = "dropItem";
                break;
            case self::CHATTING:
                $key = "sendChat";
                break;
            case self::GAMEMODE_CHANGING:
                $key = "editGamemode";
                break;
            case self::EATING:
                $key = "consumeItem";
                break;
        }
        if($this->isEnabled()){
            return $this->config->get($key) === true;
        }
        return true;
    }
    /**
     * @param Item $item
     * @return bool
     */
    public function isItemBanned(Item $item){
        if($this->isEnabled()){
            return in_array($item->getId().":".$item->getDamage(), $this->config->get("bannedItems"));
        }
        return false;
    }
    /**
     * @param Player $player
     * @return bool
     */
    public function isPlayerBanned(Player $player){
        if($this->isEnabled()){
            return in_array(strtolower($player->getName()), $this->config->get("bannedPlayers"));
        }
        return false;
    }
    /**
     * @return bool
     */
    public function isEnabled(){
        return $this->enabled === true;
    }
    /**
     * @param bool $value
     */
    public function setEnabled($value){
        $this->enabled = (bool) $value;
    }
}