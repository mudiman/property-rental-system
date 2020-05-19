<?php

namespace App\Presenters;

use App\Transformers\ThreadTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ThreadPresenter
 *
 * @package namespace App\Presenters;
 */
class ThreadPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new ThreadTransformer();
    }
}
