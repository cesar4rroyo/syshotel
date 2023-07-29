<?php

namespace App\Http\Services;

use App\Http\Services\Payment\PaymentService;
use App\Models\Bank;
use App\Models\Card;
use App\Models\DigitalWallet;
use App\Models\PaymentProcess;
use App\Models\Pos;
use App\Models\Process;
use App\Models\ProcessType;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CashRegisterService
{

    protected int $businessId;
    protected int $branchId;
    protected int $cashboxId;

    protected PaymentService $paymentService;

    public function __construct(int $businessId, int $branchId, int $cashboxId)
    {
        $this->businessId = $businessId;
        $this->branchId = $branchId;
        $this->cashboxId = $cashboxId;
        $this->paymentService = new PaymentService();
    }

    public function getLastMovementsIncomes(): Collection
    {
        return Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
            ->where('processtype_id', 2)
            ->orderBy('id', 'asc')
            ->where('id', '>=', $this->getLastOpenCashRegisterId())
            ->whereHas('concept', function ($query) {
                return $query->where('type', 'I');
            })
            ->get();
    }

    public function getLastMovementsExpenses(): Collection
    {
        return Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
            ->where('processtype_id', 2)
            ->orderBy('id', 'asc')
            ->where('id', '>=', $this->getLastOpenCashRegisterId())
            ->whereHas('concept', function ($query) {
                return $query->where('type', 'E');
            })
            ->get();
    }

    public function getStatus(): string
    {
        $lastProcess = Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
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

    public function getLastOpenCashRegisterId(): int
    {
        $lastProcess = Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
            ->where('processtype_id', 2) //MOVIMIENTOS DE CAJA
            ->where('concept_id', 1) //APERTURA DE CAJA
            ->orderBy('id', 'desc')
            ->first();
        if ($lastProcess) {
            return $lastProcess->id;
        }
        return 0;
    }

    public function getLastCloseCashRegisterId(): int
    {
        $lastProcess = Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
            ->where('processtype_id', 2) //MOVIMIENTOS DE CAJA
            ->where('concept_id', 2) //CIERRE DE CAJA
            ->orderBy('id', 'desc')
            ->first();
        if ($lastProcess) {
            return $lastProcess->id;
        }
        return 0;
    }

    public function getLastProccessCashRegisterId(): int
    {
        $lastProcess = Process::where('business_id', $this->businessId)
            ->where('branch_id', $this->branchId)
            ->where('cashbox_id', $this->cashboxId)
            ->where('processtype_id', 2) //MOVIMIENTOS DE CAJA
            ->orderBy('id', 'desc')
            ->first();
        if ($lastProcess) {
            return $lastProcess->id;
        }
        return 0;
    }

    public function getCashRegisterNumber(): string
    {
        return Process::NextNumberCashRegister(null, $this->businessId, $this->branchId, $this->cashboxId);
    }

    public function storeCashRegister(Request $request): void
    {
        $process = Process::create([
            'number'            => $request->number,
            'date'              => $request->date,
            'concept_id'        => $request->concept_id,
            'amount'            => $request->amount,
            'client_id'         => $request->client_id ?? null,
            'notes'             => $request->notes,
            'cashbox_id'        => $this->cashboxId,
            'branch_id'         => $this->branchId,
            'business_id'       => $this->businessId,
            'processtype_id'    => 2,
            'status'            => 'C',
            'payment_type'      => 'E',
            'user_id'           => auth()->user()->id,
            'amoutreal'         => $request->amountreal ?? null,
        ]);

        $process->save();

        $this->paymentService->savePayments([
            [
                'type' => 'CASH',
                'date' => date('Y-m-d H:i:s'),
                'number' => $request->number,
                'amount' => $request->amount,
                'comment' => $request->notes,
                'process_id' => $process->id,
                'concept_id' => $request->concept_id,
                'branch_id' => $this->branchId,
                'business_id' => $this->businessId,
            ]
        ]);
    }

    public function getCashAmountTotal(): string
    {
        return number_format(Process::TotalAmountCash($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->sum('total'), 2, '.', '');
    }

    public function getTotalIncomes(): string
    {
        return number_format(Process::TotalAmountIncomes($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->sum('amount'), 2, '.', '');
    }

    public function getTotalExpenses(): string
    {
        return number_format(Process::TotalAmountExpenses($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->sum('amount'), 2, '.', '');
    }

    public function getTotalCards(string $type = null, string $subType = null): string
    {
        return number_format(Process::TotalAmountCards($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $type = null, $subType = null)->sum('total'), 2, '.', '');
    }

    public function getTotalDeposits(string $type = null): string
    {
        return number_format(Process::TotalAmountDeposits($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $type = null)->sum('total'), 2, '.', '');
    }

    public function getTotalDigitalWallets(string $type = null): string
    {
        return number_format(Process::TotalAmountDigitalWallets($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $type = null)->sum('total'), 2, '.', '');
    }

    public function getListOfIncomes(): Collection
    {
        return Process::TotalAmountIncomes($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->collect()->map(function ($item) {
            $item->total = number_format($item->amount, 2, '.', '');
            $item->description = $item->concept->name;
            $item->movtype = $item->concept->type;
            $item->client = $item->client->name ?? '';
            $item->comments = $item->notes;
            $item->paymentType = 'Efectivo';
            $item->numberList = 'Mov. Caja: ' . $item->number;
            return $item;
        });
    }

    public function getListOfExpenses(): Collection
    {
        return Process::TotalAmountExpenses($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->collect()->map(function ($item) {
            $item->total = number_format($item->amount, 2, '.', '');
            $item->description = $item->concept->name;
            $item->movtype = $item->concept->type;
            $item->client = $item->client->name ?? '';
            $item->comments = $item->notes;
            $item->paymentType = 'Efectivo';
            $item->numberList = 'Mov. Caja: ' . $item->number;
            return $item;
        });
    }

    public function getListOfCashMovements(): Collection
    {
        return Process::TotalAmountCash($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId)->collect()->map(function ($item) {
            $item->total = number_format($item->total, 2, '.', '');
            $item->description = $item->concept;
            $item->client = $item->client ?? '';
            $item->paymentType = 'Efectivo';
            $item->numberList = $this->getNumberTitle($item->processtype_id) . $item->number;
            return $item;
        });
    }

    public function getListOfCardMovements(string $type = null, string $subType = null): Collection
    {
        return Process::TotalAmountCards($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $type, $subType)->collect()->map(function ($item) {
            $item->total = number_format($item->total, 2, '.', '');
            $item->description = $item->concept;
            $item->client = $item->client ?? '';
            $item->paymentType = 'Tarjeta ' . $item->card . ' - ' . $item->type . ' - ' . $item->pos;
            $item->numberList = $this->getNumberTitle($item->processtype_id) . $item->number;
            return $item;
        });
    }

    public function getListOfDepositMovements(string $bank = null): Collection
    {
        return Process::TotalAmountDeposits($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $bank)->collect()->map(function ($item) {
            $item->total = number_format($item->total, 2, '.', '');
            $item->description = $item->concept;
            $item->client = $item->client ?? '';
            $item->paymentType = 'Depósito ' . $item->bank . ' - Nro. Op: ' . $item->nrooperation;
            $item->numberList = $this->getNumberTitle($item->processtype_id) . $item->number;
            return $item;
        });
    }

    public function getListOfDigitalWalletMovements(string $type = null): Collection
    {
        return Process::TotalAmountDigitalWallets($this->getLastOpenCashRegisterId(), $this->branchId, $this->businessId, $this->cashboxId, $type)->collect()->map(function ($item) {
            $item->total = number_format($item->total, 2, '.', '');
            $item->description = $item->concept;
            $item->client = $item->client ?? '';
            $item->paymentType = $item->digitalwallet;
            $item->numberList = $this->getNumberTitle($item->processtype_id) . $item->number;
            return $item;
        });
    }

    public function getNumberTitle(int $proccesTypeId): string
    {
        if ($proccesTypeId == ProcessType::SELL_ID) {
            return 'Venta Nr.: ';
        } else if ($proccesTypeId == ProcessType::HOTEL_SERVICE_ID) {
            return 'Servicio Nr.: ';
        } else if ($proccesTypeId == ProcessType::PURCHASE_ID) {
            return 'Compra Nr.: ';
        } else if ($proccesTypeId == ProcessType::STOCK_MOVEMENT_ID) {
            return 'Mov. Stock: ';
        } else {
            return 'Mov. Caja: ';
        }
    }

    public function getCardTypes(): Collection
    {
        return Card::all();
    }

    public function getBankTypes(): Collection
    {
        return Bank::all();
    }

    public function getDigitalWalletsTypes(): Collection
    {
        return DigitalWallet::all();
    }

    public function getPosTypes(): Collection
    {
        return Pos::all();
    }
}
