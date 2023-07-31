<?php

namespace App\Http\Services;

use App\Events\BillingEvents;
use App\Models\Billing;
use App\Models\Business;
use App\Models\Floor;
use App\Models\Payments;
use App\Models\Process;
use App\Models\ProcessType;
use App\Models\Setting;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManagementService
{

    protected int $businessId;
    protected int $branchId;
    protected Process $process;
    protected Billing $billing;

    public function __construct(int $businessId, int $branchId)
    {
        $this->businessId = $businessId;
        $this->branchId = $branchId;

        $this->process = new Process();
        $this->billing = new Billing();
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
        return Billing::NextNumberDocument($type, $this->getSerie(), $this->branchId, $this->businessId);
    }

    public function getSerie(): string
    {
        return Setting::where('business_id', $this->businessId)->where('branch_id', $this->branchId)->first()->serie;
    }

    public function getLastProcessInRoom(int $roomId): int
    {
        return Process::where('room_id', $roomId)->where('business_id', $this->businessId)->where('branch_id', $this->branchId)->orderBy('id', 'desc')->first()->id;
    }

    public function getCashRegisterNumber(int $cashBoxId): string
    {
        return $this->process->NextNumberCashRegister(null, $this->businessId, $this->branchId, $cashBoxId);
    }

    public function createBilling(Process $process, array $billing): Billing
    {
        $amounts = $this->billing->GetBillingAmounts($this->businessId, $this->branchId, (float) $process->amount);
        $billing = $this->billing->create([
            'date' => date('Y-m-d H:i:s'),
            'number' => $billing['number'],
            'type' => $billing['type'],
            'status' => Billing::STATUS_CREATED,
            'total' => $amounts['total'],
            'igv' => $amounts['igv'],
            'subtotal' => $amounts['subtotal'],
            'client_id' => $billing['client_id'] ?? $process->client_id,
            'process_id' => $process->id,
            'user_id' => auth()->user()->id,
            'business_id' => $this->businessId,
            'branch_id' => $this->branchId,
        ]);

        $billing->details()->create([
            'process_id' => $process->id,
            'billing_id' => $billing->id,
            'notes' => 'Servicio de Alquiler de ' .  $process->room->roomType->name,
            'amount' => 1,
            'sale_price' => $process->amount,
            'purchase_price' => $process->amount,
            'total' => $process->amount,
            'business_id' => $this->businessId,
            'branch_id' => $this->branchId,
        ]);

        return $billing;
    }

    public function getDocumentTypes(): array
    {
        $hasBilling = Business::find($this->businessId)->hasBilling;
        if ($hasBilling) {
            return ['BOLETA' => 'BOLETA', 'FACTURA' => 'FACTURA', 'TICKET' => 'TICKET'];
        } else {
            return ['TICKET' => 'TICKET'];
        }
    }
}
