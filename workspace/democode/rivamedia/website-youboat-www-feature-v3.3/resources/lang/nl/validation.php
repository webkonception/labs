<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    'accepted'             => 'Het veld :attribute moet geaccepteerd worden.',
    'active_url'           => "Het veld :attribute is geen valide URL.",
    'after'                => 'Het veld :attribute moet een datum voor :datum zijn',
    'alpha'                => 'Het veld :attribute mag alleen maar letters kennen.',
    'alpha_dash'           => 'Het veld :attribute mag alleen maar letters, cijfers en streepjes kennen.',
    'alpha_num'            => 'Het veld :attribute mag alleen maar letters en cijfers kennen',
    'array'                => 'Het veld :attribute moet een bord zijn.',
    'before'               => 'Het veld :attribute moet een datum na :datum zijn.',
    'between'              => [
        'numeric' => 'De waarde van :attribute moet tussen :min et :max. zijn',
        'file'    => 'Het bestand :attribute moet een grootte hebben tussen :min et :max kilo-octets.',
        'string'  => 'De tekst :attribute moet tussen :min et :max caracters hebben.',
        'array'   => 'Het bord :attribute moet tussen:min et :max elementen kennen.',
    ],
    'boolean'              => 'Het veld :attribute moet goed of fout zijn.',
    'confirmed'            => 'Het veld van confirmatie :attribute is niet juist.',
    'date'                 => "Het veld :attribute is geen gelidge datum.",
    'date_format'          => 'Het veld :attribute is niet in het juiste formaat :formaat.',
    'different'            => 'De velden :attribute en :other moeten verschillend zijn.',
    'digits'               => 'Het veld :attribute moet :digits cijfers.',
    'digits_between'       => 'Het veld :attribute moet tussen :min et :max cijfers.',
    'distinct'             => 'Het veld :attribute geeft een gedupliceerde waarde.',
    'email'                => 'Het veld :attribute moet een geldig e-mail adres zijn.',
    'exists'               => 'Het veld :attribute geselectionneerd is niet juist.',
    'filled'               => 'Het veld :attribute is verplicht.',
    'image'                => 'Het veld :attribute moet een foto zijn.',
    'in'                   => 'Het veld :attribute is niet juist.',
    'in_array'             => 'Het veld :attribute bestaat niet in :other.',
    'integer'              => 'Het veld :attribute moet een geheel zijn.',
    'ip'                   => 'Het veld :attribute moet een juist IP adres zijn.',
    'json'                 => 'Het veld :attribute moet een geldig JSON bestand zijn.',
    'max'                  => [
        'numeric' => 'De waarde van :attribute moet groter zijn dan :max.',
        'file'    => 'het bestand :attribute mag niet groter zijn als :max kilo-octets.',
        'string'  => 'De tekst van:attribute mag niet meer als :max caracters kennen.',
        'array'   => 'Het bord :attribute mag niet meer als:max elementen kennen.',
    ],
    'mimes'                => 'Het veld :attribute moet een bestand zijn type : :values.',
    'min'                  => [
        'numeric' => 'La valeur de :attribute moet groter zijn als :min.',
        'file'    => 'Het bestand :attribute moet groter zijn als :min kilo-octets.',
        'string'  => 'Le texte :attribute moet :min caracters kennen',
        'array'   => 'Het bord :attribute moet tenminste :min elementen kennen',
    ],
    'not_in'               => "Het veld :attribute geselectioneerd is nie juist.",
    'numeric'              => 'Het veld :attribute moet een cijfer kennen.',
    'present'              => 'Het veld :attribute moet aanwezig zijn.',
    'regex'                => 'Het veld formaat :attribute is niet juist.',
    'required'             => 'Het veld :attribute is verplicht.',
    'required_if'          => 'Het veld :attribute is verplicht wanneer de waarde van:other is :value.',
    'required_unless'      => 'Het veld :attribute is verplicht behalve als :other is :values.',
    'required_with'        => 'Het veld :attribute is verplicht wanneer :values aanwezig is.',
    'required_with_all'    => 'Het veld :attribute is verplicht wanneer :values aanwezig is.',
    'required_without'     => "Het veld :attribute is verplicht wanneer :values is niet aanwezig.",
    'required_without_all' => "Het veld :attribute is nodig wanneer er geen enkele :values aanwezig zijn.",
    'same'                 => 'De velden :attribute en :other moeten hetzelfde zijn.',
    'size'                 => [
        'numeric' => 'De waarde van :attribute moet.',
        'file'    => 'De grootte van het bestand :attribute moet:size kilo-octets.',
        'string'  => 'De tekst van :attribute moet :size caracters.',
        'array'   => 'Het bord :attribute moet :grootte items.',
    ],
    'string'               => 'Het veld moet een tekenreeks zijn.',
    'timezone'             => 'Het veld moet een geldige tijdzone zijn',
    'unique'               => 'De waarde van het veld is al gebruikt.',
    'url'                  => "Het URL formaar is niet correct.",

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'aangepast bericht',
        ],

        'g-recaptcha-response' => [
            //'required' => 'Wij moeten het veld captcha nakijken !',
            'required' => 'Het vakje moet aangekruist worden voor captcha!',
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    /*
     * Recaptcha
     */
    "recaptcha" => 'Bent u geen robot?',

    'attributes'           => [
        'remember_me'           => 'mij herinneren',
        'name'                  => 'naam',
        'username'              => 'pseudo',
        'email'                 => 'email',
        'first_name'            => 'voornaam',
        'last_name'             => 'achternaam',
        'denomination'          => 'bedrijfsnaam',
        'password'              => 'wachtwoord',
        'password_confirmation' => 'herhaal wachtwoord',
        'country'               => 'land',
        'province'              => 'provincie',
        'region'                => 'regie',
        'subregion'             => 'onder-regie',
        'district'              => 'wijk',
        'county'                => 'afdeling',
        'borough'               => 'gemeente',
        'city'                  => 'stad',
        'zip'                   => 'postcode',
        'address'               => 'adres',
        'address_more'          => 'meer adres',
        'fax'                   => 'fax',
        'phone'                 => 'telefoon',
        'mobile'                => 'gsm',
        'age'                   => 'leeftijd',
        'sex'                   => 'sex',
        'gender'                => 'geslacht',
        'day'                   => 'dag',
        'month'                 => 'maand',
        'year'                  => 'jaar',
        'hour'                  => 'uur',
        'minute'                => 'minuut',
        'second'                => 'seconde',
        'title'                 => 'titel',
        'content'               => 'inhoud',
        'description'           => 'beschrijving',
        'specifications'       => 'Specificaties',
        'features'              => 'Kenmerken',
        'excerpt'               => 'uittreksel',
        'date'                  => 'datum',
        'time'                  => 'uur',
        'available'             => 'beschikbaar',
        'size'                  => 'grootte',
        'message'               => 'bericht',
        'comment'               => 'opmerking',
        'comments'              => 'opmerkingen',
        'picture'               => 'foto',
        'photo'                 => 'foto',
        'url'                   => 'url',

        'ci_email'              => 'email',
        'ci_firstname'         => 'voornaam',
        'ci_last_name'          => 'achternom',
        'ci_password'           => 'wachtwoord',
        'ci_phone'              => 'telefoon',

        'adstypes_id'           => 'advertentie type',
        'recovery_adstypes_id'  => 'overname advertentie type',

        'caracts_labels'        => 'kenmerk titel',
        'caracts_values'        => 'kenmerk waarde',
        'caracts_categories'    => 'kenmerk categories',
    ],

];
