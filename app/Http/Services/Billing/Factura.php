<?php

namespace App\Http\Services\Billing;

use App\Http\Contracts\Billing\BillingContract;
use App\Models\Billing;

class Factura implements BillingContract
{
    public function getDocumentDataBeforeSend(Billing $billing, array $businessInfo): array
    {
        $document = $this->getDocumentBody($billing, $businessInfo['igv']);
        return $this->generateDataToSend($billing, $document, $businessInfo);
    }

    public function getMethod(): string
    {
        return 'enviarFactura';
    }

    public function getDocumentBody(Billing $billing, string $igv): array
    {
        return [
            "fechaemision" => date('Y-m-d', strtotime($billing->date)),
            "horaemision" => date('H:i:s', strtotime($billing->date)),
            'numerofactura' => BillingHelper::getNumerBilling($billing->number),
            'ruc' => $billing->client->ruc,
            'seriefactura' => explode('-', BillingHelper::getNumerBilling($billing->number))[0],
            'correlativofactura' => (int) explode('-', BillingHelper::getNumerBilling($billing->number))[1],
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
            'seriefactura' => $document['seriefactura'],
            'correlativofactura' => $document['correlativofactura'],
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
            'password' => $businessInfo['password_sunnat'],
            'json' => $json,
        ];
    }
}
