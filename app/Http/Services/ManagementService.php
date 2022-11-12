<?php

namespace App\Http\Services;

use App\Models\Billing;
use App\Models\Floor;
use App\Models\Process;
use App\Models\Setting;
use Illuminate\Support\Collection;

class ManagementService
{

    protected int $businessId;
    protected int $branchId;

    public function __construct(int $businessId, int $branchId)
    {
        $this->businessId = $businessId;
        $this->branchId = $branchId;
    }

    public function getFloorsWithRooms(int $id = null)
    {
        $floors = Floor::with('rooms')
            ->where('business_id', $this->businessId)->where('branch_id', $this->branchId)->orderBy('id', 'asc')->get();
        $data = collect();
        foreach ($floors as $floor) {
            $data->push([
                'id' => $floor->id,
                'name' => $floor->name,
                'rooms' => $floor->rooms,
                'status' => $id && $id == $floor->id ? 'open' : (!$id && $floor->id == $floors->first()->id ? 'open' : 'close'),
            ]);
        }

        return collect($data);
    }

    public function generateCheckInNumber(): string
    {
        return Process::NextNumberCheckIn(null, $this->businessId, $this->branchId);
    }

    public function generateDocumentNumber(string $type): string
    {
        return Billing::NextNumberDocument($type, $this->getSerie(), $this->businessId, $this->branchId);
    }

    public function getSerie(): string
    {
        return Setting::where('business_id', $this->businessId)->where('branch_id', $this->branchId)->first()->serie;
    }
}