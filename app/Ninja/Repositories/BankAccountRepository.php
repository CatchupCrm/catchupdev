<?php

namespace App\Ninja\Repositories;

use App\Models\BankAccount;
use App\Models\BankSubaccount;
use Crypt;
use DB;

class BankAccountRepository extends BaseRepository
{
    public function getClassName()
    {
        return 'App\Models\BankAccount';
    }

    public function find($companyId)
    {
        return DB::table('bank_companies')
                    ->join('banks', 'banks.id', '=', 'bank_companies.bank_id')
                    ->where('bank_companies.deleted_at', '=', null)
                    ->where('bank_companies.company_id', '=', $companyId)
                    ->select(
                        'bank_companies.public_id',
                        'banks.name as bank_name',
                        'bank_companies.deleted_at',
                        'banks.bank_library_id'
                    );
    }

    public function save($input)
    {
        $bankAccount = BankAccount::createNew();
        $bankAccount->bank_id = $input['bank_id'];
        $bankAccount->username = Crypt::encrypt(trim($input['bank_username']));

        $company = \Auth::user()->company;
        $company->bank_companies()->save($bankAccount);

        foreach ($input['bank_companies'] as $data) {
            if (! isset($data['include']) || ! filter_var($data['include'], FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            $subcompany = BankSubaccount::createNew();
            $subcompany->company_name = trim($data['company_name']);
            $subcompany->company_number = trim($data['hashed_company_number']);
            $bankAccount->bank_subaccounts()->save($subcompany);
        }

        return $bankAccount;
    }
}
