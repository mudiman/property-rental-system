<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateViewingRequest;
use App\Http\Requests\UpdateViewingRequest;
use App\Repositories\ViewingRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use ViewComponents\Eloquent\EloquentDataProvider;
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
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Models\User;
use App\Models\Viewing;

class ViewingController extends AppBaseController
{
    /** @var  ViewingRepository */
    private $viewingRepository;

    public function __construct(ViewingRepository $viewingRepo)
    {
        $this->viewingRepository = $viewingRepo;
    }

    /**
     * Display a listing of the Viewing.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $query = null;
        $userName = '';
        if (isset($_GET['user']) && !empty($_GET['user'])) {
          list($query, $userName) = $this->applyUserFilter($query, Viewing::class, $_GET['user'], 'conducted_by');
        }
        if (!$query) {
          $query = Viewing::class;
        }
        $provider = new EloquentDataProvider($query);

        $components = [
          new Column('id'),
          new Column('property_id'),
          (new Column('conductedBy'))->setValueCalculator(function($row) {
            return isset($row->conductedBy) ? $row->conductedBy->first_name:'';
          }),
          new Column('start_datetime'),
          new Column('end_datetime'),
          new Column('status'),
          new Column('checkin'),
          new Column('updated_at'),
          new Column('created_at'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('viewings.edit', [$row->id]);
            $view = route('viewings.show', [$row->id]);
            $delete = route('viewings.destroy', [$row->id]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
              $buttons = <<<EOF
                <form action="$delete" method="POST" accept-charset="UTF-8">
                  <input name="_method" type="hidden" value="DELETE">
                  $deleteCsrfToken
                  <div class='btn-group'> 
                      <a href="$view" class='btn btn-default btn-xs' data-toggle="tooltip" title="View record"><i class="glyphicon glyphicon-eye-open"></i></a>
                      <a href="$edit" class='btn btn-default btn-xs' data-toggle="tooltip" title="Edit record"><i class="glyphicon glyphicon-edit"></i></a>
                      <button type="submit" class="btn btn-danger btn-xs hide" data-toggle="tooltip" title="delete record" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></button>
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
          new Part(new Tag('input', ['value' => $userName, 'type' => 'text', 'placeholder' => 'User', 'class' => 'form-control grid-m-column', 'name' => 'user']), 'salon', 'c3-row'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c9-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c10-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('start_datetime', $input->option('sort')),
          new ColumnSortingControl('end_datetime', $input->option('sort')),
          new ColumnSortingControl('status', $input->option('sort')),
          new ColumnSortingControl('checkin', $input->option('sort')),
          new ColumnSortingControl('created_at', $input->option('sort')),
          new ColumnSortingControl('updated_at', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('property_id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('property_id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'property_id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c2-row'),
          (new FilterControl('status', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('status'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'status',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c10-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c10-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c10-row'),
          new PaginationControl($input->option('page', 1), 10),
        ];

        $components[1]->getDataCell()->setAttribute('class', 'grid-column');
        $components[2]->getDataCell()->setAttribute('class', 'grid-column');
        $components[3]->getDataCell()->setAttribute('class', 'grid-column');
        $components[4]->getDataCell()->setAttribute('class', 'grid-column');
        $components[5]->getDataCell()->setAttribute('class', 'grid-column');
        $components[6]->getDataCell()->setAttribute('class', 'grid-column');
        $components[7]->getDataCell()->setAttribute('class', 'grid-column');

        $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[4]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[5]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[6]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[7]->getTitleCell()->setAttribute('class', 'grid-column');

        $grid = new Grid($provider, $components);

        $customization = new BootstrapStyling();
        $customization->apply($grid);
        
        return view('viewings.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Viewing.
     *
     * @return Response
     */
    public function create()
    {
        return view('viewings.create');
    }

    /**
     * Store a newly created Viewing in storage.
     *
     * @param CreateViewingRequest $request
     *
     * @return Response
     */
    public function store(CreateViewingRequest $request)
    {
        $input = $request->all();

        $viewing = $this->viewingRepository->create($input);

        Flash::success('Viewing saved successfully.');

        return redirect(route('viewings.index'));
    }

    /**
     * Display the specified Viewing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            Flash::error('Viewing not found');

            return redirect(route('viewings.index'));
        }

        return view('viewings.show')->with('viewing', $viewing);
    }

    /**
     * Show the form for editing the specified Viewing.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            Flash::error('Viewing not found');

            return redirect(route('viewings.index'));
        }

        return view('viewings.edit')->with('viewing', $viewing);
    }

    /**
     * Update the specified Viewing in storage.
     *
     * @param  int              $id
     * @param UpdateViewingRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateViewingRequest $request)
    {
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            Flash::error('Viewing not found');

            return redirect(route('viewings.index'));
        }

        $viewing = $this->viewingRepository->update($request->all(), $id);

        Flash::success('Viewing updated successfully.');

        return redirect(route('viewings.index'));
    }

    /**
     * Remove the specified Viewing from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $viewing = $this->viewingRepository->findWithoutFail($id);

        if (empty($viewing)) {
            Flash::error('Viewing not found');

            return redirect(route('viewings.index'));
        }

        $this->viewingRepository->delete($id);

        Flash::success('Viewing deleted successfully.');

        return redirect(route('viewings.index'));
    }
}
