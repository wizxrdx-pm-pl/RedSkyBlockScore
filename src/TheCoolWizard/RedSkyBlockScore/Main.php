<?php

declare(strict_types=1);

namespace TheCoolWizard\RedSkyBlockScore;

use RedSkyBlock\RedCraftPE\RedSkyBlock;
use TheCoolWizard\RedSkyBlockScore\listeners\TagResolveListener;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use function strval;

class Main extends pluginBase {

    private $owningPlugin;

    public function onEnable() : void {
        $this->saveDefaultConfig();
        $this->owningPlugin = $this->getServer()->getPluginManager()->getPlugin("RedSkyBlock");

        $this->getServer()->getPluginManager()->registerEvents(new TagResolveListener($this), $this);

        $this->getScheduler()->scheduleRepeatingTask(new ClosureTask(function(int $_): void{
			foreach($this->getServer()->getOnlinePlayers() as $player){
				if(!$player->isOnline()){
					continue;
				}

				(new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandsize", strval($this->getIslandSize($player)))))->call();
				(new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandlock", strval($this->isIslandLocked($player)))))->call();
                (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandvalue", strval($this->getIslandValue($player)))))->call();
                (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandrank", strval($this->getIslandRank($player)))))->call();
			}
		}), 20);
	}
    
    public function getIslandSize(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $this->owningPlugin->getIslandSize($player);
    }

    public function isIslandLocked(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $this->owningPlugin->isIslandLocked($player)? "Yes" : "No";
    }

    public function getIslandValue(Player $player) {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $islandValue = $this->owningPlugin->getIslandValue($player);
    }
    
    public function getIslandRank(Player $player): int {
        if ($this->isIslandExists($player) === null) return "§7N/A";

        return $islandValue = $this->owningPlugin->getIslandRank($player);
    }
    
    private function isIslandExists(Player $player) {
        $skyBlockArray = $this->owningPugin->skyblock->get("SkyBlock", []);

        if (array_key_exists($player->getName(), $skyBlockArray)) {
            return null;
        }
    }
}