<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Services\ManagementService;
use App\Librerias\Libreria;
use App\Models\Payments;
use App\Models\People;
use App\Models\Room;
use App\Traits\CRUDTrait;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    use CRUDTrait;

    protected ManagementService $service;
    protected int $businessId;
    protected int $branchId;
    protected string $folderView;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = session()->get('businessId');
            $this->branchId = session()->get('branchId');
            $this->service = new ManagementService($this->businessId, $this->branchId);
            return $next($request);
        });
        $this->folderView = 'control.management.';
        $this->entity = 'rooms';
        $this->routes = [
            'create' => 'management.create',
            'store' => 'management.store',
            'edit' => 'management.edit',
            'update' => 'management.update',
            'destroy' => 'management.destroy',
            'client' => 'people.createFast',
            'back' => 'management',
            'documentType' => 'management.documentNumber',
        ];
    }

    public function index(Request $request)
    {
        return view($this->folderView . 'index', with([
            'floors' => $this->service->getFloorsWithRooms($request->id),
            'entidad' => $this->entity,
            'id' => $request->id,
            'routes' => $this->routes,
        ]));
    }

    public function create(Request $request)
    {
        $room = Room::findOrFail($request->id);
        $formData = [
            'route'             => $this->routes['store'],
            'method'            => 'POST',
            'class'             => 'flex flex-col space-y-3 py-2',
            'id'                => $this->idForm,
            'autocomplete'      => 'off',
            'entidad'           => $this->entity,
            'listar'            => $this->getParam($request->input('listagain'), 'NO'),
            'boton'             => 'Registrar',
            'model'             => null,
            'today'             => date('Y-m-d'),
            'number'            => $this->service->generateCheckInNumber(),
        ];
        return view($this->folderView . 'create', with([
            'entidad' => $this->entity,
            'id' => $request->id,
            'routes' => $this->routes,
            'cboPeople' => ['' => 'Seleccione una opción'] + People::PeopleClient()->pluck('name', 'id')->all(),
            'cboCompanies' => ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all(),
            'cboClients' => ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all() + People::PeopleClient()->pluck('name', 'id')->all(),
            'formData' => $formData,
            'room' => $room,
            'cboPaymentTypes' => $this->generateCboGeneral(Payments::class, 'name', 'id', 'Seleccione una opción'),
            'cboDocumentTypes' => ['' => 'Seleccione una opción'] + ['BOLETA' => 'BOLETA', 'FACTURA' => 'FACTURA', 'TICKET' => 'TICKET'],
        ]));
    }

    public function documentNumber(Request $request)
    {
        $documentNumber = $this->service->generateDocumentNumber($request->type);
        return response()->json(['documentNumber' => $documentNumber]);
    }
}