<?php

namespace App\Presenters;

use App\Transformers\UserIndexTransformer;
use Prettus\Repository\Presenter\FractalPresenter;

/**
 * Class UserIndexPresenter
 *
 * @package namespace App\Presenters;
 */
class UserIndexPresenter extends FractalPresenter
{
    /**
     * Transformer
     *
     * @return \League\Fractal\TransformerAbstract
     */
    public function getTransformer()
    {
        return new UserIndexTransformer();
    }
}
