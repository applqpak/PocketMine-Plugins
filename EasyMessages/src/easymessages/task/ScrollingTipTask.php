<?php

namespace easymessages\task;

use easymessages\utils\Utils;
use easymessages\EasyMessages;
use pocketmine\scheduler\PluginTask;

class ScrollingTipTask extends PluginTask{
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
        $tip = $this->plugin->getScrollingTip();
        $this->plugin->broadcastTip($tip);
        $this->plugin->setScrollingTip(Utils::next($tip));
    }
}
