<?php

namespace imanager\event;

use imanager\iManager;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\Listener;

class iManagerListener implements Listener{
    /** @var iManager */
    private $plugin;
    /**
     * @param iManager $plugin
     */
    public function __construct(iManager $plugin){
        $this->plugin = $plugin;
    }
    /**
     * @param PlayerMoveEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerMove(PlayerMoveEvent $event){
        
    }
    /**
     * @param PlayerPreLoginEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onPlayerPreLogin(PlayerPreLoginEvent $event){
        if(!$this->plugin->isAddressWhitelisted($event->getPlayer()->getAddress()) and $this->plugin->getConfig()->getNested("plugin.ipWhitelist")){
            $event->setCancelled(true);
        }
    }
}
