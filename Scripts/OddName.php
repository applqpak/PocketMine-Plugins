<?php
/**
 * @name OddName
 * @main oddname\OddName
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description Give your players odd names when they join!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/OddName.php
 */
namespace oddname{
    use pocketmine\event\server\DataPacketReceiveEvent;
    use pocketmine\event\Listener;
    use pocketmine\network\protocol\Info;
    use pocketmine\plugin\PluginBase;
    class OddName extends PluginBase implements Listener{
        public function onEnable(){
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @return string
         */
        public function generateName(){
            $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
            $out = "";
            for($i = 0; $i < 16; $i++){
                $out .= $chars[mt_rand(0, strlen($chars) - 1)];
            }
            return $out; //There's always a chance that it'll generate a name already in use, but this will rarely happen
        }
        /**
         * @param DataPacketReceiveEvent $event
         * @priority HIGHEST
         * @ignoreCancelled true
         */
        public function onDataPacketReceive(DataPacketReceiveEvent $event){
            $packet = $event->getPacket();
            if($packet->pid() === Info::LOGIN_PACKET){
                $packet->username = $this->generateName();
            }
        }
    }
}