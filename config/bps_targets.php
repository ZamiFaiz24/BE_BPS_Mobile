<?php

return [
    'datasets' => [
        // Data 1: Tingkat Pengangguran Terbuka Menurut Jenis Kelamin
        [
            'id'           => 'dataset_tpt_gender_644',
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Jenis Kelamin',
            'variable_id'  => 644,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'insight_type' => 'default',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Data 2: Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan
        [
            'id'           => 'dataset_tpt_pendidikan_649',
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan di Kebumen (Persen)',
            'variable_id'  => 649,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'percent_lower_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Data 3: Jumlah Penduduk (var 51) versi panjang
        [
            'id'           => 'dataset_populasi_kebumen_51_full',
            'model'        => 'data',
            'name'         => 'Jumlah Penduduk Kabupaten Kebumen Menurut Jenis Kelamin dan Kecamatan (Jiwa)',
            'variable_id'  => 51,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2014,
            'tahun_akhir'  => 2024,
            'insight_type' => 'default',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // TPT pendidikan (var 799)
        [
            'id'           => 'dataset_tpt_pendidikan_799',
            'model'        => 'data',
            'name'         => 'Tingkat Pengangguran Terbuka Menurut Tingkat Pendidikan di Kebumen (Persen) [Alt]',
            'variable_id'  => 799,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'percent_lower_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // TPAK (var 695)
        [
            'id'           => 'dataset_tpak_gender_695',
            'model'        => 'data',
            'name'         => 'Tingkat Partisipasi Angkatan Kerja (TPAK) Menurut Jenis Kelamin di Kabupaten Kebumen (Persen)',
            'variable_id'  => 695,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'insight_type' => 'percent_lower_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // PDRB Pengeluaran Harga Berlaku (persen) var 840
        [
            'id'           => 'dataset_pdrb_pengeluaran_persen_840',
            'model'        => 'data',
            'name'         => 'Distribusi Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen (Persen)',
            'variable_id'  => 840,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // PDRB Pengeluaran Harga Konstan (milyar) var 839
        [
            'id'           => 'dataset_pdrb_pengeluaran_konstan_839',
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Konstan 2010 Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 839,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // PDRB Pengeluaran Harga Berlaku (milyar) var 838
        [
            'id'           => 'dataset_pdrb_pengeluaran_berlaku_838',
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Pengeluaran Atas Dasar Harga Berlaku Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 838,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // PDRB Usaha Harga Konstan (milyar) var 831
        [
            'id'           => 'dataset_pdrb_usaha_konstan_831',
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Konstan Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 831,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // PDRB Usaha Harga Berlaku (milyar) var 830
        [
            'id'           => 'dataset_pdrb_usaha_berlaku_830',
            'model'        => 'data',
            'name'         => 'Produk Domestik Regional Bruto (PDRB) Triwulanan Menurut Lapangan Usaha Atas Dasar Harga Berlaku Kabupaten Kebumen (Milyar Rupiah)',
            'variable_id'  => 830,
            'unit'         => 'Milyar Rupiah',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // NIK 0-4 tahun (637)
        [
            'id'           => 'dataset_nik_0_4_637',
            'model'        => 'data',
            'name'         => 'Persentase Penduduk Berumur 0-4 Tahun yang Mempunyai Nomor Induk Kependudukan (NIK) menurut Jenis Kelamin',
            'variable_id'  => 637,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2021,
            'tahun_akhir'  => 2021,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // NIK 17+ (638)
        [
            'id'           => 'dataset_nik_17plus_638',
            'model'        => 'data',
            'name'         => 'Persentase Penduduk Berumur 17 Tahun ke Atas yang Mempunyai Nomor Induk Kependudukan (NIK) menurut Jenis Kelamin',
            'variable_id'  => 638,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2021,
            'tahun_akhir'  => 2021,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // NIK 5+ (639)
        [
            'id'           => 'dataset_nik_5plus_639',
            'model'        => 'data',
            'name'         => 'Persentase Penduduk Berumur 5 Tahun ke Atas yang Mempunyai Nomor Induk Kependudukan (NIK) menurut Jenis Kelamin',
            'variable_id'  => 639,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2021,
            'tahun_akhir'  => 2021,
            'insight_type' => 'percent_higher_is_better',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Penduduk menurut kelompok umur (Perempuan) 49
        [
            'id'           => 'dataset_penduduk_perempuan_49',
            'model'        => 'data',
            'name'         => 'Penduduk Menurut Kelompok Umur dan Kecamatan (Perempuan)',
            'variable_id'  => 49,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2013,
            'tahun_akhir'  => 2022,
            'insight_type' => 'default',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Penduduk menurut kelompok umur (Laki-laki + Perempuan) 28
        [
            'id'           => 'dataset_penduduk_total_28',
            'model'        => 'data',
            'name'         => 'Penduduk Menurut Kelompok Umur dan Kecamatan (Laki-laki + Perempuan)',
            'variable_id'  => 28,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2013,
            'tahun_akhir'  => 2022,
            'insight_type' => 'default',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Penduduk menurut kelompok umur (Laki-laki) 27
        [
            'id'           => 'dataset_penduduk_laki_27',
            'model'        => 'data',
            'name'         => 'Penduduk Menurut Kelompok Umur dan Kecamatan (Laki-Laki)',
            'variable_id'  => 27,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2013,
            'tahun_akhir'  => 2022,
            'insight_type' => 'default',
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Angkatan kerja vs pendidikan 221
        [
            'id'           => 'dataset_angkatan_kerja_pendidikan_221',
            'model'        => 'data',
            'name'         => 'Penduduk Berumur 15 Tahun Ke Atas yang Termasuk Angkatan Kerja Menurut Pendidikan Tertinggi yang Ditamatkan dan Kegiatan Selama Seminggu yang Lalu di Kabupaten Kebumen',
            'variable_id'  => 221,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2023,
            'insight_type' => 'default',
            'category'     => 1,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Kejadian bencana 442
        [
            'id'           => 'dataset_bencana_442',
            'model'        => 'data',
            'name'         => 'Jumlah Kejadian Bencana Alam Menurut Kecamatan di Kabupaten Kebumen',
            'variable_id'  => 442,
            'unit'         => 'Kejadian',
            'tahun_mulai'  => 2020,
            'tahun_akhir'  => 2023,
            'insight_type' => 'default',
            'category'     => 3,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Dusun/RW/RT 303
        [
            'id'           => 'dataset_dusun_rw_rt_303',
            'model'        => 'data',
            'name'         => 'Jumlah Dusun, Rukun Warga (RW), dan Rukun Tetangga (RT) Menurut Kecamatan di Kabupaten Kebumen',
            'variable_id'  => 303,
            'unit'         => 'Unit',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2023,
            'insight_type' => 'default',
            'category'     => 3,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Beban ketergantungan 770
        [
            'id'           => 'dataset_beban_ketergantungan_770',
            'model'        => 'data',
            'name'         => 'Angka Beban Ketergantungan di Kabupaten Kebumen',
            'variable_id'  => 770,
            'unit'         => 'Indeks',
            'tahun_mulai'  => 2017,
            'tahun_akhir'  => 2022,
            'insight_type' => 'number_lower_is_better',
            'category'     => 1,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Pariwisata 771
        [
            'id'           => 'dataset_wisata_tujuan_771',
            'model'        => 'data',
            'name'         => 'Jumlah Perjalanan Wisatawan Nusantara dengan Tujuan Kabupaten Kebumen',
            'variable_id'  => 771,
            'unit'         => 'Perjalanan',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'insight_type' => 'number_higher_is_better',
            'category'     => 2,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Pariwisata 772
        [
            'id'           => 'dataset_wisata_asal_772',
            'model'        => 'data',
            'name'         => 'Jumlah Perjalanan Wisatawan Nusantara dari Asal Kabupaten Kebumen',
            'variable_id'  => 772,
            'unit'         => 'Perjalanan',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2024,
            'insight_type' => 'number_higher_is_better',
            'category'     => 2,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Wisatawan mancanegara/domestik 165
        [
            'id'           => 'dataset_wisata_mancanegara_domestik_165',
            'model'        => 'data',
            'name'         => 'Jumlah Wisatawan Mancanegara dan Domestik di Kabupaten Kebumen',
            'variable_id'  => 165,
            'unit'         => 'Orang',
            'tahun_mulai'  => 2018,
            'tahun_akhir'  => 2023,
            'insight_type' => 'number_higher_is_better',
            'category'     => 2,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Upah pekerja formal 827
        [
            'id'           => 'dataset_upah_formal_827',
            'model'        => 'data',
            'name'         => 'Rata-rata Upah/Gaji Bersih Sebulan Pekerja Formal Menurut Lapangan Pekerjaan Utama di Kabupaten Kebumen',
            'variable_id'  => 827,
            'unit'         => 'Rupiah',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'number_higher_is_better',
            'category'     => 2,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Pendapatan pekerja informal 826
        [
            'id'           => 'dataset_pendapatan_informal_826',
            'model'        => 'data',
            'name'         => 'Rata-rata Pendapatan Bersih Sebulan Pekerja Informal Menurut Lapangan Pekerjaan Utama di Kabupaten Kebumen',
            'variable_id'  => 826,
            'unit'         => 'Rupiah',
            'tahun_mulai'  => 2019,
            'tahun_akhir'  => 2023,
            'insight_type' => 'number_higher_is_better',
            'category'     => 2,
            'enabled'      => false,
            'params'       => [
                'domain' => '3305',
            ],
        ],

        // Dataset aktif sebelumnya (versi pendek) var 51
        [
            'id'           => 'dataset_populasi_kebumen_51',
            'model'        => 'data',
            'name'         => 'Jumlah Penduduk Kabupaten Kebumen Menurut Jenis Kelamin dan Kecamatan',
            'variable_id'  => 51,
            'unit'         => 'Jiwa',
            'tahun_mulai'  => 2022,
            'tahun_akhir'  => 2024,
            'insight_type' => 'default',
            'category'     => 1,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // Gini ratio 687
        [
            'id'           => 'dataset_gini_687',
            'model'        => 'data',
            'name'         => 'Ukuran Ketimpangan Gini Rasio di Kabupaten Kebumen',
            'variable_id'  => 687,
            'unit'         => 'Rasio',
            'tahun_mulai'  => 2000,
            'tahun_akhir'  => 2023,
            'insight_type' => 'number_lower_is_better',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // P1 289
        [
            'id'           => 'dataset_p1_289',
            'model'        => 'data',
            'name'         => 'Indeks Kedalaman Kemiskinan (P1) (Persen) di Kabupaten Kebumen',
            'variable_id'  => 289,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2002,
            'tahun_akhir'  => 2025,
            'insight_type' => 'percent_lower_is_better',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // P2 290
        [
            'id'           => 'dataset_p2_290',
            'model'        => 'data',
            'name'         => 'Indeks Keparahan Kemiskinan (P2) (Persen) di Kabupaten Kebumen',
            'variable_id'  => 290,
            'unit'         => 'Persen',
            'tahun_mulai'  => 2002,
            'tahun_akhir'  => 2024,
            'insight_type' => 'percent_lower_is_better',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // IPM metode baru 111
        [
            'id'           => 'dataset_ipm_baru_111',
            'model'        => 'data',
            'name'         => '[Metode Baru] Indeks Pembangunan Manusia Kabupaten Kebumen',
            'variable_id'  => 111,
            'unit'         => 'Indeks',
            'tahun_mulai'  => 2010,
            'tahun_akhir'  => 2024,
            'insight_type' => 'number_higher_is_better',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // IPM long form 674
        [
            'id'           => 'dataset_ipm_longform_674',
            'model'        => 'data',
            'name'         => 'Indeks Pembangunan Manusia Kabupaten Kebumen (Umur Harapan Hidup Hasil Long Form SP2020)',
            'variable_id'  => 674,
            'unit'         => 'Indeks',
            'tahun_mulai'  => 2020,
            'tahun_akhir'  => 2024,
            'insight_type' => 'number_higher_is_better',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // UHH/HLS/RLS/IPM menurut gender 702
        [
            'id'           => 'dataset_ipm_gender_mixed_702',
            'model'        => 'data',
            'name'         => 'UHH, HLS, RLS, Pengeluaran Riil per Kapita, IPM Menurut Jenis Kelamin',
            'variable_id'  => 702,
            'unit'         => 'Beragam',
            'tahun_mulai'  => 2014,
            'tahun_akhir'  => 2024,
            'insight_type' => 'default',
            'category'     => 3,
            'enabled'      => false,
            'params'       => ['domain' => '3305'],
        ],

        // // APM 246
        // [
        //     'id'           => 'dataset_apm_246',
        //     'model'        => 'data',
        //     'name'         => 'Angka Partisipasi Murni (APM) di Kabupaten Kebumen',
        //     'variable_id'  => 246,
        //     'unit'         => 'Persen',
        //     'tahun_mulai'  => 2006,
        //     'tahun_akhir'  => 2023,
        //     'insight_type' => 'percent_higher_is_better',
        //     'category'     => 3,
        //     'enabled'      => true,
        //     'params'       => ['domain' => '3305'],
        // ],

        // // APK 238
        // [
        //     'id'           => 'dataset_apk_238',
        //     'model'        => 'data',
        //     'name'         => 'Angka Partisipasi Kasar (APK) di Kabupaten Kebumen',
        //     'variable_id'  => 238,
        //     'unit'         => 'Persen',
        //     'tahun_mulai'  => 2006,
        //     'tahun_akhir'  => 2023,
        //     'insight_type' => 'percent_higher_is_better',
        //     'category'     => 3,
        //     'enabled'      => true,
        //     'params'       => ['domain' => '3305'],
        // ],

        // // APS 717
        // [
        //     'id'           => 'dataset_aps_717',
        //     'model'        => 'data',
        //     'name'         => 'Angka Partisipasi Sekolah (APS) Menurut Kelompok Umur Sekolah di Kabupaten Kebumen',
        //     'variable_id'  => 717,
        //     'unit'         => 'Persen',
        //     'tahun_mulai'  => 2006,
        //     'tahun_akhir'  => 2023,
        //     'insight_type' => 'percent_higher_is_better',
        //     'category'     => 3,
        //     'enabled'      => true,
        //     'params'       => ['domain' => '3305'],
        // ],

    ]
];
