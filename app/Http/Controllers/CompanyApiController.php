<?php

namespace App\Http\Controllers;

use App\Events\UserSignedUp;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Ninja\Repositories\CompanyRepository;
use App\Ninja\Transformers\CompanyTransformer;
use App\Ninja\Transformers\UserCompanyTransformer;
use App\Services\AuthService;
use Auth;
use Cache;
use Exception;
use Illuminate\Http\Request;
use Response;
use Socialite;
use Utils;

class CompanyApiController extends BaseAPIController
{
    protected $companyRepo;

    public function __construct(CompanyRepository $companyRepo)
    {
        parent::__construct();

        $this->companyRepo = $companyRepo;
    }

    public function ping(Request $request)
    {
        $headers = Utils::getApiHeaders();

        return Response::make(RESULT_SUCCESS, 200, $headers);
    }

    public function register(RegisterRequest $request)
    {
        $company = $this->companyRepo->create($request->first_name, $request->last_name, $request->email, $request->password);
        $user = $company->users()->first();

        Auth::login($user, true);
        event(new UserSignedUp());

        return $this->processLogin($request);
    }

    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->processLogin($request);
        } else {
            sleep(ERROR_DELAY);

            return $this->errorResponse(['message' => 'Invalid credentials'], 401);
        }
    }

    private function processLogin(Request $request)
    {
        // Create a new token only if one does not already exist
        $user = Auth::user();
        $this->companyRepo->createTokens($user, $request->token_name);

        $users = $this->companyRepo->findUsers($user, 'company.company_tokens');
        $transformer = new UserCompanyTransformer($user->company, $request->serializer, $request->token_name);
        $data = $this->createCollection($users, $transformer, 'user_company');

        return $this->response($data);
    }

    public function show(Request $request)
    {
        $company = Auth::user()->company;
        $updatedAt = $request->updated_at ? date('Y-m-d H:i:s', $request->updated_at) : false;

        $transformer = new CompanyTransformer(null, $request->serializer);
        $company->load(array_merge($transformer->getDefaultIncludes(), ['projects.client']));
        $company = $this->createItem($company, $transformer, 'company');

        return $this->response($company);
    }

    public function getStaticData()
    {
        $data = [];

        $cachedTables = unserialize(CACHED_TABLES);
        foreach ($cachedTables as $name => $class) {
            $data[$name] = Cache::get($name);
        }

        return $this->response($data);
    }

    public function getUserCompanies(Request $request)
    {
        return $this->processLogin($request);
    }

    public function update(UpdateCompanyRequest $request)
    {
        $company = Auth::user()->company;
        $this->companyRepo->save($request->input(), $company);

        $transformer = new CompanyTransformer(null, $request->serializer);
        $company = $this->createItem($company, $transformer, 'company');

        return $this->response($company);
    }

    public function addDeviceToken(Request $request)
    {
        $company = Auth::user()->company;

        //scan if this user has a token already registered (tokens can change, so we need to use the users email as key)
        $devices = json_decode($company->devices, true);

        for ($x = 0; $x < count($devices); $x++) {
            if ($devices[$x]['email'] == Auth::user()->username) {
                $devices[$x]['token'] = $request->token; //update
                $company->devices = json_encode($devices);
                $company->save();
                $devices[$x]['company_key'] = $company->company_key;

                return $this->response($devices[$x]);
            }
        }

        //User does not have a device, create new record

        $newDevice = [
            'token' => $request->token,
            'email' => $request->email,
            'device' => $request->device,
            'company_key' => $company->company_key,
            'notify_sent' => true,
            'notify_viewed' => true,
            'notify_approved' => true,
            'notify_paid' => true,
        ];

        $devices[] = $newDevice;
        $company->devices = json_encode($devices);
        $company->save();

        return $this->response($newDevice);
    }

    public function updatePushNotifications(Request $request)
    {
        $company = Auth::user()->company;

        $devices = json_decode($company->devices, true);

        if (count($devices) < 1) {
            return $this->errorResponse(['message' => 'No registered devices.'], 400);
        }

        for ($x = 0; $x < count($devices); $x++) {
            if ($devices[$x]['email'] == Auth::user()->username) {
                $newDevice = [
                    'token' => $devices[$x]['token'],
                    'email' => $devices[$x]['email'],
                    'device' => $devices[$x]['device'],
                    'company_key' => $company->company_key,
                    'notify_sent' => $request->notify_sent,
                    'notify_viewed' => $request->notify_viewed,
                    'notify_approved' => $request->notify_approved,
                    'notify_paid' => $request->notify_paid,
                ];

                $devices[$x] = $newDevice;
                $company->devices = json_encode($devices);
                $company->save();

                return $this->response($newDevice);
            }
        }
    }

    public function oauthLogin(Request $request)
    {
        $user = false;
        $token = $request->input('token');
        $provider = $request->input('provider');

        try {
            $user = Socialite::driver($provider)->stateless()->userFromToken($token);
        } catch (Exception $exception) {
            return $this->errorResponse(['message' => $exception->getMessage()], 401);
        }

        if ($user) {
            $providerId = AuthService::getProviderId($provider);
            $user = $this->companyRepo->findUserByOauth($providerId, $user->id);
        }

        if ($user) {
            Auth::login($user);

            return $this->processLogin($request);
        } else {
            sleep(ERROR_DELAY);

            return $this->errorResponse(['message' => 'Invalid credentials'], 401);
        }
    }
}
