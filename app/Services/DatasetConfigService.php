<?php

namespace App\Services;

use App\Models\DatasetOverride;
use Illuminate\Support\Facades\Log;

class DatasetConfigService
{
    /**
     * Ambil semua datasets dari config + database overrides
     */
    public function getAllDatasets()
    {
        // 1. Ambil dari config file
        $configDatasets = config('bps_targets.datasets') ?? [];

        // 2. Ambil dari database (quick add + overrides)
        $dbOverrides = DatasetOverride::all()->keyBy('dataset_id');

        // 3. Merge & apply overrides
        $allDatasets = [];

        foreach ($configDatasets as $dataset) {
            $datasetId = $dataset['id'] ?? null;

            if (!$datasetId) {
                // Jika tidak ada ID di config, skip
                Log::warning('Dataset di config tidak punya id:', $dataset);
                continue;
            }

            // Cek apakah ada override di database
            if (isset($dbOverrides[$datasetId])) {
                $override = $dbOverrides[$datasetId];

                // Jika override ada, gunakan status enabled dari override
                $dataset['enabled'] = $override->enabled;
                $dataset['_override'] = true;
                $dataset['_override_id'] = $override->id;

                // Merge config override (tahun_mulai, tahun_akhir, dll)
                if ($override->config && is_array($override->config)) {
                    $dataset = array_merge($dataset, $override->config);
                }
            } else {
                // Jika tidak ada override, gunakan default dari config
                $dataset['enabled'] = $dataset['enabled'] ?? true;
            }

            $allDatasets[$datasetId] = $dataset;
        }

        // 4. Tambahkan override yang tidak punya entry di config (dataset baru/quick add)
        foreach ($dbOverrides as $datasetId => $override) {
            if (isset($allDatasets[$datasetId])) {
                continue; // sudah ter-merge di atas
            }

            // Coba ambil info dari DB BpsDataset agar ada nama dan metadata
            $dbDataset = \App\Models\BpsDataset::where('dataset_code', $datasetId)->first();

            $dataset = [
                'id' => $datasetId,
                // Gunakan datasetId sebagai variable_id agar match dengan dataset_code di DB
                'variable_id' => $datasetId,
                'enabled' => $override->enabled,
                '_override' => true,
                '_override_id' => $override->id,
                'name' => $dbDataset->dataset_name ?? null,
                'dataset_name' => $dbDataset->dataset_name ?? null,
                'subject' => $dbDataset->subject ?? null,
                'category' => $dbDataset->category ?? null,
                'unit' => $dbDataset->unit ?? null,
            ];

            if ($override->config && is_array($override->config)) {
                $dataset = array_merge($dataset, $override->config);
            }

            $allDatasets[$datasetId] = $dataset;
        }

        return $allDatasets;
    }

    /**
     * Ambil datasets yang enabled saja
     */
    public function getEnabledDatasets()
    {
        $allDatasets = $this->getAllDatasets();

        return array_filter($allDatasets, function ($dataset) {
            return $dataset['enabled'] ?? true;
        });
    }

    /**
     * Cek apakah dataset enabled
     */
    public function isDatasetEnabled($datasetId)
    {
        $datasets = $this->getAllDatasets();

        return $datasets[$datasetId]['enabled'] ?? true;
    }

    /**
     * Toggle enable/disable dataset
     */
    public function toggleDataset($datasetId, $enabled)
    {
        try {
            DatasetOverride::updateOrCreate(
                ['dataset_id' => $datasetId],
                [
                    'source_type' => 'config',
                    'enabled' => $enabled,
                ]
            );

            Log::info("Dataset {$datasetId} toggled to " . ($enabled ? 'enabled' : 'disabled'));
            return true;
        } catch (\Exception $e) {
            Log::error("Error toggling dataset {$datasetId}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update config dataset (tahun_mulai, tahun_akhir, dll)
     */
    public function updateDatasetConfig($datasetId, array $configData)
    {
        try {
            $override = DatasetOverride::firstOrNew(['dataset_id' => $datasetId]);

            // Merge config lama dengan yang baru
            $currentConfig = $override->config ?? [];
            $newConfig = array_merge($currentConfig, $configData);

            $override->dataset_id = $datasetId;
            $override->source_type = 'config';
            $override->config = $newConfig;
            $override->save();

            Log::info("Dataset {$datasetId} config updated", $configData);
            return true;
        } catch (\Exception $e) {
            Log::error("Error updating dataset {$datasetId} config: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ambil dataset specific berdasarkan ID
     */
    public function getDataset($datasetId)
    {
        $datasets = $this->getAllDatasets();

        return $datasets[$datasetId] ?? null;
    }

    /**
     * Ambil list datasets untuk frontend
     */
    public function getDatasetsList()
    {
        $datasets = $this->getAllDatasets();
        $list = [];

        foreach ($datasets as $id => $dataset) {
            $list[] = [
                'id' => $id,
                'name' => $dataset['name'] ?? 'Unknown',
                'variable_id' => $dataset['variable_id'] ?? null,
                'unit' => $dataset['unit'] ?? null,
                'category' => $dataset['category'] ?? null,
                'tahun_mulai' => $dataset['tahun_mulai'] ?? null,
                'tahun_akhir' => $dataset['tahun_akhir'] ?? null,
                'enabled' => $dataset['enabled'] ?? true,
                'source' => $dataset['_override'] ?? false ? 'override' : 'config',
            ];
        }

        return $list;
    }
}
