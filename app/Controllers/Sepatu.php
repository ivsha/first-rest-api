<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelSepatu;
use CodeIgniter\API\ResponseTrait;

class Sepatu extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        $sepatu = new ModelSepatu();
        $data = $sepatu->findAll();
        $response = [
        'status' => 200,
        'error' => "false",
        'message' => '',
        'totaldata' => count($data),
        'data' => $data,
        ];
        return $this->respond($response, 200);

    }

    public function show($cari = null)
    {
        $sepatu = new ModelSepatu();
        $data = $sepatu->orLike('id_sepatu', $cari)->orLike('nama', $cari)->get()->getResult();
        if(count($data) > 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        }
        else if(count($data) == 1) {
            $response = [
                'status' => 200,
                'error' => "false",
                'message' => '',
                'totaldata' => count($data),
                'data' => $data,
            ];
            return $this->respond($response, 200);
        }
        else {
            return $this->failNotFound('maaf data ' . $cari . ' tidak ditemukan');
        }
    }

    public function create()
    {
        $sepatu = new ModelSepatu();
        $id_sepatu = $this->request->getPost("id_sepatu");
        $nama = $this->request->getPost("nama");
        $merk = $this->request->getPost("merk");
        $deskripsi = $this->request->getPost("deskripsi");
        $gambar = $this->request->getPost("gambar");
        $validation = \Config\Services::validation();
        $valid = $this->validate([
            'id_sepatu' => [
            'rules' => 'is_unique[sepatu.id_sepatu]',
            'label' => 'Nomor Induk sepatu',
            'errors' => [
            'is_unique' => "{field} sudah ada"
            ]
            ]
        ]);
        if(!$valid){
            $response = [
            'status' => 404,
            'error' => true,
            'message' => $validation->getError("id_sepatu"),
            ];
            return $this->respond($response, 404);
        }else {
            $sepatu->insert([
                'id_sepatu' => $id_sepatu,
                'nama' => $nama,
                'merk' => $merk,
                'deskripsi' => $deskripsi,
                'gambar' => $gambar,
            ]);
            $response = [
                'status' => 201,
                'error' => "false",
                'message' => "Data berhasil disimpan"
            ];
            return $this->respond($response, 201);
        }
    }

    public function update($id_sepatu = null)
    {
        $model = new ModelSepatu();
        $data = [
            'nama' => $this->request->getVar("nama"),
            'merk' => $this->request->getVar("merk"),
            'deskripsi' => $this->request->getVar("deskripsi"),
            'gambar' => $this->request->getVar("gambar"),
        ];
        $data = $this->request->getRawInput();
        $model->update($id_sepatu, $data);
        $response = [
            'status' => 200,
            'error' => null,
            'message' => "Data Anda dengan NIM $id_sepatu berhasil 
            dibaharukan"
        ];
        return $this->respond($response);
    }

    public function delete($id_sepatu = null)
    {
        $sepatu = new ModelSepatu();
        $cekData = $sepatu->find($id_sepatu);
        if($cekData) {
            $sepatu->delete($id_sepatu);
            $response = [
            'status' => 200,
            'error' => null,
            'message' => "Selamat data sudah berhasil dihapus 
            maksimal"
            ];
            return $this->respondDeleted($response);
        }else {
            return $this->failNotFound('Data tidak ditemukan kembali');
        }
    }
}
