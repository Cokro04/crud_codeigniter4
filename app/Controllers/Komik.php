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

    if (empty($data['komik'])) {
      throw new \CodeIgniter\Exceptions\PageNotFoundException('judul komik' . $slug . 'tidak ditemukan');
    }

    return view('komik/detail', $data);
  }

  public function create()
  {
    session();
    $data = [
      'title' => 'form | add data komik',
      'validation' => \Config\Services::validation()
    ];

    return view('komik/create', $data);
  }

  public function save()
  {
    // validation input

    if (!$this->validate([
      // 'judul'     => 'required|is_unique[komik.judul]',
      'judul' => [
        'rules' => 'required|is_unique[komik.judul]',
        'errors' => [
          'required' => '{field} Komik Harus diisi',
          'is_unique' => '{field}  Komik Sudah Ada'
        ]
      ]
    ])) {

      $validation = \Config\Services::validation();

      return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
    }

    $slug = url_title($this->request->getVar('judul'), '-', true);
    $this->KomikModel->save([
      'judul' => $this->request->getVar('judul'),
      'slug' => $slug,
      'penulis' => $this->request->getVar('penulis'),
      'penerbit' => $this->request->getVar('penerbit'),
      'sampul' => $this->request->getVar('sampul')
    ]);

    session()->setFlashdata('pesan', 'Data Berhasil Ditambahakan');

    return redirect()->to('/komik');
  }

  //--------------------------------------------------------------------

  // cara konek db tanpa model

  // $db = \Config\DataBase::connect();
  // $komik = $db->query("SELECT * FROM komik");
  // foreach ($komik->getResultArray() as $row) {
  //   d($row);
  // }
}
