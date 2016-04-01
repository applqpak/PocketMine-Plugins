<?php

namespace queryfacade\event\server;

use pocketmine\event\server\ServerEvent;
use queryfacade\network\DummyPlayer;

class RemovePlayerEvent extends ServerEvent{
    /** @var \pocketmine\event\HandlerList */
    public static $handlerList = null;
    /** @var DummyPlayer */
    private $player;
    /**
     * @param DummyPlayer $player
     */
    public function __construct(DummyPlayer $player){
        $this->player = $player;
    }
    /**
     * @return DummyPlayer
     */
    public function getPlayer(){
        return $this->player;
    }
}