<?php
declare(strict_types = 1);

namespace TheCoolWizard\RedSkyBlockScore\listeners;

use TheCoolWizard\RedSkyBlockScore\Main;
use Ifera\ScoreHud\event\PlayerTagUpdateEvent;
use Ifera\ScoreHud\scoreboard\ScoreTag;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use function strval;

class EventListener implements Listener{

	/** @var Main */
	private $plugin;

	public function __construct(Main $plugin){
		$this->plugin = $plugin;
	}

	public function onBlockPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
        $block = $event->getBlock();

        $valuableArray = $this->plugin->owningPlugin->cfg->get("Valuable Blocks", []);
        
        if (!onMasterWorld($player)) return;
        if (!array_key_exists(strval($block->getID()), $valuableArray)) return;

        (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandvalue", strval($this->plugin->getIslandValue($player)))))->call();
	}

    public function onBlockBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $block = $event->getBlock();

        $valuableArray = $this->plugin->owningPlugin->cfg->get("Valuable Blocks", []);
        
        if (!$this->onMasterWorld($player)) return;
        if (!array_key_exists(strval($block->getID()), $valuableArray)) return;

        (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandvalue", strval($this->plugin->getIslandValue($player)))))->call();
    }

    public function onEntityLevelChange(EntityLevelChangeEvent $event) {
        $entity = $event->getEntity();

        if (!$entity instanceof Player) return;
        if (!$this->onMasterWorld($entity)) return;

        (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandlock", strval($this->plugin->isIslandLocked($player)))))->call();
        (new PlayerTagUpdateEvent($player, new ScoreTag("redskyblockscore.islandrank", strval($this->plugin->getIslandRank($player)))))->call();
    }

    private function onMasterWorld($player) {
        $skyblock_cfg = $this->plugin->owningPlugin->skyblock;
        if ($player->getLevel()->getFolderName() === $skyblock_cfg->get("Master World") || $player->getLevel()->getFolderName() === $skyblock_cfg->get("Master World") . "-Nether") {
            return true;
        }else{
            return false;
        }
    }
}