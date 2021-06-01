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
    }
    
    public function getIslandSize(Player $player) {
        $islandSize = $this->owningPlugin->getIslandSize($player->getName());

        if $islandSize === null {
            $islandSize = "§7N/A";
        }

        return $islandSize;

    }

    public function isIslandLocked(Player $player) {
        $islandLock = $this->owningPlugin->isIslandLocked($player->getName());

        if $islandLock === null {
            $islandLock = "§7N/A";
        } elseif $islandLock == true {
            $islandLock = "Yes";
        }else{
            $islandLock = "No";
        }

        return $islandLock;
    }

    public function getIslandValue(Player $player) {
        $islandValue = $this->owningPlugin->getIslandValue($player->getName());

        if $islandValue === null {
            $islandValue = "§7N/A";
        }

        return $islandValue;

    }

    public function getIslandRank(Player $player): int {
        $islandRank = $this->owningPlugin->getIslandRank($player->getName());

        if $islandRank === null {
            $islandRank = "§7N/A";
        }

        return $islandRank;
    }
    
}