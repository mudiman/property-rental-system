<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePayoutRequest;
use App\Http\Requests\UpdatePayoutRequest;
use App\Repositories\PayoutRepository;
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
use App\Models\Payout;

class PayoutController extends AppBaseController
{
    /** @var  PayoutRepository */
    private $payoutRepository;

    public function __construct(PayoutRepository $payoutRepo)
    {
        $this->payoutRepository = $payoutRepo;
    }

    /**
     * Display a listing of the Payout.
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
          list($query, $userName) = $this->applyUserFilter($query, Payout::class, $_GET['user'], 'user_id');
        }
        if (!$query) {
          $query = Payout::class;
        }
        $provider = new EloquentDataProvider($query);
        $components = [
          new Column('id'),
          (new Column('user'))->setValueCalculator(function($row) {
            return isset($row->user) ? $row->user->first_name:'';
          }),
          new Column('holder_name'),
          new Column('card_number'),
          new Column('expire_on_month'),
          new Column('expire_on_year'),
          new Column('used'),
          new Column('payout_reference'),
          new Column('default'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('payouts.edit', [$row->id]);
            $view = route('payouts.show', [$row->id]);
            $delete = route('payouts.destroy', [$row->id]);
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
          new Part(new Tag('input', ['value' => $userName, 'type' => 'text', 'placeholder' => 'Landlord', 'class' => 'form-control grid-m-column', 'name' => 'user']), 'salon', 'c2-row'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c9-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c10-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('used', $input->option('sort')),
          new ColumnSortingControl('payout_reference', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('holder_name', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('holder_name'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'holder_name',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c3-row'),
            (new FilterControl('card_number', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('card_number'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'card_number',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
          (new FilterControl('used', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('used'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'used',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c7-row'),
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
        $components[8]->getDataCell()->setAttribute('class', 'grid-column');
        $components[9]->getDataCell()->setAttribute('class', 'grid-column');

        $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[4]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[5]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[6]->getTitleCell()->setAttribute('class', 'grid-column');

        $components[7]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[8]->getTitleCell()->setAttribute('class', 'grid-column');
        $components[9]->getTitleCell()->setAttribute('class', 'grid-column');

        $grid = new Grid($provider, $components);

        $customization = new BootstrapStyling();
        $customization->apply($grid);
        
        return view('payouts.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Payout.
     *
     * @return Response
     */
    public function create()
    {
        return view('payouts.create');
    }

    /**
     * Store a newly created Payout in storage.
     *
     * @param CreatePayoutRequest $request
     *
     * @return Response
     */
    public function store(CreatePayoutRequest $request)
    {
        $input = $request->all();

        $payout = $this->payoutRepository->create($input);

        Flash::success('Payout saved successfully.');

        return redirect(route('payouts.index'));
    }

    /**
     * Display the specified Payout.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            Flash::error('Payout not found');

            return redirect(route('payouts.index'));
        }

        return view('payouts.show')->with('payout', $payout);
    }

    /**
     * Show the form for editing the specified Payout.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            Flash::error('Payout not found');

            return redirect(route('payouts.index'));
        }

        return view('payouts.edit')->with('payout', $payout);
    }

    /**
     * Update the specified Payout in storage.
     *
     * @param  int              $id
     * @param UpdatePayoutRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePayoutRequest $request)
    {
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            Flash::error('Payout not found');

            return redirect(route('payouts.index'));
        }

        $payout = $this->payoutRepository->update($request->all(), $id);

        Flash::success('Payout updated successfully.');

        return redirect(route('payouts.index'));
    }

    /**
     * Remove the specified Payout from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $payout = $this->payoutRepository->findWithoutFail($id);

        if (empty($payout)) {
            Flash::error('Payout not found');

            return redirect(route('payouts.index'));
        }

        $this->payoutRepository->delete($id);

        Flash::success('Payout deleted successfully.');

        return redirect(route('payouts.index'));
    }
}
