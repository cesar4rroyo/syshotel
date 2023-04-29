<?php

namespace App\Http\Services\Billing;

use App\Http\Contracts\Billing\BillingContract;
use App\Models\Billing;

class Boleta implements BillingContract
{
    public function getDocumentDataBeforeSend(Billing $billing, array $businessInfo): array
    {
        $document = $this->getDocumentBody($billing, $businessInfo['igv']);
        return $this->generateDataToSend($billing, $document, $businessInfo);
    }

    public function getMethod(): string
    {
        return 'enviarBoleta';
    }

    public function getDocumentBody(Billing $billing, string $igv): array
    {
        return [
            "fechaemision" => date('Y-m-d', strtotime($billing->date)),
            "horaemision" => date('H:i:s', strtotime($billing->date)),
            'numeroboleta' => BillingHelper::getNumerBilling($billing->number),
            'dni' => $billing->client->dni,
            'serieboleta' => explode('-', BillingHelper::getNumerBilling($billing->number))[0],
            'correlativoboleta' => (int) explode('-', BillingHelper::getNumerBilling($billing->number))[1],
            "usuario" => $billing->client->full_name,
            "tipodoc" => BillingHelper::getIdType($billing->type),
            "moneda" => "PEN",
            "codubigeo" => '0000',
            "descuentototal" => 0,
            "motivodescuento" => "02",
            "percepcion" => "",
            "aplicacionpercepcion" => "",
            "documentosanexos" => [],
            "detalles" => BillingHelper::formatDetails($billing->details, $igv),
        ];
    }

    public function generateDataToSend(Billing $billing, array $document, array $businessInfo): array
    {
        $json = [
            'token' => "",
            'serieboleta' => $document['serieboleta'],
            'correlativoboleta' => $document['correlativoboleta'],
            'doc' => "",
            'nombre' => $document['usuario'],
            'direccion' => $billing->client->address,
            'total' => $billing->total,
            'comprobante' => json_encode($document),
            'formapago' => 'C',
            "cuotas" => [],
        ];
        $json = json_encode($json);

        return [
            'ruc' => $businessInfo['ruc'],
            'password' => $businessInfo['password'],
            'json' => $json,
        ];
    }
}
