<?php

namespace App\Presenters;

use App\Transformers\PayoutTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingPresenter
 *
 * @package namespace App\Presenters;
 */
class PayoutPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PayoutTransformer();
    }
}
