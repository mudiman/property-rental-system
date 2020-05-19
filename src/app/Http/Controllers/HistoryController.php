<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateHistoryRequest;
use App\Http\Requests\UpdateHistoryRequest;
use App\Repositories\HistoryRepository;
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
use App\Models\History;

class HistoryController extends AppBaseController
{
    /** @var  HistoryRepository */
    private $historyRepository;

    public function __construct(HistoryRepository $historyRepo)
    {
        $this->historyRepository = $historyRepo;
    }

    /**
     * Display a listing of the History.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
      $query = null;
      if (!$query) {
        $query = History::class;
      }
      $provider = new EloquentDataProvider($query);
      $components = [
        new Column('id'),
        new Column('historiable_id'),
        new Column('historiable_type'),
        new Column('snapshot'),
        
        (new Column('Actions'))->setValueCalculator(function ($row) {
          $edit = route('histories.edit', [$row->id]);
          $view = route('histories.show', [$row->id]);
          $delete = route('histories.destroy', [$row->id]);
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
        new Part(new Tag('td', ['class' => 'grid-column']), 'c3-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-column']), 'c5-row', 'control_row2'),
        new ColumnSortingControl('id', $input->option('sort')),
        new ColumnSortingControl('historiable_id', $input->option('sort')),
        new ColumnSortingControl('historiable_type', $input->option('sort')),
        (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
          'label' => null,
          'placeholder' => 'id',
          'inputType' => 'number',
          'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c1-row'),
          (new FilterControl('historiable_id', FilterOperation::OPERATOR_EQ, $input->option('historiable_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'historiable_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c2-row'),
          (new SelectFilterControl(
              'historiable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('historiable_type')
          ))->setDestinationParentId('c3-row')->enableAutoSubmitting(),
        new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c5-row'),
        (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c5-row'),
        (new CsvExport($input('csv')))->setDestinationParentId('c5-row'),
        new PaginationControl($input->option('page', 1), 10),
      ];

      $components[1]->getDataCell()->setAttribute('class', 'grid-column');
      $components[2]->getDataCell()->setAttribute('class', 'grid-column');
      $components[3]->getDataCell()->setAttribute('class', 'grid-column');
      $components[4]->getDataCell()->setAttribute('class', 'grid-column');

      $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[4]->getTitleCell()->setAttribute('class', 'grid-column');

      $grid = new Grid($provider, $components);

      $customization = new BootstrapStyling();
      $customization->apply($grid);

      return view('histories.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new History.
     *
     * @return Response
     */
    public function create()
    {
        return view('histories.create');
    }

    /**
     * Store a newly created History in storage.
     *
     * @param CreateHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateHistoryRequest $request)
    {
        $input = $request->all();

        $history = $this->historyRepository->create($input);

        Flash::success('History saved successfully.');

        return redirect(route('histories.index'));
    }

    /**
     * Display the specified History.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            Flash::error('History not found');

            return redirect(route('histories.index'));
        }

        return view('histories.show')->with('history', $history);
    }

    /**
     * Show the form for editing the specified History.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            Flash::error('History not found');

            return redirect(route('histories.index'));
        }

        return view('histories.edit')->with('history', $history);
    }

    /**
     * Update the specified History in storage.
     *
     * @param  int              $id
     * @param UpdateHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHistoryRequest $request)
    {
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            Flash::error('History not found');

            return redirect(route('histories.index'));
        }

        $history = $this->historyRepository->update($request->all(), $id);

        Flash::success('History updated successfully.');

        return redirect(route('histories.index'));
    }

    /**
     * Remove the specified History from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $history = $this->historyRepository->findWithoutFail($id);

        if (empty($history)) {
            Flash::error('History not found');

            return redirect(route('histories.index'));
        }

        $this->historyRepository->delete($id);

        Flash::success('History deleted successfully.');

        return redirect(route('histories.index'));
    }
}
