<?php

namespace App\Models;

use Kreait\Firebase\Database;

class FeeCancel
{
    protected $database;
    protected $dbname = 'fee_cancels';

    public function __construct()
    {
        // Inisialisasi Firebase Database
        $this->database = app('firebase')->getDatabase();
    }

    /**
     * Ambil semua data fee cancels
     * 
     * @return array
     */
    public function all()
    {
        // Mengambil semua data dari Firebase
        $feeCancels = $this->database->getReference($this->dbname)->getValue();
        return $feeCancels ?? [];
    }

    /**
     * Cari data berdasarkan ID
     * 
     * @param string $id
     * @return array|null
     */
    public function find($id)
    {
        // Mendapatkan data berdasarkan ID
        $feeCancel = $this->database->getReference("{$this->dbname}/{$id}")->getValue();
        return $feeCancel ?? null;
    }

    /**
     * Buat data baru
     * 
     * @param array $data
     * @return string ID dari data yang dibuat
     */
    public function create(array $data)
    {
        // Push data baru ke Firebase
        $postRef = $this->database->getReference($this->dbname)->push($data);
        return $postRef->getKey(); // Mengembalikan ID baru
    }

    /**
     * Perbarui data berdasarkan ID
     * 
     * @param string $id
     * @param array $data
     * @return void
     */
    public function update($id, array $data)
    {
        // Update data di Firebase
        $this->database->getReference("{$this->dbname}/{$id}")->update($data);
    }

    /**
     * Hapus data berdasarkan ID
     * 
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        // Hapus data di Firebase
        $this->database->getReference("{$this->dbname}/{$id}")->remove();
    }

    /**
     * Mengambil data user yang berelasi
     * 
     * @param string $userId
     * @return array|null
     */
    public function user($userId)
    {
        // Ambil data user berdasarkan userId
        $user = app('firebase')->getDatabase()->getReference("users/{$userId}")->getValue();
        return $user ?? null;
    }
}
