<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Billing extends Model
{
    use SoftDeletes;

    protected $table = 'billings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'date',
        'number',
        'type',
        'status',
        'motivo_anulacion',
        'total',
        'subtotal',
        'igv',
        'notes',
        'client_id',
        'process_id',
        'user_id',
        'branch_id',
        'business_id',
        'billing_id',
    ];


    public function client()
    {
        return $this->belongsTo(People::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class, 'business_id');
    }

    public function process()
    {
        return $this->belongsTo(Process::class, 'process_id');
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class, 'billing_id');
    }

    public function scopeNextNumberDocument(Builder $query, string $type, string $serie, int $branch_id = null, int $business_id = null)
    {
        $rs = $query->where('number', 'like', '%' . $serie . '-%')
            ->where('branch_id', $branch_id)
            ->where('business_id', $business_id)
            ->where('type', $type)
            ->select(DB::raw("max((CASE WHEN number is NULL THEN 0 ELSE convert(substr(number,6,11),SIGNED integer) END)*1) AS maximum"))->first();

        switch ($type) {
            case 'BOLETA':
                $serie = 'B0' . $serie;
                break;
            case 'FACTURA':
                $serie = 'F0' . $serie;
                break;
            case 'NOTA DE CREDITO FACTURA':
                $serie = 'FC' . $serie;
                break;
            case 'NOTA DE CREDITO BOLETA':
                $serie = 'BC' . $serie;
                break;
            default:
                $serie = 'T0' . $serie;
                break;
        }
        return $serie . '-' . str_pad($rs->maximum + 1, 6, '0', STR_PAD_LEFT);
    }
}