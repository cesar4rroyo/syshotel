<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Process extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'processes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'number',
        'processtype_id',
        'start_date',
        'end_date',
        'status',
        'amount',
        'amountreal',
        'notes',
        'days',
        'client_id',
        'user_id',
        'room_id',
        'branch_id',
        'business_id',
        'payment_type',
        'booking_id',
        'concept_id',
        'cashbox_id',
    ];

    public function getStatusAttribute($status)
    {
        $values = config('constants.processStatus');
        return $values[$status];
    }


    // TO DO: Add a new method to the Process model to get the color of the status
    // public function getPaymentTypeAttribute($payment_type)
    // {
    //     $values = config('constants.paymentType');
    //     return $values[$payment_type];
    // }

    public function client()
    {
        return $this->belongsTo(People::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function processtype()
    {
        return $this->belongsTo(ProcessType::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function concept()
    {
        return $this->belongsTo(Concept::class);
    }

    public function cashbox()
    {
        return $this->belongsTo(Cashbox::class);
    }

    public function payments()
    {
        return $this->belongsToMany(PaymentType::class, 'paymentprocesses', 'process_id', 'payment_id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }

    public function scopeSearch(Builder $query, string $param = null, int $branch_id = null, int $business_id = null, string $status = null, int $cashbox_id = null, array $procesType_id = null, int $lastOpenCashRegister = null, int $lastCashRegisterId = null)
    {
        return $query->when($param, function ($query, $param) {
            return $query->where('number', 'like', "%$param%");
        })->when($branch_id, function ($query, $branch_id) {
            return $query->where('branch_id', $branch_id);
        })->when($business_id, function ($query, $business_id) {
            return $query->where('business_id', $business_id);
        })->when($status, function ($query, $status) {
            return $query->where('status', $status);
        })->when($cashbox_id, function ($query, $cashbox_id) {
            return $query->where('cashbox_id', $cashbox_id);
        })->when($procesType_id, function ($query, $procesType_id) {
            return $query->whereIn('processtype_id', $procesType_id);
        })->when($lastOpenCashRegister, function ($query, $lastOpenCashRegister) {
            return $query->where('id', '>=', $lastOpenCashRegister);
        })->when($lastCashRegisterId, function ($query, $lastCashRegisterId) {
            return $query->where('id', '<=', $lastCashRegisterId);
        })->orderBy('id', 'asc');
    }

    public function scopeNextNumber(Builder $query, $year = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null)
    {
        $year = $year ?? date('Y');
        $rs = $query->where('number', 'like', '%' . $year . '-%')
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->where('processtype_id', 3)
            ->select(DB::raw("max((CASE WHEN number is NULL THEN 0 ELSE convert(substr(number,6,11),SIGNED integer) END)*1) AS maximum"))->first();
        return $year . '-' . str_pad($rs->maximum + 1, 6, '0', STR_PAD_LEFT);
    }

    public function scopeNextNumberCashRegister(Builder $query, $year = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null)
    {
        $year = $year ?? date('Y');
        $rs = $query->where('number', 'like', '%' . $year . '-%')
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->where('processtype_id', 2)
            ->select(DB::raw("max((CASE WHEN number is NULL THEN 0 ELSE convert(substr(number,6,11),SIGNED integer) END)*1) AS maximum"))->first();
        return $year . '-' . str_pad($rs->maximum + 1, 6, '0', STR_PAD_LEFT);
    }

    public function scopeNextNumberCheckIn(Builder $query, $year = null, int $branch_id = null, int $business_id = null)
    {
        $year = $year ?? date('Y');
        $rs = $query->where('number', 'like', '%' . $year . '-%')
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->select(DB::raw("max((CASE WHEN number is NULL THEN 0 ELSE convert(substr(number,6,11),SIGNED integer) END)*1) AS maximum"))->first();
        return $year . '-' . str_pad($rs->maximum + 1, 6, '0', STR_PAD_LEFT);
    }

    public function scopeNextNumberSell(Builder $query, $year = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null)
    {
        $year = $year ?? date('Y');
        $rs = $query->where('number', 'like', '%' . $year . '-%')
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->where('processtype_id', 1)
            ->select(DB::raw("max((CASE WHEN number is NULL THEN 0 ELSE convert(substr(number,6,11),SIGNED integer) END)*1) AS maximum"))->first();
        return $year . '-' . str_pad($rs->maximum + 1, 6, '0', STR_PAD_LEFT);
    }

    public function scopeTotalAmountCashFromOpen(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null)
    {
        return $query->where('branch_id', $branch_id)
            ->where('id', '>=', $lastOpenId)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->where('processtype_id', 2)
            ->where('payment_type', 'E')
            ->sum('amount');
    }

    public function scopeTotalAmountIncomes(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null): Collection
    {
        return $query->where('branch_id', $branch_id)
            ->where('id', '>=', $lastOpenId)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->whereIn('processtype_id', [ProcessType::SELL_ID, ProcessType::CASH_REGISTER_MOVEMENT_ID])
            ->whereHas('concept', function ($query) {
                $query->where('type', 'I');
            })
            ->select('*')->get();
    }

    public function scopeTotalAmountExpenses(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null): Collection
    {
        return $query->where('branch_id', $branch_id)
            ->where('id', '>=', $lastOpenId)
            ->where('business_id', $business_id)
            ->where('cashbox_id', $cashbox_id)
            ->where('processtype_id', 2)
            ->whereHas('concept', function ($query) {
                $query->where('type', 'E');
            })
            ->select('*')->get();
    }

    public function scopeTotalAmountCards(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null, string $type = null, string $subtype = null): Collection
    {
        return DB::table('paymentprocesses')
            ->join('processes', 'paymentprocesses.process_id', '=', 'processes.id')
            ->join('cards', 'paymentprocesses.card_id', '=', 'cards.id')
            ->leftJoin('people', 'processes.client_id', '=', 'people.id')
            ->join('concepts', 'processes.concept_id', '=', 'concepts.id')
            ->join('pos', 'paymentprocesses.pos_id', '=', 'pos.id')
            ->where('processes.branch_id', $branch_id)
            ->where('processes.id', '>=', $lastOpenId)
            ->where('processes.business_id', $business_id)
            ->where('processes.cashbox_id', $cashbox_id)
            ->where('paymentprocesses.payment_id', PaymentType::CARD_ID)
            ->when($type, function ($query, $type) {
                return $query->where('cards.type', $type);
            })
            ->when($subtype, function ($query, $subtype) {
                return $query->where('cards.description', $subtype);
            })
            ->select('processes.*', 'cards.description as card', 'paymentprocesses.amount as total', 'paymentprocesses.comment as comments', 'cards.type as type', 'people.name as client', 'concepts.name as concept', 'processes.processtype_id as processtype', 'pos.description as pos', 'cards.id as card_id', 'pos.id as pos_id')->get();
    }

    public function scopeTotalAmountCash(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null): Collection
    {
        return DB::table('paymentprocesses')
            ->join('paymenttypes', 'paymentprocesses.payment_id', '=', 'paymenttypes.id')
            ->join('processes', 'paymentprocesses.process_id', '=', 'processes.id')
            ->join('concepts', 'processes.concept_id', '=', 'concepts.id')
            ->leftJoin('people', 'processes.client_id', '=', 'people.id')
            ->where('processes.branch_id', $branch_id)
            ->where('processes.id', '>=', $lastOpenId)
            ->where('processes.business_id', $business_id)
            ->where('processes.cashbox_id', $cashbox_id)
            ->where('paymentprocesses.payment_id', PaymentType::CASH_ID)
            ->where('concepts.type', 'I')
            ->select('processes.*', 'paymenttypes.description as payment', 'paymentprocesses.amount as total', 'paymentprocesses.comment as comments', 'people.name as client', 'concepts.name as concept', 'processes.processtype_id as processtype')->get();
    }

    public function scopeTotalAmountDeposits(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null, string $type = null): Collection
    {
        return DB::table('paymentprocesses')
            ->join('processes', 'paymentprocesses.process_id', '=', 'processes.id')
            ->join('banks', 'paymentprocesses.bank_id', '=', 'banks.id')
            ->leftJoin('people', 'processes.client_id', '=', 'people.id')
            ->join('concepts', 'processes.concept_id', '=', 'concepts.id')
            ->where('processes.branch_id', $branch_id)
            ->where('processes.id', '>=', $lastOpenId)
            ->where('processes.business_id', $business_id)
            ->where('processes.cashbox_id', $cashbox_id)
            ->whereIn('paymentprocesses.payment_id', [PaymentType::DEPOSIT_ID, PaymentType::TRANSFER_ID])
            ->when($type, function ($query, $type) {
                return $query->where('banks.description', $type);
            })
            ->select('processes.*', 'banks.description as bank', 'paymentprocesses.amount as total', 'paymentprocesses.comment as comments', 'paymentprocesses.nrooperation as nrooperation', 'paymentprocesses.payment_id as type', 'people.name as client', 'concepts.name as concept', 'processes.processtype_id as processtype', 'banks.id as bank_id')->get();
    }

    public function scopeTotalAmountDigitalWallets(Builder $query, int $lastOpenId = null, int $branch_id = null, int $business_id = null, int $cashbox_id = null, string $type = null): Collection
    {
        return DB::table('paymentprocesses')
            ->join('processes', 'paymentprocesses.process_id', '=', 'processes.id')
            ->join('digitalwallets', 'paymentprocesses.digitalwallet_id', '=', 'digitalwallets.id')
            ->leftJoin('people', 'processes.client_id', '=', 'people.id')
            ->join('concepts', 'processes.concept_id', '=', 'concepts.id')
            ->where('processes.branch_id', $branch_id)
            ->where('processes.id', '>=', $lastOpenId)
            ->where('processes.business_id', $business_id)
            ->where('processes.cashbox_id', $cashbox_id)
            ->whereIn('paymentprocesses.payment_id', [PaymentType::DIGITALWALLET_ID])
            ->when($type, function ($query, $type) {
                return $query->where('digitalwallets.description', $type);
            })->select('processes.*', 'digitalwallets.description as digitalwallet', 'paymentprocesses.amount as total', 'paymentprocesses.comment as comments', 'people.name as client', 'concepts.name as concept', 'processes.processtype_id as processtype', 'digitalwallets.id as digitalwallet_id')->get();
    }
}
