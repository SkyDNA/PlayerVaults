<?php

namespace PlayerVaults\Vault;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Chest;

class Vault extends Chest{

    public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
        $this->inventory = new VaultInventory($this);
        $this->replacement = [$this->getBlock()->getId(), $this->getBlock()->getDamage()];
    }

    private function getReplacement() : Block{
        return Block::get(...$this->replacement);
    }

    public function sendReplacement(Player $player){
        $block = $this->getReplacement();
        $block->x = (int) $this->x;
        $block->y = (int) $this->y;
        $block->z = (int) $this->z;
        $block->level = $this->getLevel();
        if($block->level !== null){
            $block->level->sendBlocks([$player], [$block]);
        }
    }

    public function addAdditionalSpawnData(CompoundTag $nbt) : void{
    }
}
