<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellRequest;
use App\Http\Services\SellService;
use App\Models\Payments;
use App\Models\People;
use App\Models\Process;
use App\Models\Product;
use App\Traits\CRUDTrait;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SellProductController extends Controller
{
    use CRUDTrait;

    protected SellService $sellService;
    protected int $businessId;
    protected int $branchId;
    protected string $folderView;
    protected Process $process;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = session()->get('businessId');
            $this->branchId = session()->get('branchId');
            $this->cashboxId = $request->session()->get('cashboxId');
            $this->sellService = new SellService($this->businessId, $this->branchId, 'product');
            return $next($request);
        });
        $this->folderView = 'control.sell.product.';
        $this->routes = [
            'documentType' => 'management.documentNumber',
            'client' => 'people.createFast',
            'cashregister' => 'cashregister',
        ];
        $this->process = new Process();
    }

    public function index(): View
    {
        $products = Product::search(null, $this->branchId, $this->businessId)->get();
        $cartProducts = $this->sellService->getCarts();
        $cboPaymentTypes = $this->generateCboGeneral(Payments::class, 'name', 'id', 'Seleccione una opción');
        $cboDocumentTypes = ['' => 'Seleccione una opción'] + ['BOLETA' => 'BOLETA', 'FACTURA' => 'FACTURA', 'TICKET' => 'TICKET'];
        $cboPeople =  ['' => 'Seleccione una opción'] + People::PeopleClient()->pluck('name', 'id')->all();
        $cboCompanies = ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all();
        $cboClients = ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all() + People::PeopleClient()->pluck('name', 'id')->all();
        $number = $this->sellService->generateNextSellNumber($this->cashboxId);
        return view('control.sell.product.index', with([
            'products' => $products,
            'cartProducts' => $cartProducts,
            'cboPaymentTypes' => $cboPaymentTypes,
            'cboDocumentTypes' => $cboDocumentTypes,
            'cboPeople' => $cboPeople,
            'cboCompanies' => $cboCompanies,
            'cboClients' => $cboClients,
            'routes' => $this->routes,
            'number' => $number,
        ]));
    }

    public function addToCart(int $product, Request $request): JsonResponse
    {
        try {
            $product = Product::findOrfail($product);
            $cart =  $this->sellService->addToSessionCart($product, $request);
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'product' => $product,
                'message' => 'Producto agregado al carrito'
            ], 200);
        } catch (\Exception $e) {
            app('log')->error($e);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al agregar producto al carrito'
            ], 500);
        }
    }

    public function removeFromCart(int $product): JsonResponse
    {
        try {
            $cart =  $this->sellService->removeFromSessionCart($product);
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'message' => 'Producto eliminado del carrito'
            ], 200);
        } catch (\Exception $e) {
            app('log')->error($e);
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar producto del carrito'
            ], 500);
        }
    }

    public function store(SellRequest $request)
    {
        try {
            DB::beginTransaction();
            $process = $this->process->create($request->all());
            $data = $this->sellService->formatData($request->all());
            $this->sellService->createPaymentAndBilling($process, $data, 'product');
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Registro creado correctamente',
            ]);
        } catch (\Exception $e) {
            app('log')->error($e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Error al eliminar producto del carrito'
            ], 500);
        }
    }
}