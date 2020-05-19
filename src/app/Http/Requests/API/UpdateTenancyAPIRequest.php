<?php

namespace App\Http\Requests\API;

use App\Models\Tenancy;
use App\Models\Payin;
use App\Models\Payout;

class UpdateTenancyAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        if(!$this->checkAdminVerified()) return false;
        
        $tenancy = $this->all();
        if (isset($tenancy['payout_id'])) {
          $tenantPayin = Payout::where('user_id', $this->user()->id)->where('id', $tenancy['payout_id'])->exists();
          if(!$tenantPayin) return false;
        }
        if (isset($tenancy['landlord_payin_id'])) {
          $landlordPayin = Payin::where('user_id', $this->user()->id)->where('id', $tenancy['landlord_payin_id'])->exists();
          if(!$landlordPayin) return false;
        }
        return Tenancy::where('id', $this->tenancy)->where(function ($query) {
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
            'tenant_id' => 'exists:users,id',
            'landlord_id' => 'exists:users,id',
            'property_id' => 'exists:properties,id',
            'parent_tenancy_id' => 'exists:tenancies,id',
            'property_pro_id' => 'exists:users,id',
          ] + Tenancy::$rules;
    }
}