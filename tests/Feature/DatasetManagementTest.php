<?php

// Test file untuk verify Dataset Management System
// File ini bisa di-delete setelah testing selesai

namespace Tests\Feature;

use Tests\TestCase;
use App\Services\DatasetConfigService;
use App\Models\DatasetOverride;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatasetManagementTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1: DatasetConfigService dapat mengambil semua datasets
     */
    public function test_get_all_datasets()
    {
        $service = new DatasetConfigService();
        $datasets = $service->getAllDatasets();

        $this->assertIsArray($datasets);
        $this->assertGreaterThan(0, count($datasets));
        $this->assertArrayHasKey('dataset_populasi_kebumen_51', $datasets);

        dump('✅ Test 1 PASS: getAllDatasets works');
    }

    /**
     * Test 2: DatasetConfigService dapat mengambil datasets yang enabled
     */
    public function test_get_enabled_datasets()
    {
        $service = new DatasetConfigService();
        $enabled = $service->getEnabledDatasets();

        $this->assertIsArray($enabled);
        $this->assertGreaterThan(0, count($enabled));

        dump('✅ Test 2 PASS: getEnabledDatasets works');
    }

    /**
     * Test 3: Toggle dataset functionality
     */
    public function test_toggle_dataset()
    {
        $service = new DatasetConfigService();

        // Toggle off
        $service->toggleDataset('dataset_populasi_kebumen_51', false);
        $this->assertFalse($service->isDatasetEnabled('dataset_populasi_kebumen_51'));

        // Toggle on
        $service->toggleDataset('dataset_populasi_kebumen_51', true);
        $this->assertTrue($service->isDatasetEnabled('dataset_populasi_kebumen_51'));

        dump('✅ Test 3 PASS: toggleDataset works');
    }

    /**
     * Test 4: Get single dataset
     */
    public function test_get_single_dataset()
    {
        $service = new DatasetConfigService();
        $dataset = $service->getDataset('dataset_populasi_kebumen_51');

        $this->assertIsArray($dataset);
        $this->assertEquals('Jumlah Penduduk Kabupaten Kebumen Menurut Jenis Kelamin dan Kecamatan', $dataset['name']);
        $this->assertEquals(51, $dataset['variable_id']);

        dump('✅ Test 4 PASS: getDataset works');
    }

    /**
     * Test 5: Get datasets list for UI
     */
    public function test_get_datasets_list()
    {
        $service = new DatasetConfigService();
        $list = $service->getDatasetsList();

        $this->assertIsArray($list);
        $this->assertGreaterThan(0, count($list));

        $firstItem = $list[0];
        $this->assertArrayHasKey('id', $firstItem);
        $this->assertArrayHasKey('name', $firstItem);
        $this->assertArrayHasKey('variable_id', $firstItem);
        $this->assertArrayHasKey('enabled', $firstItem);

        dump('✅ Test 5 PASS: getDatasetsList works');
    }

    /**
     * Test 6: DatasetOverride model operations
     */
    public function test_dataset_override_model()
    {
        $override = DatasetOverride::create([
            'dataset_id' => 'test_dataset',
            'source_type' => 'config',
            'enabled' => false,
        ]);

        $this->assertDatabaseHas('dataset_overrides', [
            'dataset_id' => 'test_dataset',
            'enabled' => 0,
        ]);

        $override->update(['enabled' => true]);
        $this->assertDatabaseHas('dataset_overrides', [
            'dataset_id' => 'test_dataset',
            'enabled' => 1,
        ]);

        dump('✅ Test 6 PASS: DatasetOverride model works');
    }

    /**
     * Test 7: Config file format validation
     */
    public function test_config_format()
    {
        $datasets = config('bps_targets.datasets');

        foreach ($datasets as $dataset) {
            // Check required fields
            $this->assertArrayHasKey('id', $dataset, 'Dataset harus punya field "id"');
            $this->assertArrayHasKey('enabled', $dataset, 'Dataset harus punya field "enabled"');
            $this->assertArrayHasKey('model', $dataset);
            $this->assertArrayHasKey('name', $dataset);
            $this->assertArrayHasKey('variable_id', $dataset);
        }

        dump('✅ Test 7 PASS: Config format is valid');
    }
}
