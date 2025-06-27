<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'supplier_name' => [
                'required',
                'string',
                'max:255',
                'unique:suppliers,supplier_name',
                'regex:/^[\pL\s\-]+$/u'
            ],
            'contact_number' => [
                'required',
                'regex:/^0[1-9][0-9]{7,8}$/'
            ],
            'is_active' => 'required|boolean',
            'note' => 'nullable|string',
            'email' => [
                'required',
                'email', // Use Laravel's default email validation (removes rfc,dns for better compatibility)
                'max:255'
            ],
            'street' => 'required|string|max:100',
            'house_number' => 'required|string|max:4',
            'addition' => 'nullable|string|max:5',
            'postcode' => [
                'required',
                'regex:/^[1-9][0-9]{3}[A-Z]{2}$/'
            ],
            'city' => 'required|string|max:30',
            'mobile' => [
                'required',
                'regex:/^0(6)[0-9]{8}$/'
            ],
        ];
    }

    public function messages()
    {
        return [
            'supplier_name.required' => 'De naam van de leverancier is verplicht.',
            'supplier_name.unique' => 'Deze leverancier bestaat al.',
            'supplier_name.max' => 'De naam van de leverancier mag maximaal 255 tekens zijn.',
            'supplier_name.regex' => 'De naam van de leverancier mag alleen letters, spaties en streepjes bevatten.',
            'contact_number.regex' => 'Het contactnummer moet een geldig Nederlands vast nummer zijn (bijv. 0101234567 of 02012345678).',
            'contact_number.max' => 'Het contactnummer mag maximaal 255 tekens zijn.',
            'is_active.required' => 'Geef aan of de leverancier actief is.',
            'is_active.boolean' => 'Ongeldige waarde voor actief.',
            'note.max' => 'De notitie mag niet langer zijn dan toegestaan.',
            'email.email' => 'Vul een geldig e-mailadres in.',
            'email.max' => 'Het e-mailadres mag maximaal 255 tekens zijn.',
            'street.max' => 'De straatnaam mag maximaal 100 tekens zijn.',
            'house_number.max' => 'Het huisnummer mag maximaal 4 tekens zijn.',
            'addition.max' => 'De toevoeging mag maximaal 5 tekens zijn.',
            'postcode.regex' => 'De postcode moet bestaan uit 4 cijfers gevolgd door 2 hoofdletters, zonder spatie (bijv. 1234AB).',
            'postcode.max' => 'De postcode mag maximaal 6 tekens zijn.',
            'city.max' => 'De plaatsnaam mag maximaal 30 tekens zijn.',
            'mobile.regex' => 'Het mobiele nummer moet een geldig Nederlands mobiel nummer zijn (bijv. 0612345678).',
            'mobile.max' => 'Het mobiele nummer mag maximaal 10 tekens zijn.',
        ];
    }
}
