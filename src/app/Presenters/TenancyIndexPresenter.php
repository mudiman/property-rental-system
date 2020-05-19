<?php

namespace App\Presenters;

use App\Transformers\TenancyIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class TenancyIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class TenancyIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new TenancyIndexTransformer();
    }
}
