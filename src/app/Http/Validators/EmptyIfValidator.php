<?php

namespace App\Http\Validators;

use Illuminate\Support\Facades\Validator;

/**
 * Description of EmptyIfValidator
 *
 * @author muda
 */
class EmptyIfValidator extends \Illuminate\Validation\Validator {
    
    public function validateEmpty($attribute, $value)
    {
        return ! $this->validateRequired($attribute, $value);
    }

    public function validateEmptyIf($attribute, $value, $parameters)
    {
      if ($value){
        foreach ($parameters as $key) {
          if ($this->validateRequired($key, $this->getValue($key))) {
              $this->setCustomMessages(['empty_if' => sprintf('only one field is required from %s and %s', $attribute, $key)]);
              return $this->validateEmpty($attribute, $value);
          }
        }
      }
      return true;
    }
}
