<?php

namespace App\Services\DatasetHandlers;

use App\Models\BpsDataset;

interface DatasetHandlerInterface
{
    public function __construct(BpsDataset $dataset);
    
    public function getTableData(): array;
    public function getChartData(): array;
    public function getInsightData(): array;
}
