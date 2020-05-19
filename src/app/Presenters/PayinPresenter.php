<?php

namespace App\Presenters;

use App\Transformers\PayinTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingPresenter
 *
 * @package namespace App\Presenters;
 */
class PayinPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PayinTransformer();
    }
}
