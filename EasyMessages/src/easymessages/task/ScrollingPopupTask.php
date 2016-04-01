<?php

namespace easymessages\task;

use easymessages\utils\Utils;
use easymessages\EasyMessages;
use pocketmine\scheduler\PluginTask;

class ScrollingPopupTask extends PluginTask{
    /** @var EasyMessages */
    private $plugin;
    /**
     * @param EasyMessages $plugin
     */
    public function __construct(EasyMessages $plugin){
        parent::__construct($plugin);
        $this->plugin = $plugin;
    }
    /**
     * @param int $currentTick
     */
    public function onRun($currentTick){
        $popup = $this->plugin->getScrollingPopup();
        $this->plugin->broadcastPopup($popup);
        $this->plugin->setScrollingPopup(Utils::next($popup));
    }
}
