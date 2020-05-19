<?php

namespace App\Http\Requests\API;

use App\Models\Offer;
use App\Models\Payin;
use App\Models\Payout;
use App\Models\Property;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Support\Helper;

class UpdateOfferAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $this->checkAdminVerified();
        
        $offer = $this->all();
        if (isset($offer['payout_id'])) {
          $tenantPayin = Payout::where('user_id', $this->user()->id)->where('id', $offer['payout_id'])->exists();
          if(!$tenantPayin) return false;
        }
        if (isset($offer['landlord_payin_id'])) {
          $landlordPayin = Payin::where('user_id', $this->user()->id)->where('id', $offer['landlord_payin_id'])->exists();
          if(!$landlordPayin) return false;
        }
        return Offer::where('id', $this->offer)->where(function ($query) {
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
        $offer = $this->all();
        $checkin = $this->parseDateTimeOrGiveNowPlusADayIfLess($offer, 'checkin');
        
        return [
            'tenant_id' => 'exists:users,id',
            'landlord_id' => 'exists:users,id',
            'property_id' => 'exists:properties,id',
            'property_pro_id' => 'exists:users,id',
            'rent_per_month' => 'numeric|'.Helper::FLOAT_REGEX,
            'rent_per_week' => 'numeric|'.Helper::FLOAT_REGEX,
            'rent_per_night' => 'numeric|'.Helper::FLOAT_REGEX,
            'security_deposit_week' => 'numeric',
            'security_deposit_amount' => 'numeric|'.Helper::FLOAT_REGEX,
            'security_holding_deposit_amount' => 'numeric|'.Helper::FLOAT_REGEX,
          
            'previous_offer_id' => 'exists:offers,id',
            'checkin' => 'date_format:Y-m-d',
            'checkout' => 'date_format:Y-m-d',
            'currency' => 'sometimes',
          ] + Offer::$rules;
    }
}
