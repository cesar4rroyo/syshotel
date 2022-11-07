<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Services\ManagementService;
use App\Models\Room;
use Illuminate\Http\Request;

class ManagementController extends Controller
{
    protected ManagementService $service;
    protected int $businessId;
    protected int $branchId;
    protected string $folderView;
    protected string $entity;

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
    }

    public function index(Request $request)
    {
        return view($this->folderView . 'index', with([
            'floors' => $this->service->getFloorsWithRooms($request->id),
            'entidad' => $this->entity,
            'id' => $request->id,
        ]));
    }
}