<?php

namespace globalshield\event;

use globalshield\level\Shield;
use globalshield\GlobalShield;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\LeavesDecayEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\entity\EntityArmorChangeEvent;
use pocketmine\event\entity\EntityCombustEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerGameModeChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerItemHeldEvent;
use pocketmine\event\Listener;
use pocketmine\Player;

class GlobalShieldListener implements Listener{
    /** @var GlobalShield */
    private $plugin;
    /**
     * @param GlobalShield $plugin
     */
    public function __construct(GlobalShield $plugin){
        $this->plugin = $plugin;
    }
    /** 
     * @param BlockBreakEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onBlockBreak(BlockBreakEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::BREAKING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("breakNotAllowed"));
        }
    }
    /** 
     * @param BlockPlaceEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onBlockPlace(BlockPlaceEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::PLACING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("placeNotAllowed"));
        }
    }
    /**
     * @param LeavesDecayEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onLeavesDecay(LeavesDecayEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getBlock()->getLevel())->isAllowed(Shield::DECAYING)){
            $event->setCancelled(true);
        }
    }
    /**
     * @param SignChangeEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onSignChange(SignChangeEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getBlock()->getLevel())->isAllowed(Shield::EDITING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("signChangeNotAllowed"));
        }
    }
    /**
     * @param EntityArmorChangeEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityArmorChange(EntityArmorChangeEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if(!$this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::ARMOR_CHANGING)){
                $event->setCancelled(true);
                $player->sendTip($this->plugin->getConfig()->get("armorChangeNotAllowed"));
            }
        }
    }
    /**
     * @param EntityCombustEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityCombust(EntityCombustEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if(!$this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::BURNING)){
                $event->setCancelled(true);
            }
        }
    }
    /**
     * @param EntityDamageEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityDamage(EntityDamageEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if(!$this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::DAMAGING)){
                $event->setCancelled(true);
            }
        }
    }
    /**
     * @param EntityExplodeEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityExplode(EntityExplodeEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getEntity()->getLevel())->isAllowed(Shield::EXPLODING)){
            $event->setCancelled(true);
        }
    }
    /** 
     * @param EntityInventoryChangeEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityInventoryChange(EntityInventoryChangeEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if($this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::INVENTORY_CHANGING)){
                $event->setCancelled(true);
                $event->getPlayer()->sendTip($this->plugin->getConfig()->get("inventoryChangeNotAllowed"));
            }
        }
    }
    /** 
     * @param EntityLevelChangeEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityLevelChange(EntityLevelChangeEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if($this->plugin->getStorage()->getShield($player->getLevel())->isPlayerBanned($player)){
                $event->setCancelled(true);
                $event->getPlayer()->sendTip($this->plugin->getConfig()->get("levelChangeNotAllowed"));
            }
        }
    }
    /** 
     * @param EntityRegainHealthEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityRegainHealth(EntityRegainHealthEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if($this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::HEALING)){
                $event->setCancelled(true);
            }
        }
    }
    /** 
     * @param EntityTeleportEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onEntityTeleport(EntityTeleportEvent $event){
        if(($player = $event->getEntity()) instanceof Player){
            if(!$this->plugin->getStorage()->getShield($player->getLevel())->isAllowed(Shield::TELEPORTING)){
                $event->setCancelled(true);
                $player->sendTip($this->plugin->getConfig()->get("teleportNotAllowed"));
            }
        }
    }
    /** 
     * @param PlayerBucketEmptyEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerBucketEmpty(PlayerBucketEmptyEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::EMPTYING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("emptyNotAllowed"));
        }
    }
    /**
     * @param PlayerBucketFillEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerBucketFill(PlayerBucketFillEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::FILLING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("fillNotAllowed"));
        }
    }
    /**
     * @param PlayerChatEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerChat(PlayerChatEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::CHATTING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("chatNotAllowed"));
        }
    }
    /** 
     * @param PlayerDropItemEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerDropItem(PlayerDropItemEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::DROPPING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("dropNotAllowed"));
        }
    }
    /** 
     * @param PlayerGameModeChangeEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerGameModeChange(PlayerGameModeChangeEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::GAMEMODE_CHANGING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("gamemodeChangeNotAllowed"));
        }
    }
    /** 
     * @param PlayerInteractEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerInteract(PlayerInteractEvent $event){
        if(!$this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::INTERACTING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("interactionNotAllowed"));
        }
    }
    /**
     * @param PlayerItemConsumeEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerItemConsume(PlayerItemConsumeEvent $event){
        if($this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isAllowed(Shield::EATING)){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("eatNotAllowed"));
        }
    }
    /**
     * @param PlayerItemHeldEvent $event 
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerItemHeld(PlayerItemHeldEvent $event){
        if($this->plugin->getStorage()->getShield($event->getPlayer()->getLevel())->isItemBanned($event->getItem())){
            $event->setCancelled(true);
            $event->getPlayer()->sendTip($this->plugin->getConfig()->get("itemNotAllowed"));
        }
    }
}
