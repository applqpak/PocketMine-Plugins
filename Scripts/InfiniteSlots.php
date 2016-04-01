<?php
/**
 * @name InfiniteSlots
 * @main infiniteslots\InfiniteSlots
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description Bypass the player slot limit on your server!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/InfiniteSlots.php
 */
namespace infiniteslots{
    use pocketmine\event\player\PlayerKickEvent;
    use pocketmine\event\Listener;
    use pocketmine\plugin\PluginBase;
    class InfiniteSlots extends PluginBase implements Listener{
        public function onEnable(){
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @param PlayerKickEvent $event
         * @priority HIGHEST
         * @ignoreCancelled true
         */
        public function onPlayerKick(PlayerKickEvent $event){
            if($event->getReason() === "disconnectionScreen.serverFull"){
                $event->setCancelled(true);
            }
        }
    }
}