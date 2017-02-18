<?php

namespace Modules\Email\Http\Controllers;

use Auth;
use App\Http\Controllers\BaseController;
use App\Services\DatatableService;
use Modules\Email\Datatables\EmailDatatable;
use Modules\Email\Repositories\EmailRepository;
use Modules\Email\Http\Requests\EmailRequest;
use Modules\Email\Http\Requests\CreateEmailRequest;
use Modules\Email\Http\Requests\UpdateEmailRequest;

class EmailController extends BaseController
{
    protected $EmailRepo;
    //protected $entityType = 'email';

    public function __construct(EmailRepository $emailRepo)
    {
        //parent::__construct();

        $this->emailRepo = $emailRepo;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('list_wrapper', [
            'entityType' => 'email',
            'datatable' => new EmailDatatable(),
            'title' => mtrans('email', 'email_list'),
        ]);
    }

    public function datatable(DatatableService $datatableService)
    {
        $search = request()->input('test');
        $userId = Auth::user()->filterId();

        $datatable = new EmailDatatable();
        $query = $this->emailRepo->find($search, $userId);

        return $datatableService->createDatatable($datatable, $query);
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(EmailRequest $request)
    {
        $data = [
            'email' => null,
            'method' => 'POST',
            'url' => 'email',
            'title' => mtrans('email', 'new_email'),
        ];

        return view('email::edit', $data);
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CreateEmailRequest $request)
    {
        $email = $this->emailRepo->save($request->input());

        return redirect()->to($email->present()->editUrl)
            ->with('message', mtrans('email', 'created_email'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(EmailRequest $request)
    {
        $email = $request->entity();

        $data = [
            'email' => $email,
            'method' => 'PUT',
            'url' => 'email/' . $email->public_id,
            'title' => mtrans('email', 'edit_email'),
        ];

        return view('email::edit', $data);
    }

    /**
     * Show the form for editing a resource.
     * @return Response
     */
    public function show(EmailRequest $request)
    {
        return redirect()->to("email/{$request->email}/edit");
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(UpdateEmailRequest $request)
    {
        $email = $this->emailRepo->save($request->input(), $request->entity());

        return redirect()->to($email->present()->editUrl)
            ->with('message', mtrans('email', 'updated_email'));
    }

    /**
     * Update multiple resources
     */
    public function bulk()
    {
        $action = request()->input('action');
        $ids = request()->input('public_id') ?: request()->input('ids');
        $count = $this->emailRepo->bulk($ids, $action);

        return redirect()->to('email')
            ->with('message', mtrans('email', $action . '_email_complete'));
    }
}
