<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Contest;
use Modules\Admin\View\DataTables\ContestTemplateDataTable;
use Modules\Admin\Http\Requests\ModifyContestTemplate;
use Modules\Admin\Http\Requests\BatchUpdate;

use App\domain\service\Contest\Create\CreateCommand;
use App\domain\service\Contest\Create\CreateHandler;
use App\domain\service\Contest\Update\UpdateCommand;
use App\domain\service\Contest\Update\UpdateHandler;
use App\domain\service\Contest\Remove\RemoveCommand;
use App\domain\service\Contest\Remove\RemoveHandler;
use App\domain\service\Contest\Activate\ActivateCommand;
use App\domain\service\Contest\Activate\ActivateHandler;
use App\domain\service\Contest\Deactivate\DeactivateCommand;
use App\domain\service\Contest\Deactivate\DeactivateHandler;
use App\domain\repositories\NotFoundException;

class ContestTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ContestTemplateDataTable $dataTable
     * @return Response
     */
    public function index(Request $request, ContestTemplateDataTable $dataTable)
    {
        if($request->ajax() && $request->wantsJson()) {
            return $dataTable->ajax();
        } else {
            return view('admin::contest-templates.index', ['dataTable' => $dataTable->html()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function show($id)
    {
        $model = Contest::find($id) ?: abort(404);
        return view('admin::contest-templates.show')->with('model', $model);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin::contest-templates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateHandler $handler
     * @param ContestTemplateModifyRequest $request
     * @return Response
     */
    public function store(CreateHandler $handler, ModifyContestTemplate $request)
    {
        $id = $handler->handle(new CreateCommand($request->getDto()));

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect(route('admin::contest-templates.show', (string)$id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param $id
     * @return Response
     */
    public function edit($id)
    {
        $model = Contest::find($id) ?: abort(404);
        return view('admin::contest-templates.edit')->with('model', $model);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     * @param UpdateHandler $handler
     * @param ContestTemplateModifyRequest $request
     * @return Response
     */
    public function update($id, UpdateHandler $handler, ModifyContestTemplate $request)
    {
        try {
            $handler->handle(new UpdateCommand($id, $request->getDto()));
        } catch (NotFoundException $e) {
            abort(404);
        }

        return $request->wantsJson()
            ? new Response('', 201)
            : redirect(route('admin::contest-templates.show', $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @param RemoveHandler $handler
     * @param Request $request
     * @return Response
     */
    public function destroy($id, RemoveHandler $handler, Request $request)
    {
        try {
            $handler->handle(new RemoveCommand($id));
        } catch (NotFoundException $e) {
            abort(404);
        }

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::contest-templates.index'));
    }

    /**
     * Activate the contest template
     *
     * @param $id
     * @param ActivateHandler $handler
     * @param Request $request
     * @return Response
     */
    public function activate($id, ActivateHandler $handler, Request $request)
    {
        $handler->handle(new ActivateCommand($id));

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::contest-templates.index'));
    }

    /**
     * Deactivate the contest template
     *
     * @param $id
     * @param DeactivateHandler $handler
     * @param Request $request
     * @return Response
     */
    public function deactivate($id, DeactivateHandler $handler, Request $request)
    {
        $handler->handle(new DeactivateCommand($id));

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::contest-templates.index'));
    }

    /**
     * Batch update actions such as mass deletion
     *
     * @param Request $request
     * @param UserService $Service
     * @return Response
     */
    public function batchUpdate(BatchUpdate $request)
    {
        $validated = $request->validated();

        switch ($validated['action']) {
            case 'activate': $commandClass = ActivateCommand::class; $handlerClass = ActivateHandler::class; break;
            case 'deactivate': $commandClass = DeactivateCommand::class; $handlerClass = DeactivateHandler::class; break;
            case 'delete': $commandClass = RemoveCommand::class; $handlerClass = RemoveHandler::class; break;
            default: throw new \DomainException('Invalid Action');
        }

        foreach ($validated['selected'] as $id) {
            app($handlerClass)->handle(app($commandClass, ['id' => $id]));
        }

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::contest-templates.index'));
    }
}
