<?php

namespace App\Controllers;

class Komik extends BaseController
{
  public function index()
  {
    $data = [
      'title' => 'Komik | BelajarWeb'
    ];

    return view('komik/index', $data);
  }

  //--------------------------------------------------------------------

}
