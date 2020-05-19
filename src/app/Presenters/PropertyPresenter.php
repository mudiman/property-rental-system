<?php

namespace App\Presenters;

use App\Transformers\PropertyTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PropertyPresenter
 *
 * @package namespace App\Presenters;
 */
class PropertyPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PropertyTransformer();
    }
}
