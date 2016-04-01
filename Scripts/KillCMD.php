<?php
/**
 * @name KillCMD
 * @main killcmd\KillCMD
 * @version 1.0.0
 * @api 1.12.0
 * @load POSTWORLD
 * @author Gamecrafter
 * @description Do stuff when a player kills another player!
 * @link https://github.com/NebulaLabs/Script-Plugins-PM/blob/master/KillCMD.php
 */
namespace killcmd{
    use pocketmine\event\entity\EntityDamageByEntityEvent;
    use pocketmine\event\player\PlayerDeathEvent;
    use pocketmine\event\Listener;
    use pocketmine\plugin\PluginBase;
    use pocketmine\utils\Config;
    use pocketmine\Player;
    class KillCMD extends PluginBase implements Listener{
        public function onEnable(){
            @mkdir($this->getDataFolder());
            $this->config = new Config($this->getDataFolder()."config.yml", Config::YAML, [
                "commands" => [
                    "give {PLAYER_KILLER} 264 1",
                    "say {PLAYER_KILLER} killed {PLAYER_VICTIM}!",
                    "tell {PLAYER_KILLER} Good job, keep it up!"
                ]
            ]);
            $this->getServer()->getPluginManager()->registerEvents($this, $this);
        }
        /**
         * @param PlayerDeathEvent $event
         * @priority HIGHEST
         */
        public function onPlayerDeath(PlayerDeathEvent $event){
            if(($entity = $event->getEntity()) instanceof Player and ($cause = $event->getEntity()->getLastDamageCause()) instanceof EntityDamageByEntityEvent){
                if(($damager = $cause->getDamager()) instanceof Player){
                    foreach($this->getConfig()->get("commands") as $command){
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), str_replace(
                            ["{PLAYER_KILLER}", "{PLAYER_VICTIM}"],
                            [$damager->getName(), $entity->getName()],
                            $command
                        ));
                    }
                }
            }
        }
    }
}