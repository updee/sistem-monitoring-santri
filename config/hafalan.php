<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Target halaman Al-Qur'an (acuan progress)
    |--------------------------------------------------------------------------
    |
    | Digunakan untuk menghitung persentase progress hafalan di tampilan wali
    | (total halaman tercatat: setoran baru + muroja'ah, dibanding target mushaf).
    |
    */
    'target_halaman' => (int) env('HAFALAN_TARGET_HALAMAN', 604),

];
