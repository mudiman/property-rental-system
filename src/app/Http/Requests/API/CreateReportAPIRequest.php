<?php

namespace App\Http\Requests\API;

use App\Models\Report;

class CreateReportAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
      
      $report = $this->all();
      if (isset($report['by_user'])) {
        return $this->user()->id == $report['by_user'];
      }
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return Report::rules(0, []);
    }
}
