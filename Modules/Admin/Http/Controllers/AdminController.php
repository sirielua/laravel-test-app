<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Modules\Admin\Entities\DataTables\UsersDataTable;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request, UsersDataTable $dataTable)
    {
        return view('admin::index', ['dataTable' => $dataTable->html()]);
    }
}
