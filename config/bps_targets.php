<?php

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
            'insight_type' => 'percent_lower_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],

        [
            'model'        => 'data',
            'name'         => 'Distribusi Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen (Persen)',
            'variable_id'  => 840,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],
        [
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Konstan 2010 Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 839,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],
        [
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 838,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],
        [
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Konstan Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 831,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],
        [
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Berlaku Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 830,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'params'       => [
                'domain' => '3305',
            ],
        ],
    ],
];
