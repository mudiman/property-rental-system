<?php

namespace App\Transformers;

use App\Models\Property;

/**
 * Class PropertyTransformer
 * @package namespace App\Transformers;
 */
class PropertyTransformer extends BaseTransformer
{

    /**
     * Transform the \Property entity
     * @param \Property $model
     *
     * @return array
     */
    public function transform(Property $model)
    {
        $response = [
          'id' => (int) $model->id,
          'title' => $model->title,
          'summary' => $model->summary,
          'letting_type' => $model->letting_type,
          'property_type' => $model->property_type,
          'room_type' => $model->room_type,
          'room_suitable' => $model->room_suitable,
          'bathroom_type' => $model->bathroom_type,
          'people_living' => (int) $model->people_living,
          'status' => $model->status,
          'completion_phase' => $model->completion_phase,
          'cordinate' => $model->cordinate,
          'available_date' => $this->formatDate($model->available_date),
          'postcode' => $model->postcode,
          'door_number' => $model->door_number,
          'street' => $model->street,
          'city' => $model->city,
          'apartment_building' => $model->apartment_building,
          'county' => $model->county,
          'country' => $model->country,
          
          'valuation_comment' => $model->valuation_comment,
          'valuation_rating' => $model->valuation_rating,
          
          'currency' => $model->currency,
          'rent_per_month' => $this->formatAmount($model->rent_per_month),
          'rent_per_week' => $this->formatAmount($model->rent_per_week),
          'rent_per_night' => $this->formatAmount($model->rent_per_night),
          'minimum_accepted_price' => $this->formatAmount($model->minimum_accepted_price),
          'minimum_accepted_price_short_term_price' => $this->formatAmount($model->minimum_accepted_price_short_term_price),
          'security_deposit_weeks' => $model->security_deposit_weeks,
          'security_deposit_amount' => $this->formatAmount($model->security_deposit_amount),
          'security_deposit_holding_amount' => $this->formatAmount($model->security_deposit_holding_amount),
          'contract_length_months' => $model->contract_length_months,
          'shortterm_rent_per_month' => $this->formatAmount($model->shortterm_rent_per_month),
          'shortterm_rent_per_week' => $this->formatAmount($model->shortterm_rent_per_week),
          
          'quick_booking' => boolval($model->quick_booking),
          'receptions' => $model->receptions,
          'bedrooms' => $model->bedrooms,
          'bathrooms' => $model->bathrooms,
          'has_garden' => boolval($model->has_garden),
          'has_balcony_terrace' => boolval($model->has_balcony_terrace),
          'has_parking' => boolval($model->has_parking),
          'flatshares' => boolval($model->flatshares),
          'ensuite' => boolval($model->ensuite),
          'reviewed' => boolval($model->reviewed),
          'area_overview' => $model->area_overview,
          'area_info' => $model->area_info,
          'notes' => $model->notes,
          'rules' => $model->rules,
          'getting_around' => $model->getting_around,
          'inclusive' => $model->inclusive,
          'extra_info' => $model->extra_info,
          
          'landlord' => $model->landlord()->first($this->userBasicFieldList()),
          'property_pro' => $model->propertyPros()->limit(10)->offset(0)->orderBy('property_pros.created_at')->get($this->userBasicFieldList()),
          'documents' => $model->documents()->limit(10)->offset(0)->orderBy('created_at')->get($this->documentBasicFieldList()),
          'images' => $model->images()->orderBy('created_at')->get($this->imageBasicFieldList()),
          'reviews' => $model->reviews()->limit(10)->offset(0)->orderBy('created_at')->get($this->reviewBasicFieldList()),
          'reports' => $model->reports()->limit(10)->offset(0)->orderBy('created_at')->get($this->reportBasicFieldList()),
          'tenancies' => $model->tenancies()->limit(10)->offset(0)->orderBy('created_at')->get($this->tenancyBasicFieldList()),
          'viewings' => $model->viewings()->limit(10)->offset(0)->orderBy('created_at')->get($this->viewingBasicFieldList()),
          'viewedBy' => $model->viewedBy()->limit(10)->distinct()->offset(0)->get($this->userBasicFieldList()),
          
          'likeUsers' => $model->likeUsers()->limit(10)->offset(0)->get($this->userBasicFieldList()),          
          'liked' => $model->liked()->limit(10)->offset(0)->get(),          
          
          'total_listing_view' => intVal($model->total_listing_view),
          'total_detail_view' => intVal($model->total_detail_view),
          
        ] + $this->transformDefault($model);
        return $response;
    }
}
