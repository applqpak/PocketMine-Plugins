<?php
/**
 * @name IdleKick
 * @main idlekick\IdleKick
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description Prevent inactive players from taking up player slots on your server!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/IdleKick.php
 */
namespace idlekick{
    use pocketmine\event\player\PlayerChatEvent;
    use pocketmine\event\player\PlayerJoinEvent;
    use pocketmine\event\player\PlayerMoveEvent;
    use pocketmine\event\player\PlayerQuitEvent;
    use pocketmine\event\Listener;
    use pocketmine\plugin\PluginBase; 
    use pocketmine\scheduler\PluginTask;
    use pocketmine\utils\Config;
    use pocketmine\utils\TextFormat;
    use pocketmine\Player;
    class IdleKick extends PluginBase implements Listener{
        /** @var array */
        private $lastMoved = [];
        public function onEnable(){
            @mkdir($this->getDataFolder());
            $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "logoutMessage" => TextFormat::RED."You have been kicked due to inactivity.",
                "timeout" => 10
            ]);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
            $this->getServer()->getScheduler()->scheduleRepeatingTask(new IdleKickTask($this), 20);
        }
        /**
         * @param Player $player
         */
        public function setLastActionTime(Player $player){
            $this->lastMoved[$player->getName()] = time();
        }
        /**
         * @param Player $player
         * @return int
         */
        public function getLastActionTime(Player $player){
            if(isset($this->lastMoved[$player->getName()])){
                return $this->lastMoved[$player->getName()];
            }
        }
        /**
         * @param Player $player
         */
        public function removeLastActionTime(Player $player){
            if(isset($this->lastMoved[$player->getName()])){
                unset($this->lastMoved[$player->getName()]);
            }
        }
        /**
         * @param Player $player
         * @return bool
         */
        public function isInactive(Player $player){
            return (time() - $this->getLastActionTime($player)) > ($this->getConfig()->get("timeout") * 60);
        }
        /**
         * @param PlayerChatEvent $event
         * @priority MONITOR
         * @ignoreCancelled true
         */
        public function onPlayerChat(PlayerChatEvent $event){
            $this->setLastActionTime($event->getPlayer());
        }
        /**
         * @param PlayerJoinEvent $event
         * @priority MONITOR
         */
        public function onPlayerJoin(PlayerJoinEvent $event){
            $this->setLastActionTime($event->getPlayer());
        }
        /**
         * @param PlayerMoveEvent $event
         * @priority MONITOR
         * @ignoreCancelled true
         */
        public function onPlayerMove(PlayerMoveEvent $event){
            $this->setLastActionTime($event->getPlayer());
        }
        /**
         * @param PlayerQuitEvent $event
         * @priority MONITOR
         */
        public function onPlayerQuit(PlayerQuitEvent $event){
            $this->removeLastActionTime($event->getPlayer());
        }
    }
    class IdleKickTask extends PluginTask{
        /** @var IdleKick */
        private $plugin;
        /**
         * @param IdleKick $plugin
         */
        public function __construct(IdleKick $plugin){
            parent::__construct($plugin);
            $this->plugin = $plugin;
        }
        /**
         * @param int $currentTick
         */
        public function onRun($currentTick){
            foreach($this->plugin->getServer()->getOnlinePlayers() as $player){
                if($this->plugin->isInactive($player)){
                    $player->kick($this->plugin->getConfig()->get("logoutMessage"), false);
                }
            }
        }
    }
}
