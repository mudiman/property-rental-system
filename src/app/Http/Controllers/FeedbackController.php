<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateFeedbackRequest;
use App\Http\Requests\UpdateFeedbackRequest;
use App\Repositories\FeedbackRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
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
use App\Models\Feedback;

class FeedbackController extends AppBaseController
{
    /** @var  FeedbackRepository */
    private $feedbackRepository;

    public function __construct(FeedbackRepository $feedbackRepo)
    {
        $this->feedbackRepository = $feedbackRepo;
    }

    /**
     * Display a listing of the Feedback.
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
          list($query, $userName) = $this->applyUserFilter($query, Feedback::class, $_GET['user'], 'user_id');
        }
        if (!$query) {
          $query = Feedback::class;
        }
        $provider = new EloquentDataProvider($query);

        $components = [
          new Column('id'),
          (new Column('user'))->setValueCalculator(function($row) {
            return isset($row->user) ? $row->user->first_name:'';
          }),
          new Column('title'),
          new Column('description'),
          new Column('updated_at'),
          new Column('created_at'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('feedback.edit', [$row->id]);
            $view = route('feedback.show', [$row->id]);
            $delete = route('feedback.destroy', [$row->id]);
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
          new Part(new Tag('input', ['value' => $userName, 'type' => 'text', 'placeholder' => 'User', 'class' => 'form-control grid-m-column', 'name' => 'user']), 'salon', 'c2-row'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('created_at', $input->option('sort')),
          new ColumnSortingControl('updated_at', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('title', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('title'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'title',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c3-row'),
          (new FilterControl('description', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('description'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'description',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c7-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c7-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c7-row'),
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
        
        return view('feedback.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Feedback.
     *
     * @return Response
     */
    public function create()
    {
        return view('feedback.create');
    }

    /**
     * Store a newly created Feedback in storage.
     *
     * @param CreateFeedbackRequest $request
     *
     * @return Response
     */
    public function store(CreateFeedbackRequest $request)
    {
        $input = $request->all();

        $feedback = $this->feedbackRepository->create($input);

        Flash::success('Feedback saved successfully.');

        return redirect(route('feedback.index'));
    }

    /**
     * Display the specified Feedback.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            Flash::error('Feedback not found');

            return redirect(route('feedback.index'));
        }
        $feedback->user = User::findorfail($feedback->user_id);
        
        return view('feedback.show')->with('feedback', $feedback);
    }

    /**
     * Show the form for editing the specified Feedback.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            Flash::error('Feedback not found');

            return redirect(route('feedback.index'));
        }

        return view('feedback.edit')->with('feedback', $feedback);
    }

    /**
     * Update the specified Feedback in storage.
     *
     * @param  int              $id
     * @param UpdateFeedbackRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFeedbackRequest $request)
    {
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            Flash::error('Feedback not found');

            return redirect(route('feedback.index'));
        }

        $feedback = $this->feedbackRepository->update($request->all(), $id);

        Flash::success('Feedback updated successfully.');

        return redirect(route('feedback.index'));
    }

    /**
     * Remove the specified Feedback from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $feedback = $this->feedbackRepository->findWithoutFail($id);

        if (empty($feedback)) {
            Flash::error('Feedback not found');

            return redirect(route('feedback.index'));
        }

        $this->feedbackRepository->delete($id);

        Flash::success('Feedback deleted successfully.');

        return redirect(route('feedback.index'));
    }
}
