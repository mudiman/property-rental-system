<?php

namespace App\Presenters;

use App\Transformers\TenancyTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TenancyPresenter
 *
 * @package namespace App\Presenters;
 */
class TenancyPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TenancyTransformer();
    }
}
