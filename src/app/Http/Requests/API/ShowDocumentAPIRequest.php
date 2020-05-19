<?php

namespace App\Http\Requests\API;

use App\Models\Document;
use App\Models\Tenancy;
use InfyOm\Generator\Request\APIRequest;
use App\Models\Property;
use App\Models\User;
use App\Models\PropertyPro;
use App\Models\Offer;

class ShowDocumentAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if($this->user()->isAdmin()) return true;
        
        $document = Document::findorfail($this->document);
        if ($document->documentable_type == Property::morphClass) {
          return Property::where('id', $document->documentable_id)->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
          
        } elseif ($document->documentable_type == Tenancy::morphClass) {
          return Tenancy::where('id', $document->documentable_id)->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('tenant_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
          
        } elseif ($document->documentable_type == User::morphClass) {
          return $document->documentable_id == $this->user()->id;
          
        } elseif ($document->documentable_type == PropertyPro::morphClass) {
          return PropertyPro::where('id', $document->documentable_id)->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
          
        } elseif ($document->documentable_type == Offer::morphClass) {
          return Offer::where('id', $document->documentable_id)->where(function ($query) {
            $query->where('landlord_id', $this->user()->id)
                  ->orWhere('tenant_id', $this->user()->id)
                  ->orWhere('property_pro_id', $this->user()->id);
          })->exists();
        }
        return false;
    }
    
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
