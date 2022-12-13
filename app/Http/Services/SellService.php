<?php

namespace App\Http\Services;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SellService
{

    protected int $businessId;
    protected int $branchId;
    protected string $type;
    protected Product $product;
    protected Service $service;

    public function __construct(int $businessId, int $branchId, string $type)
    {
        $this->businessId = $businessId;
        $this->branchId = $branchId;
        $this->type = $type;
        $this->product = new Product();
        $this->service = new Service();
    }

    public function getCarts(): Collection
    {
        $sessionName = 'cart_' . $this->type . '_' . $this->branchId . '_' . $this->businessId;
        $cart = session()->get($sessionName) ?? [];
        $cart = collect($cart);
        if ($cart->count() > 0) {
            $cart = $cart->map(function ($item, $key) {
                $item['total'] = $item['quantity'] * $item['price'];
                return $item;
            });
        }
        return $cart;
    }

    public function removeFromSessionCart(int $productId): array
    {
        $sessionName = 'cart_' . $this->type . '_' . $this->branchId . '_' . $this->businessId;
        $cart = session()->get($sessionName);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put($sessionName, $cart);
            return $this->getCarts()->toArray();
        }
        return session()->get($sessionName);
    }

    public function addToSessionCart(Product|Service $product, Request $request): array
    {
        if ($request->has('quantity')) {
            $quantity = $request->quantity;
        }
        $sessionName = 'cart_' . $this->type . '_' . $this->branchId . '_' . $this->businessId;
        $cart = session()->get($sessionName);
        if (!$cart) {
            $cart = [
                $product->id => [
                    "name" => $product->name,
                    "quantity" => 1,
                    "price" => $product->sale_price ?? $product->price,
                ]
            ];
            session()->put($sessionName, $cart);
            return $this->getCarts()->toArray();
        }
        if (isset($cart[$product->id]) && !isset($quantity)) {
            $cart[$product->id]['quantity']++;
            session()->put($sessionName, $cart);
            return $this->getCarts()->toArray();
        } else if (isset($cart[$product->id]) && isset($quantity)) {
            $cart[$product->id]['quantity'] = $quantity;
            session()->put($sessionName, $cart);
            return $this->getCarts()->toArray();
        }
        $cart[$product->id] = [
            "name" => $product->name,
            "quantity" => 1,
            "price" => $product->sale_price ?? $product->price,
        ];
        session()->put($sessionName, $cart);
        return $this->getCarts()->toArray();
    }
}