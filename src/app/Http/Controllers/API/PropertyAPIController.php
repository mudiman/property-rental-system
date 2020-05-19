<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePropertyAPIRequest;
use App\Http\Requests\API\UpdatePropertyAPIRequest;
use App\Http\Requests\API\DeletePropertyAPIRequest;
use App\Presenters\PropertyPresenter;
use App\Jobs\UpdatePropertyStat;
use App\Models\Statistic;
use App\Presenters\PropertyIndexPresenter;
use App\Models\Property;
use App\Jobs\UpdateUserRecentSearchQuery;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;
use App\Criteria\LocationRequestCriteria;
use App\Repositories\PropertyRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use Illuminate\Support\Facades\Auth;


/**
 * Class PropertyController
 * @package App\Http\Controllers\API
 */

class PropertyAPIController extends AppBaseController
{
  
    use DispatchesJobs;
    
    /** @var  PropertyRepository */
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepo)
    {
        $this->propertyRepository = $propertyRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/properties",
     *      summary="Get a listing of the Properties.",
     *      tags={"Property"},
     *      description="Get all Properties also search and sort by specific parameters
     * ?geosearch=cordinate:51,0.1:0.4:<  .This returns properties with 0.4 meter from specified lat,log with respect to cordinate field of property
     * ?search=gotham  .This searches on all searchable fields
     * ?search=gotham&searchFields=title:like This search on title field only using like
     * ?search=gotham&searchFields=city:= This search on title field only using = there are also >, <, >=, <= 
     * ?search=name:John Doe;email:john@gmail.com  Provide fieldName:Value in search param for default = to search
     * ?search=name:John;email:john@gmail.com&searchFields=name:like;email:= rovide fieldName:Value in search param and in searchFields provide 
     * the operator with field name
     * &filter=id;name  Limit response to this fields only semi colon separate fields
     * &limit  for limit
     * &orderBy=id&sortedBy=desc  order by Field name and sortedBy with order type
     * &with=relation   Using with model relation data is attached with property. 
     * Relations are documents;images;landlord;likes;propertyPros;propertyServices;reviews;reports;tenancies;viewings;viewedBy
     * ",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Property")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->propertyRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->propertyRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->propertyRepository->pushCriteria(new LocationRequestCriteria($request));
        $this->propertyRepository->pushCriteria(new LimitOffsetCriteria($request));
        $this->propertyRepository->setPresenter(PropertyIndexPresenter::class);
        $properties = $this->propertyRepository->all();
        
//        $this->dispatch(
//          (new UpdateUserRecentSearchQuery(
//            Auth::user()->id, 
//            json_encode($request->all())
//          ))
//        );
        
        return $this->sendResponse($properties['data'], 'Properties retrieved successfully');
    }

    /**
     * @param CreatePropertyAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/properties",
     *      summary="Store a newly created Property in storage",
     *      tags={"Property"},
     *      description="Store Property",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Property that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Property")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Property"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreatePropertyAPIRequest $request)
    {
        $input = $request->all();
        $this->propertyRepository->setPresenter(PropertyPresenter::class);
        $properties = $this->propertyRepository->create($input);

        return $this->sendResponse($properties['data'], 'Property saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/properties/{id}",
     *      summary="Display the specified Property",
     *      tags={"Property"},
     *      description="Get Property",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Property",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Property"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Property $property */
        $this->propertyRepository->setPresenter(PropertyPresenter::class);
        $property = $this->propertyRepository->findWithoutFail($id);
        if (empty($property)) {
            return $this->sendError('Property not found');
        }
        try {
          if (!Auth::user()->isAdmin()) {
            $this->dispatch(
              new UpdatePropertyStat(
                $property['data']['id'], 
                Auth::user()->id,
                Statistic::TYPE_DETAIL
              )
            );
          }
        } catch (Exception $e) {
        }
        return $this->sendResponse($property['data'], 'Property retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdatePropertyAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/properties/{id}",
     *      summary="Update the specified Property in storage",
     *      tags={"Property"},
     *      description="Update Property",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Property",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Property that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Property")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Property"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update(UpdatePropertyAPIRequest $request, $id)
    {
        $input = $request->all();

        /** @var Property $property */
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            return $this->sendError('Property not found');
        }
        
        if (isset($input['status']) && $property->status != $input['status']) {
          $property->{'transition'.$input['status']}();
        }
        $this->propertyRepository->setPresenter(PropertyPresenter::class);
        $property = $this->propertyRepository->update($input, $id);

        return $this->sendResponse($property['data'], 'Property updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/properties/{id}",
     *      summary="Remove the specified Property from storage",
     *      tags={"Property"},
     *      description="Delete Property",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Property",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy(DeletePropertyAPIRequest $request, $id)
    {
        /** @var Property $property */
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            return $this->sendError('Property not found');
        }

        $property->delete();

        return $this->sendResponse($id, 'Property deleted successfully');
    }
    
    
    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/properties/{id}/statistics",
     *      summary="Display the specified Property statitics",
     *      tags={"Property"},
     *      description="Get Property statistics",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Property",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Property"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function showStatistics($id)
    {
        /** @var Property $property */
        $property = $this->propertyRepository->findWithoutFail($id);
        if (empty($property)) {
            return $this->sendError('Property not found');
        }
        $monthWiseChange = $weekWiseChange = 0;
        
        $monthWiseViews = Statistic::select(\DB::raw("DATE_FORMAT(`viewed_datetime`,'%Y-%m')  as month_year"), \DB::raw("count(id) as count"))
            ->where('property_id', $id)
            ->groupBy("month_year")
            ->orderBy('month_year')
            ->get();
        if (count($monthWiseViews) > 1) {
          $last = $monthWiseViews[count($monthWiseViews)-1];
          $secondLast = $monthWiseViews[count($monthWiseViews)-2];
          $monthWiseChange = $last->count - $secondLast->count;
        }
        
        $weekWiseViews = Statistic::select(\DB::raw("EXTRACT(WEEK  FROM `viewed_datetime`) as week"), \DB::raw("count(id) as count"))
            ->where('property_id', $id)
            ->groupBy('week')
            ->orderBy('week')
            ->get();
        if (count($weekWiseViews) > 1) {
          $last = $weekWiseViews[count($weekWiseViews)-1];
          $secondLast = $weekWiseViews[count($weekWiseViews)-2];
          $weekWiseChange = $last->count - $secondLast->count;
        }
        
        $dayWiseViews = Statistic::select(\DB::raw("date(`viewed_datetime`) as day"), \DB::raw("count(id) as count"))
            ->where('property_id', $id)
            ->groupBy("day")
            ->orderBy('day')
            ->get();
        
        
        $response = [
          'month_wise_views' => $monthWiseViews,
          'change_this_month' => $monthWiseChange,
          'week_wise_views' => $weekWiseViews,
          'change_this_week' => $weekWiseChange,
          'day_wise_views' => $dayWiseViews,
          
        ];
        return $this->sendResponse($response, 'Property statistics retrieved successfully');
    }
}
