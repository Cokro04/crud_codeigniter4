<?php

namespace App\Controllers;

class Pages extends BaseController
{
  public function index()
  {
    $data = [
      'title' => 'Home | BelajarWeb'
    ];

    return view('pages/home', $data);
  }

  public function about()
  {
    $data = [
      'title' => 'About | BelajarWeb'
    ];

    return view('pages/about', $data);
  }

  public function contact()
  {
    $data = [
      'title' => 'Contact | BelajarWeb',
      'alamat' => [
        [
          'type' => 'Rumah',
          'alamat' => 'Kp Naringgul RT/RW 000/000',
          'kota' => 'Bandung'
        ],
        [
          'type' => 'Kantor',
          'alamat' => 'Kp Tipar RT/RW',
          'kota' => 'Bandung'
        ]
      ]
    ];

    return view('pages/contact', $data);
  }

  //--------------------------------------------------------------------

}
