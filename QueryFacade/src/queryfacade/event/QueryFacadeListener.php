<?php

namespace queryfacade\event;

use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\event\Listener;
use queryfacade\QueryFacade;

class QueryFacadeListener implements Listener{
    /** @var QueryFacade */
    private $plugin;
    /**
     * @param QueryFacade $plugin
     */
    public function __construct(QueryFacade $plugin){
        $this->plugin = $plugin;
    }
    /** 
     * @param QueryRegenerateEvent $event 
     * @priority HIGHEST
     */
    public function onQueryRegenerate(QueryRegenerateEvent $event){
        if($this->plugin->isApplicable(QueryFacade::PLUGINS)){
            $event->setPlugins($this->plugin->getModifier()->getPlugins());
        }
        if($this->plugin->isApplicable(QueryFacade::PLAYERS)){
            $event->setPlayerList($this->plugin->getModifier()->getPlayers());
        }
        if($this->plugin->isApplicable(QueryFacade::COUNT)){
            $event->setPlayerCount($this->plugin->getModifier()->getPlayerCount());
        }
        if($this->plugin->isApplicable(QueryFacade::MAX_COUNT)){
            $event->setMaxPlayerCount($this->plugin->getModifier()->getMaxPlayerCount());
        }
        if($this->plugin->isApplicable(QueryFacade::MAP)){
            $event->setWorld($this->plugin->getModifier()->getWorld());
        }
        //var_dump($event);
        //var_dump($event->getLongQuery());
        //var_dump($event->getShortQuery());
    }
}
