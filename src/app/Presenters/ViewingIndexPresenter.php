<?php

namespace App\Presenters;

use App\Transformers\ViewingIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class ViewingIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ViewingIndexTransformer();
    }
}
