<?php

namespace App\Presenters;

use App\Transformers\PayoutIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class ViewingPresenter
 *
 * @package namespace App\Presenters;
 */
class PayoutIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new PayoutIndexTransformer();
    }
}
