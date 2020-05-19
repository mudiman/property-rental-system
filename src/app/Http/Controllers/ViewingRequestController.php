<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateViewingRequestRequest;
use App\Http\Requests\UpdateViewingRequestRequest;
use App\Repositories\ViewingRequestRepository;
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
use Response;
use App\Models\User;
use App\Models\ViewingRequest;

class ViewingRequestController extends AppBaseController
{
    /** @var  ViewingRequestRepository */
    private $viewingRequestRepository;

    public function __construct(ViewingRequestRepository $viewingRequestRepo)
    {
        $this->viewingRequestRepository = $viewingRequestRepo;
    }

    /**
     * Display a listing of the ViewingRequest.
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
          list($query, $userName) = $this->applyUserFilter($query, ViewingRequest::class, $_GET['user'], 'view_by_user');
        }
        if (!$query) {
          $query = ViewingRequest::class;
        }
        $provider = new EloquentDataProvider($query);

        $components = [
          new Column('id'),
          new Column('viewing_id'),
          (new Column('viewByUser'))->setValueCalculator(function($row) {
            return isset($row->viewByUser) ? $row->viewByUser->first_name:'';
          }),
          new Column('status'),
          new Column('checkin'),
          new Column('updated_at'),
          new Column('created_at'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('viewingRequests.edit', [$row->id]);
            $view = route('viewingRequests.show', [$row->id]);
            $delete = route('viewingRequests.destroy', [$row->id]);
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
          new ColumnSortingControl('id', $input->option('sort')),
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
          (new FilterControl('viewing_id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('viewing_id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'viewing_id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c2-row'),
          (new FilterControl('status', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('status'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'status',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c8-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c8-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c8-row'),
          new PaginationControl($input->option('page', 1), 10),
        ];

        $components[1]->getDataCell()->setAttribute('class', 'grid-column');
        $components[2]->getDataCell()->setAttribute('class', 'grid-column');
        $components[3]->getDataCell()->setAttribute('class', 'grid-column');
        $components[4]->getDataCell()->setAttribute('class', 'grid-column');
        $components[5]->getDataCell()->setAttribute('class', 'grid-column');
        $components[6]->getDataCell()->setAttribute('class', 'grid-column');

        $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[4]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[5]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[6]->getTitleCell()->setAttribute('class', 'grid-column');



        $grid = new Grid($provider, $components);

        $customization = new BootstrapStyling();
        $customization->apply($grid);
        
        return view('viewing_requests.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new ViewingRequest.
     *
     * @return Response
     */
    public function create()
    {
        return view('viewing_requests.create');
    }

    /**
     * Store a newly created ViewingRequest in storage.
     *
     * @param CreateViewingRequestRequest $request
     *
     * @return Response
     */
    public function store(CreateViewingRequestRequest $request)
    {
        $input = $request->all();

        $viewingRequest = $this->viewingRequestRepository->create($input);

        Flash::success('Viewing Request saved successfully.');

        return redirect(route('viewingRequests.index'));
    }

    /**
     * Display the specified ViewingRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            Flash::error('Viewing Request not found');

            return redirect(route('viewingRequests.index'));
        }

        return view('viewing_requests.show')->with('viewingRequest', $viewingRequest);
    }

    /**
     * Show the form for editing the specified ViewingRequest.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            Flash::error('Viewing Request not found');

            return redirect(route('viewingRequests.index'));
        }

        return view('viewing_requests.edit')->with('viewingRequest', $viewingRequest);
    }

    /**
     * Update the specified ViewingRequest in storage.
     *
     * @param  int              $id
     * @param UpdateViewingRequestRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateViewingRequestRequest $request)
    {
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            Flash::error('Viewing Request not found');

            return redirect(route('viewingRequests.index'));
        }

        $viewingRequest = $this->viewingRequestRepository->update($request->all(), $id);

        Flash::success('Viewing Request updated successfully.');

        return redirect(route('viewingRequests.index'));
    }

    /**
     * Remove the specified ViewingRequest from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $viewingRequest = $this->viewingRequestRepository->findWithoutFail($id);

        if (empty($viewingRequest)) {
            Flash::error('Viewing Request not found');

            return redirect(route('viewingRequests.index'));
        }

        $this->viewingRequestRepository->delete($id);

        Flash::success('Viewing Request deleted successfully.');

        return redirect(route('viewingRequests.index'));
    }
}
