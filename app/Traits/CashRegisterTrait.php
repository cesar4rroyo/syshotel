<?php

namespace App\Traits;

use App\Models\Concept;

trait CashRegisterTrait
{
    private function getTypeById(int $id): string
    {
        $tyes = [
            '1' => 'CASH',
            '2' => 'CARD',
            '3' => 'DIGITALWALLET',
            '4' => 'DEPOSIT',
            '5' => 'TRANSFER'
        ];

        return $tyes[$id];
    }

    private function evaluatePaymentType(array $payments): string
    {
        if (count($payments) == 1) {
            return $payments[0]['type'];
        } else {
            return 'MIXED';
        }
    }

    private function preparePaymentData($data): array
    {
        $types = $data->payment_type_id;
        $amounts = $data->payment_amount;
        $notes = $data->notes;
        $pos = $data->pos;
        $card = $data->card;
        $bank = $data->bank;
        $noperation = $data->noperation;
        $digitalwallet = $data->digitalwallet;

        $payments = [];

        foreach ($types as $key => $type) {
            $payments[] = [
                'type' => $this->getTypeById($type),
                'date' => date('Y-m-d H:i:s'),
                'number' => 'aaa',
                'amount' => $amounts[$key],
                'comment' => $notes[$key] ?? '',
                'concept_id' => Concept::SELL_PRODUCT_OR_SERVICE_ID,
                'branch_id' => session()->get('branchId'),
                'business_id' => session()->get('businessId'),
                'pos_id' => $pos[$key] ?? null,
                'card_id' => $card[$key] ?? null,
                'bank_id' => $bank[$key] ?? null,
                'nrooperation' => $noperation[$key] ?? null,
                'digitalwallet_id' => $digitalwallet[$key] ?? null,
            ];
        }
        return $payments;
    }

    private function prepareProductsData($data): array
    {
        $products = $data->productId;
        $quantities = $data->quantity;
        $prices = $data->price;
        $subtotals = $data->subtotal;

        $productsData = [];

        foreach ($products as $key => $product) {
            $productsData[] = [
                'product_id' => $product,
                'quantity' => $quantities[$key],
                'price' => $prices[$key],
                'subtotal' => $subtotals[$key],
            ];
        }
        return $productsData;
    }

    private function prepareBillingData($data): array
    {
        return [
            'date' => $data->date,
            'number' => $data->documentNumber,
            'type' => $data->document,
            'client_id' => $data->clientBilling,
        ];
    }


    public function evaluateStatus(string|null $toggle): string
    {
        if ($toggle == 'on') {
            return 'PyC';
        } else {
            return 'P';
        }
    }
}
