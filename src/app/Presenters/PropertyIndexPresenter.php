<?php

namespace App\Presenters;

use App\Transformers\PropertyIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class PropertyIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class PropertyIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PropertyIndexTransformer();
    }
}
