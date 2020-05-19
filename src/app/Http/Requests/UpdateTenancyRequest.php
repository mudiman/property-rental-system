<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Tenancy;

class UpdateTenancyRequest extends FormRequest
{

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        return Tenancy::where('id', $this->tenancy)->whereNotIn('status', [Tenancy::CANCEL, Tenancy::COMPLETE, Tenancy::RENEWED])
            ->where(function ($query) {
                $query->where('landlord_id', $this->user()->id)
                  ->orWhere('tenant_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
        })->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
          'actual_checkin' => 'nullable|date_format:Y-m-d H:i:s',
          'actual_checkout' => 'nullable|date_format:Y-m-d H:i:s',
          
          'property_pro_id' => '',
          'parent_tenancy_id' => '',
          
          ] + Tenancy::$rules;
    }
}
