<?php
// file: config/bps_targets.php

return [
    'datasets' => [
        // Data 1: Tingkat Pengangguran Terbuka Menurut Jenis Kelamin
        [
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Jenis Kelamin',
            'variable_id'  => 644,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Data 2: Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan
        [
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan di Kebumen (Persen)',
            'variable_id'  => 649,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'percent_lower_is_better', // <-- Tipe Ditambahkan
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Data 3: Persentase Penduduk Bekerja Menurut Lapangan Pekerjaan Utama
        [
            'model'        => 'data',
            'name'         => 'Jumlah Penduduk Kabupaten Kebumen Menurut Jenis Kelamin dan Kecamatan (Jiwa)',
            'variable_id'  => 51,
            'unit'         => 'Jiwa', // <-- Unit Diperbaiki
            'tahun_mulai'  => 2014,
            'tahun_akhir'  => 2024,
            'insight_type' => 'default', // <-- Tipe Ditambahkan
            'params'       => [
                'domain' => '3305',
            ],
        ],


        [
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan di Kebumen (Persen)',
            'variable_id'  => 799,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'percent_lower_is_better', // <-- Tipe Ditambahkan
            'params'       => [
                'domain' => '3305',
            ],
        ],


        [
            'model'        => 'data',
            'name'         => 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin di Kabupaten Kebumen (Persen)',
            'variable_id'  => 695,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'insight_type' => 'percent_lower_is_better', // <-- Tipe Ditambahkan
            'params'       => [
                'domain' => '3305',
            ],
        ],
    ],
];
