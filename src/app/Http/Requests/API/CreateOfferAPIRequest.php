<?php

namespace App\Http\Requests\API;

use App\Models\Offer;
use App\Models\Property;
use App\Models\Payin;
use App\Models\Payout;
use Carbon\Carbon;
use Symfony\Component\HttpKernel\Exception\HttpException;

class CreateOfferAPIRequest extends APIBaseRequest
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
      if (isset($offer['property_id']) 
          && $offer['status'] != Offer::COUNTER
          && Property::where('id', $offer['property_id'])->where('landlord_id', $this->user()->id)->exists()) {
        throw new HttpException(403, "You can't make offer on your own property");
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
        $offer = $this->all();
        $checkin = $this->parseDateTimeOrGiveNowPlusADayIfLess($offer, 'checkin');
        
        return [
          'checkin' => 'required|date_format:Y-m-d|after:'.$checkin->format('Y-m-d'),
          'checkout' => 'required|date_format:Y-m-d|after:'.$checkin->addDays(1)->format('Y-m-d'),
          ] + Offer::$rules;
    }
}
