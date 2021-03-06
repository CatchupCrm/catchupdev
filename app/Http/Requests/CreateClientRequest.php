<?php

namespace App\Http\Requests;

class CreateClientRequest extends ClientRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', ENTITY_CLIENT);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'contacts' => 'valid_contacts',
        ];

        if ($this->user()->company->client_number_counter) {
            $rules['id_number'] = 'unique:clients,id_number,,id,company_id,' . $this->user()->company_id;
        }

        return $rules;
    }
}
