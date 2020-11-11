<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use Modules\Admin\Entities\DataTables\UsersDataTable;
use Modules\Admin\Services\UserService;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @param Request $request
     * @param UsersDataTable $dataTable
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, UsersDataTable $dataTable)
    {
        if($request->ajax() && $request->wantsJson()) {
            return $dataTable->ajax();
        } else {
            return view('admin::users.index', ['dataTable' => $dataTable->html()]);
        }
    }
    
    /**
     * Butch update actions such as mass deletion
     *
     * @param Request $request
     * @param UserService $Service
     * @return \Illuminate\Http\Response
     */
    public function batchUpdate(Request $request, UserService $Service)
    {
        $Service->batchUpdate($request->only('action', 'selected'));
        
        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::users.index'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin::users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param UserService $Service
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, UserService $Service)
    {
        $user = $Service->create($request->all());
        
        return $request->wantsJson()
            ? new Response('', 201)
            : redirect(route('admin::users.show', $user->id));
    }
    
    /**
     * Display the specified resource.
     * 
     * @param UserService $Service
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(UserService $Service, $id)
    {
        $model = $Service->find($id) ?: abort(404);
        return view('admin::users.show')->with('model', $model);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param UserService $Service
     * @param type $id
     * @return \Illuminate\Http\Response
     */
    public function edit(UserService $Service, $id)
    {
        $model = $Service->find($id) ?: abort(404);
        return view('admin::users.edit')->with('model', $model);
    }
    
    /**
     * Update the specified resource in storage.
     * 
     * @param Request $request
     * @param UserService $Service
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserService $Service, $id)
    {
        $model = $Service->find($id) ?: abort(404);
        $Service->update($model->id, $request->all());
        
        return $request->wantsJson()
            ? new Response('', 201)
            : redirect(route('admin::users.show', $model->id));
    }
    
    /**
     * Remove the specified resource from storage.
     * 
     * @param Request $request
     * @param UserService $Service
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, UserService $Service, $id)
    {
        $model = $Service->find($id) ?: abort(404);
        $Service->delete($model->id, $request->all());
        
        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::users.index'));
    }
}
