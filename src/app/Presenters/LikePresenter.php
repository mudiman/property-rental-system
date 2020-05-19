<?php

namespace App\Presenters;

use App\Transformers\LikeTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class OfferPresenter
 *
 * @package namespace App\Presenters;
 */
class LikePresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new LikeTransformer();
    }
}
