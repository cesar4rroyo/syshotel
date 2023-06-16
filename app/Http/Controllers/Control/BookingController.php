<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Services\BookingService;
use App\Models\Booking;
use App\Models\People;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    protected BookingService $service;
    protected int $businessId;
    protected int $branchId;
    public array $routes;
    public string $entity;
    public string $idForm;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->businessId = session()->get('businessId');
            $this->branchId = session()->get('branchId');
            $this->service = new BookingService($this->businessId, $this->branchId);
            return $next($request);
        });

        $this->routes = [
            'create' => 'booking.create',
            'store' => 'booking.store',
            'client' => 'people.createFast',
            'index' => 'bookings.view',
            'destroy' => 'booking.destroy',
            'cancel' => 'booking.cancel',
        ];

        $this->entity = 'booking';
        $this->idForm = 'form' . $this->entity;
    }

    public function view()
    {
        return view('control.booking.index', [
            'routes' => $this->routes,
            'data' => $this->service->getDataToCalendar([Booking::PENDING_STATUS], [Room::OCCUPIED_STATUS]),
        ]);
    }

    public function index()
    {
        try {
            $data = $this->service->getDataToCalendar([Booking::PENDING_STATUS], [Room::OCCUPIED_STATUS]);
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(BookingRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'dateto' => $request->dateto,
                'datefrom' => $request->datefrom,
                'number' => $request->number ?? null,
                'room_id' => $request->room_id,
                'client_id' => $request->client_id,
                'notes' => $request->notes,
                'amount' => $request->amount,
                'days' => $request->days ?? null,
            ];
            $data = $this->service->storeBooking($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(BookingRequest $request)
    {
        try {
            DB::beginTransaction();
            $data = [
                'dateto' => $request->dateto,
                'datefrom' => $request->datefrom,
                'room_id' => $request->room_id,
                'client_id' => $request->client_id,
                'notes' => $request->notes,
                'amount' => $request->amount,
                'user_id' => $request->user_id ?? null,
                'status' => $request->status,
            ];
            $data = $this->service->storeBooking($data);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Booking $booking)
    {
        try {
            DB::beginTransaction();
            $data = $this->service->deleteBooking($booking);
            DB::commit();
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function rooms(Request $request)
    {
        try {
            $date_from = $request->datefrom;
            $date_to = $request->dateto;
            $data = $this->service->getAvailableRooms($date_from, $date_to);
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request)
    {
        $type = $request->type;
        switch ($type) {
            case 'new':
                $formData = [
                    'route'             => $this->routes['store'],
                    'client_route'      => $this->routes['client'],
                    'index_route'       => $this->routes['index'],
                    'method'            => 'POST',
                    'class'             => 'flex flex-col space-y-3 py-2',
                    'id'                => $this->idForm,
                    'autocomplete'      => 'off',
                    'entidad'           => $this->entity,
                    'listar'            => 'SI',
                    'boton'             => 'Registrar',
                    'day'               => $request->date,
                    'number'            => Booking::NextNumber(date('Y'), $this->branchId, $this->businessId),
                    'model'             => null,
                    'cboClients'        => ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all() + People::PeopleClient()->pluck('name', 'id')->all()
                ];
                return view('control.booking.create')->with(compact('formData'));
                break;
            case 'booking':
                $bookingId = $request->processId;
                $model = Booking::find($bookingId);
                $formData = [
                    'route'             => [$this->routes['destroy'], $bookingId],
                    'url_cancel'        => route($this->routes['destroy'], $bookingId),
                    'client_route'      => $this->routes['client'],
                    'index_route'       => $this->routes['index'],
                    'method'            => 'DELETE',
                    'class'             => 'flex flex-col space-y-3 py-2',
                    'id'                => $this->idForm,
                    'autocomplete'      => 'off',
                    'entidad'           => $this->entity,
                    'listar'            => 'SI',
                    'boton'             => 'Registrar',
                    'day'               => $request->date,
                    'number'            => $model->number,
                    'model'             => $model,
                    'cboClients'        => ['' => 'Seleccione una opción'] + People::Companies()->pluck('social_reason', 'id')->all() + People::PeopleClient()->pluck('name', 'id')->all(),
                    'room'              => $model->room->name . ' - ' . $model->room->number . ' - ' . $model->room->roomType->name .  ' - ' .  $model->room->roomType->price,
                ];
                return view('control.booking.create')->with(compact('formData'));
                break;
            case 'room':
                dd('room');
                # code...
                break;
        }
    }
}
