<?php

namespace App\Presenters;

use App\Transformers\PayinIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingPresenter
 *
 * @package namespace App\Presenters;
 */
class PayinIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PayinIndexTransformer();
    }
}
