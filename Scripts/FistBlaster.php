<?php
/**
 * @name FistBlaster
 * @main fistblaster\FistBlaster
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description TNTs are so retro!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/FistBlaster.php
 */
namespace fistblaster{   
    use pocketmine\command\Command;
    use pocketmine\command\CommandExecutor;
    use pocketmine\command\CommandSender;
    use pocketmine\command\PluginCommand;
    use pocketmine\event\entity\EntityDamageEvent;
    use pocketmine\event\entity\EntityDamageByEntityEvent;
    use pocketmine\event\player\PlayerInteractEvent;
    use pocketmine\event\player\PlayerQuitEvent;
    use pocketmine\event\Listener;
    use pocketmine\level\Explosion;
    use pocketmine\level\Position;
    use pocketmine\plugin\PluginBase;
    use pocketmine\utils\Config;
    use pocketmine\utils\TextFormat;
    use pocketmine\Player;
    class FistBlaster extends PluginBase implements CommandExecutor, Listener{
        /** @var \SplObjectStorage */
        private $players;
        public function onEnable(){
            @mkdir($this->getDataFolder());
            $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "blockBreaking" => false,
                "size" => 10
            ]);
            $this->players = new \SplObjectStorage();
            $command = new PluginCommand("fistblaster", $this);
            $command->setAliases(["fb"]);
            $command->setExecutor($this);
            $this->getServer()->getCommandMap()->register("fistblaster", $command);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @param Position $position
         */
        public function explode(Position $position){
            $explosion = new Explosion($position, (int) $this->getConfig()->get("size"));
            if($this->getConfig()->get("blockBreaking")){
                $explosion->explode();
            }
            else{
                $explosion->explodeB();
            }
        }
        /**
         * @param Player $player
         * @param bool $value
         */
        public function setActive(Player $player, $value){
            if($value){
                $this->players->attach($player);
            }
            else{
                if($this->isActive($player)){
                    $this->players->detach($player);
                }
            }
        }
        /**
         * @param Player $player
         * @return bool
         */
        public function isActive(Player $player){
            return $this->players->contains($player);
        }
        /**
         * @param CommandSender $sender
         * @param Command $command
         * @param string $label
         * @param string[] $args
         * @return bool
         */
        public function onCommand(CommandSender $sender, Command $command, $label, array $args){
            if($sender instanceof Player){
                if($sender->isOp()){
                    if($this->isActive($sender)){
                        $this->setActive($sender, false);
                        $sender->sendMessage(TextFormat::RED."Blaster disabled! You will no longer blow things up on interaction.");
                    }
                    else{
                        $this->setActive($sender, true);
                        $sender->sendMessage(TextFormat::GREEN."Blaster enabled! Touch something to blow it up!");
                    }
                }
                else{
                    $sender->sendMessage(TextFormat::RED."You don't have permissions to use this command.");
                }
            }
            else{
                $sender->sendMessage(TextFormat::RED."Please run this command in-game.");
            }
            return true;
        }
        /**
         * @param EntityDamageEvent $event
         * @priority LOWEST
         */
        public function onEntityDamage(EntityDamageEvent $event){
            if($event instanceof EntityDamageByEntityEvent){
                if($event->getDamager() instanceof Player){
                    $this->explode($event->getEntity());
                }
            }
        }
        /**
         * @param PlayerInteractEvent $event
         * @priority LOWEST
         */
        public function onPlayerInteract(PlayerInteractEvent $event){
            if($this->isActive($event->getPlayer())){
                $this->explode($event->getBlock());
            }
        }
        /**
         * @param PlayerQuitEvent $event
         * @priority MONITOR
         */
        public function onPlayerQuit(PlayerQuitEvent $event){
            if($this->isActive($event->getPlayer())){
                $this->setActive($event->getPlayer(), false);
            }
        }
    }
}