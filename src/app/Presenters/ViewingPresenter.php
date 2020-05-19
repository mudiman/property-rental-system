<?php

namespace App\Presenters;

use App\Transformers\ViewingTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingPresenter
 *
 * @package namespace App\Presenters;
 */
class ViewingPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ViewingTransformer();
    }
}
