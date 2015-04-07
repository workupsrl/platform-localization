<?php

namespace Stevebauman\Localization\Validator\Locale;

use Cartalyst\Support\Validator;

class LocaleValidator extends Validator implements LocaleValidatorInterface
{

    /**
     * {@inheritDoc}
     */
    protected $rules = [
        'code' => 'required|max:2',
        'name' => 'required',
        'display_name' => '',
    ];

    /**
     * {@inheritDoc}
     */
    public function onUpdate()
    {

    }

}
