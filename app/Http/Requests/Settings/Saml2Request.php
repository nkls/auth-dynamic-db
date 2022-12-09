<?php

namespace App\Http\Requests\Settings;

use App\Http\Requests\Traits\FailedValidation;
use App\Models\Organisation\Tenant;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Saml2Request extends FormRequest
{

    use FailedValidation;

    protected const NAME_ID_FORMATS = [
        'persistent',
        'transient',
        'emailAddress',
        'unspecified',
        'X509SubjectName',
        'WindowsDomainQualifiedName',
        'kerberos',
        'entity'
    ];

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'key' => [
                'required',
                Rule::unique((new Tenant)->getTable())->ignore($this->route('key')),
                'max:255',
            ],
            'idp_entity_id' => ['required', 'max:255'],
            'idp_login_url' => ['required', 'max:255'],
            'idp_logout_url' => ['required', 'max:255'],
            'idp_x509_cert' => ['required'],
            'name_id_format' => [Rule::in(static::NAME_ID_FORMATS)],
            'metadata' => [],
        ];
    }
}
