<?php

namespace App\Http\Requests\API;

use App\Models\Document;
use App\Models\Property;
use App\Models\User;
use App\Models\Tenancy;
use App\Models\Offer;

class CreateDocumentAPIRequest extends APIBaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      if($this->user()->isAdmin()) return true;
        
      $document = $this->all();
      if (isset($document['documentable_type']) && isset($document['documentable_id'])) {
        if ($document['documentable_type'] == User::morphClass) {
          return $this->user()->id == $document['documentable_id'];
          
        } elseif ($document['documentable_type'] == Property::morphClass) {
          return Property::where('id', $document['documentable_id'])->where('landlord_id', $this->user()->id)->exists() || 
              PropertyPro::where('property_id', $document['documentable_id'])->where('property_pro_id', $this->user()->id)->exists();
          
        } elseif ($document['documentable_type'] == Tenancy::morphClass) {
          return Tenancy::where('id', $document['documentable_id'])->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('tenant_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
          
        } elseif ($document['documentable_type'] == Offer::morphClass) {
          return Offer::where('id', $document['documentable_id'])->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('tenant_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
          
        } elseif ($document->documentable_type == PropertyPro::morphClass) {
          return PropertyPro::where('id', $document->documentable_id)->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
        }
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
        return Document::rules(0, []);
    }
}
