<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreatePaymentMethodAPIRequest;
use App\Http\Requests\API\UpdatePaymentMethodAPIRequest;
use App\Models\PaymentMethod;
use App\Repositories\PaymentMethodRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Criteria\CustomRequestCriteria;
use App\Criteria\CustomSecondRequestCriteria;

/**
 * Class PaymentMethodController
 * @package App\Http\Controllers\API
 */

class PaymentMethodAPIController extends AppBaseController
{
    /** @var  PaymentMethodRepository */
    private $paymentMethodRepository;

    public function __construct(PaymentMethodRepository $paymentMethodRepo)
    {
        $this->paymentMethodRepository = $paymentMethodRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/payment_methods",
     *      summary="Get a listing of the PaymentMethods.",
     *      tags={"PaymentMethod"},
     *      description="Get all PaymentMethods",
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
     *                  @SWG\Items(ref="#/definitions/PaymentMethod")
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
        $this->paymentMethodRepository->pushCriteria(new CustomRequestCriteria($request));
        $this->paymentMethodRepository->pushCriteria(new CustomSecondRequestCriteria($request));
        $this->paymentMethodRepository->pushCriteria(new LimitOffsetCriteria($request));
        $paymentMethods = $this->paymentMethodRepository->all();

        return $this->sendResponse($paymentMethods->toArray(), 'Payment Methods retrieved successfully');
    }
}
