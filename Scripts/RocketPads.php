<?php
/**
 * @name RocketPads
 * @main rocketpads\RocketPads
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description Save your players the walking, give them a boost!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/RocketPads.php
 */
namespace rocketpads{
    use pocketmine\block\Block;
    use pocketmine\event\player\PlayerMoveEvent;
    use pocketmine\event\Listener;
    use pocketmine\plugin\PluginBase;
    use pocketmine\utils\Config;
    class RocketPads extends PluginBase implements Listener{
        public function onEnable(){
            @mkdir($this->getDataFolder());
            $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "baseValue" => 0.6,
                "launchDistance" => 10,
                "blocks" => ["133:0", "152:0"]
            ]);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @param Block $block
         * @return bool
         */
        public function isRocketPad(Block $block){
            if(is_array($blocks = $this->getConfig()->get("blocks"))){
                return in_array($block->getId().":".$block->getDamage(), $blocks);
            }
        }
        /** 
         * @return int|float 
         */
        public function getBaseValue(){
            return $this->getConfig()->get("baseValue");
        }
        /** 
         * @return int|float 
         */
        public function getLaunchDistance(){
            return $this->getConfig()->get("launchDistance");
        }
        /** 
         * @param Player $player 
         */
        public function launchPlayer(Player $player){
            switch($player->getDirection()){
                case 0: //south
                    $player->knockBack($player, 0, $this->getLaunchDistance(), 0, $this->getBaseValue());
                    break;
                case 1: //west
                    $player->knockBack($player, 0, 0, $this->getLaunchDistance(), $this->getBaseValue());
                    break;
                case 2: //north
                    $player->knockBack($player, 0, -$this->getLaunchDistance(), 0, $this->getBaseValue());
                    break;
                case 3: //east
                    $player->knockBack($player, 0, 0, -$this->getLaunchDistance(), $this->getBaseValue());
                    break;
            }
        }
        /**
         * @param PlayerMoveEvent $event
         * @priority MONITOR
         * @ignoreCancelled true
         */
        public function onPlayerMove(PlayerMoveEvent $event){
            if($this->isRocketPad($event->getPlayer()->getLevel()->getBlock($event->getPlayer()->floor()->subtract(0, 1)))){
                $this->launchPlayer($event->getPlayer());
            }
        }
    }
}
