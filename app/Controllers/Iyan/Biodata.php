<?php

namespace App\Controllers\Iyan;

use App\Controllers\BaseController;
use App\Models\Konfigurasi_model;
use App\Models\Biodata_model;


class Biodata extends BaseController
{
    public function __construct()
    {
        helper('form', 'image_helper');
        //load model
        $this->Konfigurasi_model = new Konfigurasi_model();
        $this->Biodata_model = new Biodata_model();
    }
    //Halaman utama dasbor
    public function index()
    {
        //memanggil function di model
        $konfigurasi = $this->Konfigurasi_model->listing();
        $biodata = $this->Biodata_model->listing();
        $id_biodata = $this->request->getPost('id');
        //reguest Post
        $i              = $this->request;
        if ($this->request->getMethod() == 'post') {
            //validasion
            $rules = [
                'gambar' => [
                    'label'  => 'gambar',
                    'rules'  => 'mime_in[gambar,image/jpg,image/jpeg,image/png]|max_size[gambar,40000]|is_image[gambar]|max_dims[gambar,2000,2000]',
                    'errors' => [
                        'mime_in'  => 'Not a Picture !!!',
                        'max_size' => 'Oversize !!!',
                        'is_image' => 'Not a Picture !!!',
                        'max_dims' => 'Height and Width Exceeding 1000 X 1000'
                    ]
                ]
            ];
            if ($this->validate($rules)) {
                //penyimpanan file gambar
                $path = "./upload/image/bio/";
                $file = $this->request->getFile('gambar');
                if ($file->getError() == 4) {
                    //masukan ke database tanpa gambar
                    $data = array(
                        'id_biodata'                    => $id_biodata,
                        'panggilan'                     => $i->getPost('panggilan'),
                        'saya'                          => $i->getPost('about'),
                        'alamat'                        => $i->getPost('alamat'),
                        'email'                         => $i->getPost('email'),
                        'telpon'                        => $i->getPost('telpon'),
                        'fb'                            => $i->getPost('fb'),
                        'ig'                            => $i->getPost('ig'),
                        'linked'                        => $i->getPost('linked'),
                        'github'                        => $i->getPost('github')
                    );
                } else {
                    //memasukan data ke database dengan gambar
                    $file = $this->request->getFile('gambar');
                    $user = $this->Biodata_model->detail_data($id_biodata);
                    if ($user['gambar'] != "") {
                        //hapus gambar sebelumnya
                        unlink('upload/image/bio/' . $user['gambar']);
                    }
                    $file->move($path);
                    $fileName = $file->getName();
                    $data['gambar'] = $fileName;
                    $data = array(
                        'id_biodata'                    => $id_biodata,
                        'panggilan'                     => $i->getPost('panggilan'),
                        'saya'                          => $i->getPost('about'),
                        'alamat'                        => $i->getPost('alamat'),
                        'email'                         => $i->getPost('email'),
                        'telpon'                        => $i->getPost('telpon'),
                        'fb'                            => $i->getPost('fb'),
                        'ig'                            => $i->getPost('ig'),
                        'linked'                        => $i->getPost('linked'),
                        'github'                        => $i->getPost('github'),
                        'gambar'                        => $file->getName()
                    );
                }
                //load ke model
                $this->Biodata_model->edit($data);
                session()->setflashdata('pesan', 'Successfully Updated Data');
                return redirect()->to(base_url('iyan/biodata'));
            } else {
                $data['validation'] = $this->validator;
            }
        }
        $data = array(
            'title'         => 'Biodata',
            'web'           => 'PangestuJava',
            'jenis'         => 'Form Biodata',
            'biodata'       => $biodata,
            'konfigurasi'   => $konfigurasi,
            'isi'           => 'iyan/biodata/list'
        );
        return view('iyan/layout/wrapper', $data);
    }

    public function about()
    {
        $konfigurasi = $this->Konfigurasi_model->listing();
        $biodata = $this->Biodata_model->listing();
        $id_biodata = $this->request->getPost('id');
        $i              = $this->request;
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'profil' => [
                    'label'  => 'profil',
                    'rules'  => 'mime_in[profil,image/jpg,image/jpeg,image/png]|max_size[profil,40000]|is_image[profil]|max_dims[profil,500,500]',
                    'errors' => [
                        'mime_in'  => 'Not a Picture !!!',
                        'max_size' => 'Oversize !!!',
                        'is_image' => 'Not a Picture !!!',
                        'max_dims' => 'Height and Width Exceeding 1000 X 1000'
                    ]
                ]
            ];
            if ($this->validate($rules)) {
                $path = "./upload/image/bio/";
                $file = $this->request->getFile('profil');
                if ($file->getError() == 4) {
                    $data = array(
                        'id_biodata'                    => $id_biodata,
                        'nama'                          => $i->getPost('nama'),
                        'ahli'                          => $i->getPost('ahli'),
                        'link'                          => $i->getPost('link')
                    );
                } else {
                    $file = $this->request->getFile('profil');
                    $user = $this->Biodata_model->detail_data($id_biodata);
                    if ($user['profil'] != "") {
                        unlink('upload/image/bio/' . $user['profil']);
                    }
                    $file->move($path);
                    $fileName = $file->getName();
                    $data['profil'] = $fileName;
                    $data = array(
                        'id_biodata'                    => $id_biodata,
                        'nama'                          => $i->getPost('nama'),
                        'ahli'                          => $i->getPost('ahli'),
                        'link'                          => $i->getPost('link'),
                        'profil'                        => $file->getName()
                    );
                }
                $this->Biodata_model->edit($data);
                session()->setflashdata('pesan', 'Successfully Updated Data');
                return redirect()->to(base_url('iyan/biodata/about'));
            } else {
                $data['validation'] = $this->validator;
            }
        }
        $data = array(
            'title'         => 'Biodata',
            'web'           => 'PangestuJava',
            'jenis'         => 'Form Biodata',
            'biodata'       => $biodata,
            'konfigurasi'   => $konfigurasi,
            'isi'           => 'iyan/biodata/about'
        );
        return view('iyan/layout/wrapper', $data);
    }
}
