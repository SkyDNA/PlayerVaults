<?php

namespace PlayerVaults\Vault;

use PlayerVaults\PlayerVaults;

use pocketmine\inventory\{ChestInventory, InventoryType};
use pocketmine\Player;

class VaultInventory extends ChestInventory{

    public function onClose(Player $who): void{
        if(isset($this->getHolder()->namedtag->Vault)){
            PlayerVaults::getInstance()->getData()->saveContents($this->getHolder(), $this->getContents());
        }
        $this->holder->sendReplacement($who);
        $this->holder->close();
    }
}
