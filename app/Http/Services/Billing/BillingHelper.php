<?php


namespace App\Http\Services\Billing;



class BillingHelper
{
    public static function getIdType(string $type): int
    {
        switch ($type) {
            case 'BOLETA':
                return 1;
            case 'FACTURA':
                return 6;
            default:
                return 0;
        }
    }

    public static function formatDetails(mixed $details, string $igv = "10"): array
    {
        $data = [];
        foreach ($details as $item) {
            $data[] = [
                'tipodetalle' => "V",
                'codigo' => "-",
                'unidadmedida' => "NIU",
                'cantidad' => $item->amount,
                'descripcion' => $item->product->name ?? $item->service->name,
                'precioventaunitarioxitem' => $item->purchase_price,
                'descuentoxitem' => 0,
                'tipoigv' => $igv,
                'tasaisc' => "0",
                'aplicacionisc' => "",
                'precioventasugeridoxitem' => ""
            ];
        }
        return $data;
    }


    public static function getNumerBilling(string $number): string
    {
        $data = explode("-", $number);
        $serie = substr($data[0], 1);
        $correlative = $data[1];
        return $serie . '-' . $correlative;
    }
}
