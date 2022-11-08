<?php

namespace App\Http\Services;

use App\Models\Floor;
use App\Models\Process;
use Illuminate\Support\Collection;

class CashRegisterService
{

    protected int $businessId;
    protected int $branchId;

    public function __construct(int $businessId, int $branchId)
    {
        $this->businessId = $businessId;
        $this->branchId = $branchId;
    }

    public function getStatus(): string
    {
        $lastProcess = Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('processtype_id', 2) //MOVIMIENTOS DE CAJA
            ->orderBy('id', 'desc')
            ->first();
        if ($lastProcess) {
            $lastConcept = $lastProcess->concept_id;
            if ($lastConcept == 2) {
                return 'close';
            } else {
                return 'open';
            }
        }
        return 'close';
    }

    public function getCashResister()
    {
    }
}