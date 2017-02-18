<?php

namespace Modules\Email\Http\ApiControllers;

use App\Http\Controllers\BaseAPIController;
use Modules\Email\Repositories\EmailRepository;
use Modules\Email\Http\Requests\EmailRequest;
use Modules\Email\Http\Requests\CreateEmailRequest;
use Modules\Email\Http\Requests\UpdateEmailRequest;

class EmailApiController extends BaseAPIController
{
    protected $EmailRepo;
    protected $entityType = 'email';

    public function __construct(EmailRepository $emailRepo)
    {
        parent::__construct();

        $this->emailRepo = $emailRepo;
    }

    /**
     * @SWG\Get(
     *   path="/email",
     *   summary="List of email",
     *   tags={"email"},
     *   @SWG\Response(
     *     response=200,
     *     description="A list with email",
     *      @SWG\Schema(type="array", @SWG\Items(ref="#/definitions/Email"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function index()
    {
        $data = $this->emailRepo->all();

        return $this->listResponse($data);
    }

    /**
     * @SWG\Get(
     *   path="/email/{email_id}",
     *   summary="Individual Email",
     *   tags={"email"},
     *   @SWG\Response(
     *     response=200,
     *     description="A single email",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Email"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function show(EmailRequest $request)
    {
        return $this->itemResponse($request->entity());
    }




    /**
     * @SWG\Post(
     *   path="/email",
     *   tags={"email"},
     *   summary="Create a email",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Email")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="New email",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Email"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */
    public function store(CreateEmailRequest $request)
    {
        $email = $this->emailRepo->save($request->input());

        return $this->itemResponse($email);
    }

    /**
     * @SWG\Put(
     *   path="/email/{email_id}",
     *   tags={"email"},
     *   summary="Update a email",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Email")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Update email",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Email"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function update(UpdateEmailRequest $request, $publicId)
    {
        if ($request->action) {
            return $this->handleAction($request);
        }

        $email = $this->emailRepo->save($request->input(), $request->entity());

        return $this->itemResponse($email);
    }


    /**
     * @SWG\Delete(
     *   path="/email/{email_id}",
     *   tags={"email"},
     *   summary="Delete a email",
     *   @SWG\Parameter(
     *     in="body",
     *     name="body",
     *     @SWG\Schema(ref="#/definitions/Email")
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Delete email",
     *      @SWG\Schema(type="object", @SWG\Items(ref="#/definitions/Email"))
     *   ),
     *   @SWG\Response(
     *     response="default",
     *     description="an ""unexpected"" error"
     *   )
     * )
     */

    public function destroy(UpdateEmailRequest $request)
    {
        $email = $request->entity();

        $this->emailRepo->delete($email);

        return $this->itemResponse($email);
    }

}
