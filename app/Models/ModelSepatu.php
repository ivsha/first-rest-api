<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelSepatu extends Model
{
    protected $table = 'sepatu';
    protected $primaryKey = 'id_sepatu';
    protected $allowedFields = [
    'nama','merk','deskripsi','gambar'
    ];
}