<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Services\SellService;
use App\Models\Service;
use App\Traits\CRUDTrait;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;

class SellServiceController extends Controller
{
    use CRUDTrait;

    protected SellService $sellService;
    protected int $businessId;
    protected int $branchId;
    protected string $folderView;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = session()->get('businessId');
            $this->branchId = session()->get('branchId');
            $this->cashboxId = $request->session()->get('cashboxId');
            $this->sellService = new SellService($this->businessId, $this->branchId, 'service');
            return $next($request);
        });
        $this->folderView = 'control.sell.product.';
        $this->routes = [];
    }

    public function index(): View
    {
        $services = Service::search(null, $this->branchId, $this->businessId)->get();
        $cartServices = $this->sellService->getCarts();
        return view('control.sell.service.index', compact('services', 'cartServices'));
    }

    public function addToCart(int $service, Request $request): JsonResponse
    {
        try {
            $service = Service::findOrfail($service);
            $cart =  $this->sellService->addToSessionCart($service, $request);
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'service' => $service,
                'message' => 'Servicio agregado al carrito'
            ], 200);
        } catch (\Exception $e) {
            app('log')->error($e);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al agregar servicio al carrito'
            ], 500);
        }
    }

    public function removeFromCart(int $service): JsonResponse
    {
        try {
            $cart =  $this->sellService->removeFromSessionCart($service);
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'message' => 'Servicio eliminado del carrito'
            ], 200);
        } catch (\Exception $e) {
            app('log')->error($e);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar servicio del carrito'
            ], 500);
        }
    }
}