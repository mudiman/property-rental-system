<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateScoreRequest;
use App\Http\Requests\UpdateScoreRequest;
use App\Repositories\ScoreRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use ViewComponents\Eloquent\EloquentDataProvider;
use ViewComponents\ViewComponents\Component\Control\SelectFilterControl;
use Illuminate\Database\Eloquent\Relations\Relation;
use ViewComponents\ViewComponents\Data\Operation\FilterOperation;
use ViewComponents\ViewComponents\Input\InputSource;
use ViewComponents\Grids\Component\Column;
use ViewComponents\ViewComponents\Component\Part;
use ViewComponents\ViewComponents\Component\Control\PaginationControl;
use ViewComponents\ViewComponents\Component\Control\FilterControl;
use ViewComponents\ViewComponents\Component\Html\TagWithText;
use ViewComponents\ViewComponents\Component\Html\Tag;
use ViewComponents\Grids\Component\CsvExport;
use ViewComponents\Grids\Component\ColumnSortingControl;
use ViewComponents\ViewComponents\Component\ManagedList\ResetButton;
use ViewComponents\Grids\Grid;
use ViewComponents\ViewComponents\Customization\CssFrameworks\BootstrapStyling;
use ViewComponents\ViewComponents\Component\TemplateView;
use Response;
use App\models\Score;

class ScoreController extends AppBaseController
{
    /** @var  ScoreRepository */
    private $scoreRepository;

    public function __construct(ScoreRepository $scoreRepo)
    {
        $this->scoreRepository = $scoreRepo;
    }

    /**
     * Display a listing of the Score.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
      $query = null;
      if (!$query) {
        $query = Score::class;
      }
      $provider = new EloquentDataProvider($query);
      $components = [
        new Column('id'),
        new Column('score_type_id'),
        (new Column('user'))->setValueCalculator(function($row) {
          return isset($row->user) ? $row->user->first_name:'';
        }),
        new Column('scoreable_id'),
        new Column('scoreable_type'),
        new Column('status'),
        new Column('score'),
        new Column('score_change'),
        new Column('current'),
        new Column('max'),
        new Column('min'),
        new Column('factor'),
        new Column('streak_count'),
        new Column('max_diff'),
        (new Column('Actions'))->setValueCalculator(function ($row) {
          $edit = route('scores.edit', [$row->id]);
          $view = route('scores.show', [$row->id]);
          $delete = route('scores.destroy', [$row->id]);
          $deleteCsrfToken = \Collective\Html\FormFacade::token();
          $buttons = <<<EOF
            <form action="$delete" method="POST" accept-charset="UTF-8">
              <input name="_method" type="hidden" value="DELETE">
              $deleteCsrfToken
                <div class='btn-group'> 
                    <a href="$view" class='btn btn-default btn-xs' data-toggle="tooltip" title="View record"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="$edit" class='btn btn-default btn-xs' data-toggle="tooltip" title="Edit record"><i class="glyphicon glyphicon-edit"></i></a>
                    <button type="submit" class="btn btn-danger btn-xs hide" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="delete record"><i class="glyphicon glyphicon-trash"></i></button>
                </div>
              </form>
EOF;
          $buttons.= '<form action="'.$delete.'" method="POST" accept-charset="UTF-8"><input name="_method" type="hidden" value="DELETE">'.$deleteCsrfToken.
                '<button type="submit" class="btn btn-danger btn-xs" data-toggle="tooltip" title="delete record" onclick="return confirm(\'Are you sure?\')">'
                . '<i class="glyphicon glyphicon-trash"></i></button></form>';
            return $buttons;
      }),
        new Part(new Tag('div', ['class' => 'top-buttons pull-right']), 'action-buttons', 'table_heading'),
        new Part(new Tag('tr'), 'control_row2', 'table_heading'),
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c1-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c2-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c3-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c4-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c5-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c8-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c9-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c10-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c11-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c12-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c13-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c14-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c15-row', 'control_row2'),
        new ColumnSortingControl('id', $input->option('sort')),
        new ColumnSortingControl('score_type_id', $input->option('sort')),
        new ColumnSortingControl('scoreable_id', $input->option('sort')),
        new ColumnSortingControl('status', $input->option('sort')),
        new ColumnSortingControl('score', $input->option('sort')),
        new ColumnSortingControl('current', $input->option('sort')),
        new ColumnSortingControl('max', $input->option('sort')),
        new ColumnSortingControl('min', $input->option('sort')),
        new ColumnSortingControl('factor', $input->option('sort')),
        new ColumnSortingControl('streak_count', $input->option('sort')),
        new ColumnSortingControl('max_diff', $input->option('sort')),
        (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
          'label' => null,
          'placeholder' => 'id',
          'inputType' => 'number',
          'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c1-row'),
        (new FilterControl('score_type_id', FilterOperation::OPERATOR_EQ, $input->option('payin_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'score_type_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c2-row'),
          (new FilterControl('scoreable_id', FilterOperation::OPERATOR_EQ, $input->option('scoreable_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'scoreable_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c4-row'),
          (new SelectFilterControl(
              'scoreable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('scoreable_type')
          ))->setDestinationParentId('c5-row')->enableAutoSubmitting(),
        (new FilterControl('status', FilterOperation::OPERATOR_EQ, $input->option('status'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'status',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c6-row'),
        (new FilterControl('score', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('score'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'score',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c7-row'),
           (new FilterControl('score_change', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('score_change'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'score_change',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c8-row'),
          (new FilterControl('current', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('current'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'current',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c9-row'),
          (new FilterControl('max', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('max'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'max',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c10-row'),
          (new FilterControl('min', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('min'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'min',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c11-row'),
          (new FilterControl('factor', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('factor'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'factor',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c12-row'),
          (new FilterControl('streak_count', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('streak_count'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'streak_count',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c13-row'),
       
        new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c15-row'),
        (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c15-row'),
        (new CsvExport($input('csv')))->setDestinationParentId('c15-row'),
        new PaginationControl($input->option('page', 1), 10),
      ];

      $components[1]->getDataCell()->setAttribute('class', 'grid-column');
      $components[2]->getDataCell()->setAttribute('class', 'grid-column');
      $components[3]->getDataCell()->setAttribute('class', 'grid-column');
      $components[4]->getDataCell()->setAttribute('class', 'grid-column');
      $components[5]->getDataCell()->setAttribute('class', 'grid-column');
      $components[6]->getDataCell()->setAttribute('class', 'grid-column');
      $components[7]->getDataCell()->setAttribute('class', 'grid-column');
      $components[8]->getDataCell()->setAttribute('class', 'grid-column');
      $components[9]->getDataCell()->setAttribute('class', 'grid-column');
      $components[10]->getDataCell()->setAttribute('class', 'grid-column');
      $components[11]->getDataCell()->setAttribute('class', 'grid-column');
      $components[12]->getDataCell()->setAttribute('class', 'grid-column');
      $components[13]->getDataCell()->setAttribute('class', 'grid-column');
      $components[14]->getDataCell()->setAttribute('class', 'grid-column');

      $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[4]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[5]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[6]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[7]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[8]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[9]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[10]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[11]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[12]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[13]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[14]->getTitleCell()->setAttribute('class', 'grid-column');

      $grid = new Grid($provider, $components);

      $customization = new BootstrapStyling();
      $customization->apply($grid);

      return view('scores.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Score.
     *
     * @return Response
     */
    public function create()
    {
        return view('scores.create');
    }

    /**
     * Store a newly created Score in storage.
     *
     * @param CreateScoreRequest $request
     *
     * @return Response
     */
    public function store(CreateScoreRequest $request)
    {
        $input = $request->all();

        $score = $this->scoreRepository->create($input);

        Flash::success('Score saved successfully.');

        return redirect(route('scores.index'));
    }

    /**
     * Display the specified Score.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            Flash::error('Score not found');

            return redirect(route('scores.index'));
        }

        return view('scores.show')->with('score', $score);
    }

    /**
     * Show the form for editing the specified Score.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            Flash::error('Score not found');

            return redirect(route('scores.index'));
        }

        return view('scores.edit')->with('score', $score);
    }

    /**
     * Update the specified Score in storage.
     *
     * @param  int              $id
     * @param UpdateScoreRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateScoreRequest $request)
    {
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            Flash::error('Score not found');

            return redirect(route('scores.index'));
        }

        $score = $this->scoreRepository->update($request->all(), $id);

        Flash::success('Score updated successfully.');

        return redirect(route('scores.index'));
    }

    /**
     * Remove the specified Score from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $score = $this->scoreRepository->findWithoutFail($id);

        if (empty($score)) {
            Flash::error('Score not found');

            return redirect(route('scores.index'));
        }

        $this->scoreRepository->delete($id);

        Flash::success('Score deleted successfully.');

        return redirect(route('scores.index'));
    }
}
