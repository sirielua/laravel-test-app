<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Participant;
use Modules\Admin\View\DataTables\ParticipantDataTable;

use App\domain\service\Participant\Remove\RemoveCommand;
use App\domain\service\Participant\Remove\RemoveHandler;
use App\domain\service\Participant\ForceConfirmRegistration\ForceConfirmRegistrationCommand;
use App\domain\service\Participant\ForceConfirmRegistration\ForceConfirmRegistrationHandler;
use App\domain\repositories\NotFoundException;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ContestTemplateDataTable $dataTable
     * @return Response
     */
    public function index(Request $request, ParticipantDataTable $dataTable)
    {
        if($request->ajax() && $request->wantsJson()) {
            return $dataTable->ajax();
        } else {
            return view('admin::participants.index', ['dataTable' => $dataTable->html()]);
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
        $model = Participant::find($id) ?: abort(404);
        return view('admin::participants.show')->with('model', $model);
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
            : redirect(route('admin::participants.index'));
    }

    /**
     * Activate the contest template
     *
     * @param $id
     * @param ForceConfirmRegistrationHandler $handler
     * @param Request $request
     * @return Response
     */
    public function forceConfirm($id, ForceConfirmRegistrationHandler $handler, Request $request)
    {
        $handler->handle(new ForceConfirmRegistrationCommand($id));

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::participants.index'));
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
            case 'force-confirm': $commandClass = ForceConfirmRegistrationCommand::class; $handlerClass = ForceConfirmRegistrationHandler::class; break;
            case 'delete': $commandClass = RemoveCommand::class; $handlerClass = RemoveHandler::class; break;
            default: throw new \DomainException('Invalid Action');
        }

        foreach ($validated['selected'] as $id) {
            app($handlerClass)->handle(app($commandClass, ['id' => $id]));
        }

        return $request->wantsJson()
            ? new Response('', 200)
            : redirect(route('admin::participants.index'));
    }
}
