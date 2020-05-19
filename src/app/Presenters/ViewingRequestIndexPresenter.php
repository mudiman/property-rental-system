<?php

namespace App\Presenters;

use App\Transformers\ViewingRequestIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingRequestIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class ViewingRequestIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ViewingRequestIndexTransformer();
    }
}
