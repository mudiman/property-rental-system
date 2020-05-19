<?php

namespace App\Presenters;

use App\Transformers\OfferTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferPresenter
 *
 * @package namespace App\Presenters;
 */
class OfferPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferTransformer();
    }
}
