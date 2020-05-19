<?php

namespace App\Presenters;

use App\Transformers\ViewingRequestTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingRequestPresenter
 *
 * @package namespace App\Presenters;
 */
class ViewingRequestPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ViewingRequestTransformer();
    }
}
