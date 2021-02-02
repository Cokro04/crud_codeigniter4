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
      ],
      'sampul' => [
        'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
        'errors' => [
          'max_size' => 'Ukuran Gambar Terlalu Besar',
          'is_image' => 'yang anda pilih bukan gambar',
          'mime_in' => 'yang anda pilih bukan gambar'
        ]
      ]
    ])) {

      // $validation = \Config\Services::validation();
      // return redirect()->to('/komik/create')->withInput()->with('validation', $validation);
      return redirect()->to('/komik/create')->withInput();
    }
    // ambil gambar 
    $fileGambar = $this->request->getFile('sampul');

    if ($fileGambar->getError() == 4) {
      $namaRandom = 'default.jpg';
    } else {
      $namaRandom = $fileGambar->getRandomName();
      $fileGambar->move('img', $namaRandom); //memindah file ke direktori img 
      // $namaGambar = $fileGambar->getName(); //mengambil nama file
    }

    $slug = url_title($this->request->getVar('judul'), '-', true);
    $this->KomikModel->save([
      'judul' => $this->request->getVar('judul'),
      'slug' => $slug,
      'penulis' => $this->request->getVar('penulis'),
      'penerbit' => $this->request->getVar('penerbit'),
      'sampul' => $namaRandom
    ]);

    session()->setFlashdata('pesan', 'Data Berhasil Ditambahakan');

    return redirect()->to('/komik');
  }

  public function delete($id)
  {
    $komik = $this->KomikModel->find($id);
    if ($komik['sampul'] != 'default.jpg') {
      unlink('img/' . $komik['sampul']);
    }
    $this->KomikModel->delete($id);
    session()->setFlashdata('pesan', 'Data Berhasil Dihapus');
    return redirect()->to('/komik');
  }

  public function edit($slug)
  {
    session();
    $data = [
      'title' => 'form | Edit data komik',
      'validation' => \Config\Services::validation(),
      'komik' => $this->KomikModel->getKomik($slug)
    ];

    return view('komik/edit', $data);
  }

  public function update($id)
  {
    $KomikLama = $this->KomikModel->getKomik($this->request->getVar('slug'));
    if ($KomikLama['judul'] == $this->request->getVar('judul')) {
      $rule_judul = 'required';
    } else {
      $rule_judul = 'required|is_unique[komik.judul]';
    }


    if (!$this->validate([
      // 'judul'     => 'required|is_unique[komik.judul]',
      'judul' => [
        'rules' => $rule_judul,
        'errors' => [
          'required' => '{field} Komik Harus diisi',
          'is_unique' => '{field}  Komik Sudah Ada'
        ]
      ],
      'sampul' => [
        'rules' => 'max_size[sampul,1024]|is_image[sampul]|mime_in[sampul,image/jpg,image/jpeg,image/png]',
        'errors' => [
          'max_size' => 'Ukuran Gambar Terlalu Besar',
          'is_image' => 'yang anda pilih bukan gambar',
          'mime_in' => 'yang anda pilih bukan gambar'
        ]
      ]
    ])) {

      return redirect()->to('/komik/edit/' . $this->request->getVar('slug'))->withInput();
    }

    $fileSampul = $this->request->getFile('sampul');
    $fileLama = $this->request->getVar('sampulLama');

    if ($fileSampul->getError() == 4) {
      $namaSampul =  $fileLama;
    } else {
      $namaSampul = $fileSampul->getRandomName();
      $fileSampul->move('img', $namaSampul);
      if ($fileLama != 'default.jpg') {
        unlink('img/' . $fileLama);
      }
    }
    $slug = url_title($this->request->getVar('judul'), '-', true);
    $this->KomikModel->save([
      'id' => $id,
      'judul' => $this->request->getVar('judul'),
      'slug' => $slug,
      'penulis' => $this->request->getVar('penulis'),
      'penerbit' => $this->request->getVar('penerbit'),
      'sampul' => $namaSampul
    ]);

    session()->setFlashdata('pesan', 'Data Berhasil diubanh');

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
