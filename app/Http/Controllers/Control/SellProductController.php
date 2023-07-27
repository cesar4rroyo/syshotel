<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\SellRequest;
use App\Http\Services\CashRegisterService;
use App\Http\Services\Payment\PaymentService;
use App\Http\Services\SellService;
use App\Models\Bank;
use App\Models\Card;
use App\Models\DigitalWallet;
use App\Models\PaymentType;
use App\Models\People;
use App\Models\Pos;
use App\Models\Process;
use App\Models\Product;
use App\Models\StockProduct;
use App\Traits\CRUDTrait;
use Exception;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class SellProductController extends Controller
{
    use CRUDTrait;

    protected SellService $sellService;
    protected CashRegisterService $cashRegisterService;
    protected int $businessId;
    protected int $branchId;
    protected int $cashboxId;
    protected string $folderView;
    protected Process $process;
    protected PaymentService $paymentService;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = session()->get('businessId');
            $this->branchId = session()->get('branchId');
            $this->cashboxId = $request->session()->get('cashboxId');
            $this->sellService = new SellService($this->businessId, $this->branchId, 'product');
            $this->cashRegisterService = new CashRegisterService($this->businessId, $this->branchId, $this->cashboxId);
            $this->paymentService = new PaymentService();
            return $next($request);
        });
        $this->folderView = 'control.sell.product.';
        $this->routes = [
            'index' => 'sellproduct',
            'documentType' => 'management.documentNumber',
            'client' => 'people.createFast',
            'cashregister' => 'cashregister',
            'print'   => 'billinglist.print',
        ];
        $this->process = new Process();
    }

    public function index(): View
    {
        $products = StockProduct::search(null, $this->branchId, $this->businessId)->get();
        $cartProducts = $this->sellService->getCarts();
        $cboPaymentTypes = $this->generateCboGeneral(PaymentType::class, 'description', 'id', 'Seleccione una opción');
        $cboDocumentTypes = ['' => 'Seleccione una opción'] + $this->sellService->getDocumentTypes();
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
            'paymentRoute' => 'sellproduct.create.payment',
        ]));
    }

    public function addToCart(int $product, Request $request): JsonResponse
    {
        $status = $this->cashRegisterService->getStatus();
        if ($status == 'close') {
            return response()->json([
                'success' => false,
                'error' => 'Caja cerrada',
                'message' => 'La caja se encuentra cerrada, aperturela para realizar ventas'
            ], 500);
        }
        try {
            $product = StockProduct::findOrfail($product);
            $cart =  $this->sellService->addToSessionCart($product->product, $request, $product->sale_price);
            return response()->json([
                'success' => true,
                'cart' => $cart,
                'product' => $product->product,
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
        $status = $this->cashRegisterService->getStatus();
        if ($status == 'close') {
            return response()->json([
                'success' => false,
                'error' => 'Caja cerrada',
                'message' => 'La caja se encuentra cerrada, aperturela para realizar ventas'
            ], 500);
        }
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
            $billing = $this->sellService->createBilling($process, $request->billing, $request->products, 'product');
            $this->sellService->createProcessDetails($request->products, $process, 'product');
            $this->paymentService->savePayments($request->payments, $process->id);
            $this->sellService->clearSessionCart();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Registro creado correctamente',
                'url' => URL::route($this->routes['print'], ['type' => 'TICKET', 'id' => $billing->id]),
            ]);
        } catch (Exception $e) {
            app('log')->error($e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Ha ocurrido un error al generar el pago!'
            ], 500);
        }
    }

    public function payment(Request $request)
    {
        return view('control.sell.payments.create', with([
            'cboPaymentTypes' => PaymentType::pluck('description', 'id')->all(),
            'pos' => Pos::pluck('description', 'id')->all(),
            'banks' => Bank::pluck('description', 'id')->all(),
            'wallets' => DigitalWallet::pluck('description', 'id')->all(),
            'cards' => Card::all()->each(function ($card) {
                $card->description = $card->type . ' - ' . $card->description;
            })->pluck('description', 'id')->all(),
        ]));
    }
}