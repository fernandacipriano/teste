<?php

require dirname(__DIR__) . '/vendor/autoload.php';

use Cipriano\DB\Dbmaker;

$banco = Dbmaker::instanciar();
$query = "SELECT * FROM NOTES ";
$result = $banco->exec($query);

$retorno = [];


foreach ($result as $key) {
  array_push($retorno, [
    'id' => $key->id,
    'nome' => $key->name,
    'descricao' => $key->description
  ]);
}

echo json_encode($retorno);


// echo "<pre>";
// print_r($result);
// echo "</pre>";

// use App\Controller\MyController;
//
// $controller = new MyController();
//
// try {
//   $html = $controller->view();
//   echo $html;
// } catch (Throwable $error) {
//   echo 'Error: ' . $error->getMessage(), PHP_EOL;
// }


// require __DIR__ . '/vendor/autoload.php';
