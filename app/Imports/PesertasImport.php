<?php

namespace App\Imports;

use App\Pesertas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PesertasImport implements ToModel, WithHeadingRow
{
    protected $sertif_id;

    public function __construct($sertif_id)
    {
        $this->sertif_id = $sertif_id;
    }

    public function model(array $row)
    {
        // Convert all keys to lowercase for case insensitive comparison
        $row = array_change_key_case($row, CASE_LOWER);

        return new Pesertas([
            'name' => $row['name'] ?? $row['nama'] ?? null,
            'email' => $row['email'] ?? $row['e-mail'] ?? null,
            'partisipan' => $row['partisipan'] ?? $row['sebagai'] ?? $row['keterangan'] ?? $row['peserta'] ?? null,
            'sertif_id' => $this->sertif_id // Use the sertif_id from constructor
        ]);
    }
}