<?php

namespace App\Controllers;

use \App\Models\KomikModel;

class Komik extends BaseController
{
  protected $KomikModel;
  public function __construct()
  {
    $this->KomikModel = new KomikModel();
  }
  public function index()
  {
    $komik = $this->KomikModel->getKomik();
    $data = [
      'title' => 'Komik | BelajarWeb',
      'komik' => $komik
    ];

    return view('komik/index', $data);
  }

  public function detail($slug)
  {
    $data = [
      'title' => 'Detail | Komik',
      'komik' => $this->KomikModel->getKomik($slug)
    ];

    return view('komik/detail', $data);
  }

  //--------------------------------------------------------------------

  // cara konek db tanpa model

  // $db = \Config\DataBase::connect();
  // $komik = $db->query("SELECT * FROM komik");
  // foreach ($komik->getResultArray() as $row) {
  //   d($row);
  // }
}
