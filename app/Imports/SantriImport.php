<?php

namespace App\Imports;

use App\Models\Santri;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SantriImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Parse dates if they are in Excel serial format, or fallback to standard parsing
        $tanggal_lahir = null;
        if (!empty($row['tanggal_lahir'])) {
            if (is_numeric($row['tanggal_lahir'])) {
                $tanggal_lahir = Date::excelToDateTimeObject($row['tanggal_lahir'])->format('Y-m-d');
            } else {
                try {
                    $tanggal_lahir = Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $tanggal_lahir = null;
                }
            }
        }

        return new Santri([
            'nis'           => $row['nis'],
            'nama'          => $row['nama'],
            'jenis_kelamin' => strtoupper($row['jenis_kelamin']),
            'tempat_lahir'  => $row['tempat_lahir'] ?? null,
            'tanggal_lahir' => $tanggal_lahir,
            'no_telepon'    => $row['no_telepon'] ?? null,
            'alamat'        => $row['alamat'] ?? null,
            'status'        => 'aktif', // Default status for imported santri
            // Relasi (kelas, kamar, wali) akan diset null terlebih dahulu
            // Admin bisa melengkapinya melalui fitur edit di web.
        ]);
    }

    public function rules(): array
    {
        return [
            'nis'           => 'required|unique:santri,nis',
            'nama'          => 'required',
            'jenis_kelamin' => 'required|in:L,P,l,p',
        ];
    }
}
