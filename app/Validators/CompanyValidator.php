<?php
namespace App\Validators;

class CompanyValidator extends BaseValidator
{
    public static $update_rules = [
        'name'      => 'required',
        'address'   => 'required',
        'is_active' => 'required|integer',
    ];

    public static $create_rules = [
        'name'      => 'required',
        'address'   => 'required',
        'is_active' => 'required|integer',
    ];

    protected $messages = [
        'name.required'      => 'Company name is required',
        'address.required'   => 'Company address is required',
        'is_active.required' => 'Company is_active is required',
        'is_active.integer'  => 'Company is_active must be integer',
    ];
}