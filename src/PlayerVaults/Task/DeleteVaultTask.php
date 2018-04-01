<?php

namespace PlayerVaults\Task;

use PlayerVaults\Provider;

use pocketmine\scheduler\AsyncTask;

class DeleteVaultTask extends AsyncTask{

    private $player;
    private $type;
    private $data;
    private $what;

    public function __construct(string $player, int $type, int $what, $data){
        $this->player = (string) $player;
        if($type === Provider::MYSQL){
            $this->data = (array) $data;
        }else{
            $this->data = (string) $data;
        }
        $this->what = (int) $what;
    }

    public function onRun(){
        switch($this->type){
            case Provider::YAML:
                if(is_file($path = $this->data.$this->player.".yml")){
                    if($this->what === -1){
                        unlink($path);
                    }else{
                        $data = yaml_parse_file($path);
                        unset($data[$this->what]);
                        yaml_emit_file($path, $data);
                    }
                }
                break;
            case Provider::JSON:
                if(is_file($path = $this->data.$this->player.".json")){
                    if($this->what === -1){
                        unlink($path);
                    }else{
                        $data = json_decode(file_get_contents($path), true);
                        unset($data[$this->what]);
                        file_put_contents($path, json_encode($data, JSON_PRETTY_PRINT));
                    }
                }
                break;
            case Provider::MYSQL:
                $data = new \mysqli(...$this->data);
                if($this->what === -1){
                    $stmt = $data->prepare("DELETE FROM vaults WHERE player=?");
                    $stmt->bind_param("s", $this->player);
                    $stmt->execute();
                    $stmt->close();
                }else{
                    $stmt = $data->prepare("DELETE FROM vaults WHERE player=? AND number=?");
                    $stmt->bind_param("si", $this->player, $this->number);
                    $stmt->execute();
                    $stmt->close();
                }
                $data->close();
                break;
        }
    }
}
