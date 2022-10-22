<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Services\BusinessService;
use App\Librerias\Libreria;
use App\Models\Business;
use App\Traits\CRUDTrait;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BusinessController extends Controller
{
    use CRUDTrait;

    public string $settingsTitle;
    public string $branchesTitle;
    public string $usersTitle;
    public BusinessService $businessService;

    public function __construct()
    {
        $this->model            = new Business();
        $this->businessService  = new BusinessService();

        $this->entity       = 'business';
        $this->folderview   = 'admin.business';
        $this->adminTitle   = __('maintenance.admin.business.title');
        $this->addTitle     = __('maintenance.general.add', ['entity' => $this->adminTitle]);
        $this->updateTitle  = __('maintenance.general.edit', ['entity' => $this->adminTitle]);
        $this->deleteTitle  = __('maintenance.general.delete', ['entity' => $this->adminTitle]);
        $this->settingsTitle = __('maintenance.admin.business.settings');
        $this->branchesTitle = __('maintenance.admin.business.branches');
        $this->usersTitle    = __('maintenance.admin.business.users');
        $this->routes = [
            'search'  => 'business.search',
            'index'   => 'business.index',
            'store'   => 'business.store',
            'delete'  => 'business.delete',
            'create'  => 'business.create',
            'edit'    => 'business.edit',
            'update'  => 'business.update',
            'destroy' => 'business.destroy',
            'maintenance' => 'business.maintenance',
        ];
        $this->idForm       = 'formMantenimiento' . $this->entity;

        $this->clsLibreria = new Libreria();

        $this->headers = [
            [
                'valor'  => 'Nombre',
                'numero' => '1',
            ],
            [
                'valor'  => 'Estado',
                'numero' => '1',
            ],
            [
                'valor'  => 'Email/Teléfono',
                'numero' => '1',
            ],
            [
                'valor'  => 'Dirección',
                'numero' => '1',
            ],
            [
                'valor'  => 'Acciones',
                'numero' => '1',
            ],
        ];
    }

    public function search(Request $request)
    {
        try {
            $paginas = $request->page;
            $filas = $request->filas;
            $nombre      = $this->getParam($request->nombre);

            $result = $this->model::search($nombre);
            $list   = $result->get();

            if (count($list) > 0) {
                $paramPaginacion = $this->clsLibreria->generarPaginacion($list, $paginas, $filas, $this->entity);
                $list = $result->paginate($filas);
                $request->replace(array('page' => $paramPaginacion['nuevapagina']));
                return view($this->folderview . '.list')->with([
                    'lista'             => $list,
                    'cabecera'          => $this->headers,
                    'titulo_admin'      => $this->adminTitle,
                    'titulo_eliminar'   => $this->deleteTitle,
                    'titulo_modificar'  => $this->updateTitle,
                    'paginacion'        => $paramPaginacion['cadenapaginacion'],
                    'inicio'            => $paramPaginacion['inicio'],
                    'fin'               => $paramPaginacion['fin'],
                    'ruta'              => $this->routes,
                    'entidad'           => $this->entity,
                    'settings_title'    => $this->settingsTitle,
                    'branches_title'    => $this->branchesTitle,
                    'users_title'       => $this->usersTitle,
                ]);
            }
            return view($this->folderview . '.list')->with('lista', $list)->with([
                'entidad'           => $this->entity,
                'settings_title'    => $this->settingsTitle,
                'branches_title'    => $this->branchesTitle,
                'users_title'       => $this->usersTitle,
            ]);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function index()
    {
        try {
            return view($this->folderview . '.index')->with([
                'entidad'           => $this->entity,
                'titulo_admin'      => $this->adminTitle,
                'titulo_eliminar'   => $this->deleteTitle,
                'titulo_modificar'  => $this->updateTitle,
                'titulo_registrar'  => $this->addTitle,
                'ruta'              => $this->routes,
                'cboRangeFilas'     => $this->cboRangeFilas(),
            ]);
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function create(Request $request)
    {
        try {
            $formData = [
                'route'             => $this->routes['store'],
                'method'            => 'POST',
                'class'             => 'flex flex-col space-y-3 py-2',
                'id'                => $this->idForm,
                'autocomplete'      => 'off',
                'entidad'           => $this->entity,
                'listar'            => $this->getParam($request->input('listagain'), 'NO'),
                'boton'             => 'Registrar',
                'cboTipoUsuario'    => $this->generateCboGeneral(UserType::class, 'name', 'id', 'Seleccione una opción'),
                'cboPersona'        => $this->generateCboGeneral(People::class, 'fullName', 'id', 'Seleccione una opción'),
            ];
            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function store(Request $request)
    {
        try {

            $error = DB::transaction(function () use ($request) {
                $model = $this->model->create($request->all());
            });
            event(new BinnacleEvent(auth()->user()->id, 'STORE', 'Stored new ' . $this->entity));
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function edit(Request $request, $id)
    {
        try {

            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }

            $formData = [
                'route'             => array($this->routes['update'], $id),
                'method'            => 'PUT',
                'class'             => 'form-horizontal',
                'id'                => $this->idForm,
                'autocomplete'      => 'off',
                'model'             => $this->model->find($id),
                'listar'            => $this->getParam($request->input('listar'), 'NO'),
                'boton'             => 'Modificar',
                'entidad'           => $this->entity,
                'cboTipoUsuario'    => $this->generateCboGeneral(UserType::class, 'name', 'id', 'Seleccione una opción'),
                'cboPersona'        => $this->generateCboGeneral(People::class, 'fullName', 'id', 'Seleccione una opción'),
            ];

            return view($this->folderview . '.create')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $error = DB::transaction(function () use ($request, $id) {
                switch ($request->action) {
                    case 'SETTINGS':
                        $this->businessService->storeOrUpdateBussinessSettings($request, $id);
                        break;
                    case 'BRANCHES':
                        # code...
                        break;
                    case 'USERS':
                        # code...
                        break;
                    case 'PROFILEPHOTO':
                        # code...
                        break;
                    default:
                        # code...
                        break;
                }
            });
            // event(new BinnacleEvent(auth()->user()->id, 'UPDATE', 'Updated ' . $this->entity));
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function delete($id, $listagain)
    {
        try {
            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }
            $listar = 'NO';
            if (!is_null($this->getParam($listagain))) {
                $listar = $listagain;
            }
            $formData = [
                'route'         => array($this->routes['destroy'], $this->model->find($id)),
                'method'        => 'DELETE',
                'class'         => 'form-horizontal',
                'id'            => $this->idForm,
                'autocomplete'  => 'off',
                'boton'         => 'Eliminar',
                'entidad'       => $this->entity,
                'listar'        => $listar,
                'modelo'        => $this->model->find($id),
            ];
            return view('utils.comfirndelete')->with(compact('formData'));
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function maintenance($id, $action)
    {
        try {
            $exist = $this->verificarExistencia($id, $this->entity);
            if ($exist !== true) {
                return $exist;
            }
            $listar = 'SI';
            $formData = [
                'route'         => array($this->routes['update'], $this->model->find($id)),
                'method'        => 'PUT',
                'class'         => 'form-horizontal',
                'id'            => $this->idForm,
                'autocomplete'  => 'off',
                'boton'         => 'Guardar',
                'entidad'       => $this->entity,
                'listar'        => $listar,
                'model'         => $this->model->find($id),
                'action'        => $action,
            ];
            switch ($action) {
                case 'SETTINGS':
                    return view($this->folderview . '.settings')->with(compact('formData'));
                    break;
                case 'BRANCHES':
                    return view($this->folderview . '.branches')->with(compact('formData'));
                    break;
                case 'USERS':
                    return view($this->folderview . '.users')->with(compact('formData'));
                    break;
                case 'PROFILEPHOTO':
                    # code...
                    break;
                default:
                    return view('utils.comfirndelete')->with(compact('formData'));
                    break;
            }
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }

    public function destroy($id)
    {
        try {
            $error = DB::transaction(function () use ($id) {
                $this->model->find($id)->delete();
            });
            event(new BinnacleEvent(auth()->user()->id, 'DELETE', 'Deleted ' . $this->entity));
            return is_null($error) ? "OK" : $error;
        } catch (\Throwable $th) {
            return $this->MessageResponse($th->getMessage(), 'danger');
        }
    }
}