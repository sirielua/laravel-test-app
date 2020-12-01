<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use Modules\Admin\View\DataTables\UserDataTable;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(UserDataTable $dataTable)
    {
        return view('admin::index', ['dataTable' => $dataTable->html()]);
    }
}
