<?php

namespace App\Controller;

use App\Model\MyModel;

class MyController
{
  public function view()
  {
    $model = new MyModel();

    $data = $model->read();

    return compile(APP_ROOT . '/resources/view/index.phtml', ['data' => $data]);
    // require APP_ROOT . '/resources/view/index.phtml';

  }
}
