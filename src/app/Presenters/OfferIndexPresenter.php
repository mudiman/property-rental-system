<?php

namespace App\Presenters;

use App\Transformers\OfferIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class OfferIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new OfferIndexTransformer();
    }
}
