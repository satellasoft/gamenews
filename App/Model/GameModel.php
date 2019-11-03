<?php
namespace App\Model;
use App\Entity\Game;
use App\Util\Serialize;

class GameModel{
  private $fileName;
  private $listGame = []; //Object type Game

  public function __construct(){
    $this->fileName = "../database/carro.db";
    $this->load();
  }

  public function readAll(){
    return (new Serialize())->serialize($this->listGame);
  }

  public function readById($id){

    foreach($this->listGame as $g){
      if($g->getId() == $id)
      return (new Serialize())->serialize($g);
    }

    return json_encode([]);
  }

  public function create(Game $game){
    $game->setId($this->getLastId());

    $this->listGame[] = $game;
    $this->save();

    return "ok";
  }

  public function update(Game $game){
    $result = "not found";

    for($i = 0; $i < count($this->listGame); $i++){
      if($this->listGame[$i]->getId() == $game->getId()){
        $this->listGame[$i] = $game;
        $result = "ok";
      }
    }

    $this->save();

    return $result;
  }

  public function delete($id){
    $result = "not found";
    for($i = 0; $i < count($this->listGame); $i++){
      if($this->listGame[$i]->getId() == $id){
        unset($this->listGame[$i]);
        $result = "ok";
      }
    }

    $this->listGame = array_filter(array_values($this->listGame));

    $this->save();
    return $result;
  }
  //Internal Method
  private function save(){
    $temp = [];

    foreach($this->listGame as $g){
      $temp[]       = [
        "id"        => $g->getId(),
        "titulo"    => $g->getTitulo(),
        "descricao" => $g->getDescricao(),
        "videoid"   => $g->getVideoid()
      ];

      $fp = fopen($this->fileName, "w");
      fwrite($fp, json_encode($temp));
      fclose($fp);
    }
  }

  private function getLastId(){
    $lastId = 0;

    foreach($this->listGame as $g){
      if($g->getId() > $lastId)
      $lastId = $g->getId();
    }

    return ($lastId + 1);
  }

  private function load(){
    if(!file_exists($this->fileName) || filesize($this->fileName) <= 0)
    return [];

    $fp = fopen($this->fileName, "r");
    $str = fread($fp, filesize($this->fileName));
    fclose($fp);

    $arrayGame = json_decode($str);

    foreach($arrayGame as $g){
      $this->listGame[] = new Game(
        $g->id,
        $g->titulo,
        $g->descricao,
        $g->videoid
      );
    }
  }
}
