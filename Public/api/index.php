<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once("../../vendor/autoload.php");
use App\Controller\GameController;

$controller = null;
$id         = null; //Param
$method     = $_SERVER["REQUEST_METHOD"]; //POST, PUT, DELETE and GET
$uri = $_SERVER["REQUEST_URI"];
$data       = null;
parse_str(file_get_contents('php://input'), $data);

$unsetCount = 3;
//TRATA A URI
$ex = explode("/", $uri);
for($i = 0; $i < $unsetCount; $i++){
  unset($ex[$i]);
}

$ex = array_filter(array_values($ex));
if(isset($ex[0])){
  $controller = $ex[0];
}

if(isset($ex[1])){
  $id = $ex[1];
}

//FIM TRATA A URI
$gameController = new GameController();

switch($method) {
  case 'GET':
  if($controller != null && $id == null){
    echo $gameController->readAll();
  }elseif($controller != null && $id != null){
    echo $gameController->readById($id);
  }else{
    echo json_encode(["result" => "invalid"]);
  }
  break;

  case 'POST':
  if($controller != null && $id == null){
    echo $gameController->create($data);
  }else{
    echo json_encode(["result" => "invalid"]);
  }
  break;

  case 'PUT':
  if($controller != null && $id != null){
    echo $gameController->update($id, $data);
  }else{
    echo json_encode(["result" => "invalid"]);
  }
  break;

  case 'DELETE':
    if($controller != null && $id != null){
      echo $gameController->delete($id);
    }else{
      echo json_encode(["result" => "invalid"]);
    }
  break;

  default:
    echo json_encode(["result" => "invalid resquest"]);
  break;
}

?>
