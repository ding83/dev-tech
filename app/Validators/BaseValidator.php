<?php

namespace App\Validators;

use Validator;

class BaseValidator
{
	protected $validator;

	protected $messages = [
		'required' => 'The :attribute field is required.'
	];

	protected $setUniqueExemptedId;

	public function setUniqueExemptedId($id)
	{
		$this->setUniqueExemptedId = $id;
	}

	public function validate($data, $rules)
	{
		if ($this->setUniqueExemptedId) {
			$rules = $this->modifyRules($rules);
		}

		$this->validator = Validator::make($data, $rules, $this->messages);

		if ($this->validator->fails()) {
			return false;
		}

		return true;
	}

	private function modifyRules($rules)
	{
		$newRules = [];

		foreach ($rules as $key => $rule)
		{
			$newRules[$key] = preg_replace('/\$id/', $this->setUniqueExemptedId, $rule);
		}

		return $newRules;
	}

	public function errors()
	{
		return $this->validator->errors();
	}
}