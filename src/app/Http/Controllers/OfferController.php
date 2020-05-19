<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Http\Requests\CreateOfferRequest;
use App\Http\Requests\UpdateOfferRequest;
use App\Repositories\OfferRepository;
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

class OfferController extends AppBaseController
{
    /** @var  OfferRepository */
    private $offerRepository;

    public function __construct(OfferRepository $offerRepo)
    {
        $this->offerRepository = $offerRepo;
    }

    /**
     * Display a listing of the Offer.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $query = null;
        
        $tenantName = '';
        if (isset($_GET['tenant']) && !empty($_GET['tenant'])) {
          list($query, $tenantName) = $this->applyUserFilter($query, Offer::class, $_GET['tenant'], 'tenant_id');
        }
        
        $landlordName = '';
        if (isset($_GET['landlord']) && !empty($_GET['landlord'])) {
          list($query, $landlordName) = $this->applyUserFilter($query, Offer::class, $_GET['landlord'], 'landlord_id');
        }
        
        if (!$query) {
          $query = Offer::class;
        }
        $provider = new EloquentDataProvider($query);
        
            
        $components = [
          new Column('id'),
          (new Column('tenant'))->setValueCalculator(function($row) {
            return isset($row->tenant) ? $row->tenant->first_name:'';
          }),
          (new Column('landlord'))->setValueCalculator(function($row) {
            return isset($row->landlord) ? $row->landlord->first_name:'';
          }),
          new Column('property_id'),
          new Column('property_pro_id'),
          new Column('status'),
          new Column('type'),
          (new Column('rent'))->setValueCalculator(function($row) {
            $status = '';
            if (isset($row->rent_per_month)) {
              $status = $row->rent_per_month.' /month';
            }
            if (isset($row->rent_per_week)) {
              $status = $row->rent_per_week.' /week';
            }
            if (isset($row->rent_per_night)) {
              $status = $row->rent_per_night.' /night';
            }
            return $status;
          }),
          new Column('checkin'),
          new Column('checkout'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('offers.edit', [$row->id]);
            $view = route('offers.show', [$row->id]);
            $delete = route('offers.destroy', [$row->id]);
            $document = route('documents.index', ['documentable_id'=> $row->id, 'documentable_type' => Offer::morphClass]);
            $transaction = route('transactions.index', ['transactionable_id'=> $row->id, 'transactionable_type' => Offer::morphClass]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
            $buttons = <<<EOF
              <form action="$delete" method="POST" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                $deleteCsrfToken
                  <div class='btn-group'> 
                      <a href="$document" class='btn btn-default btn-xs' data-toggle="tooltip" title="View documents"><i class="glyphicon glyphicon-book"></i></a>
                      <a href="$transaction" class='btn btn-default btn-xs' data-toggle="tooltip" title="View transaction"><i class="glyphicon glyphicon-usd"></i></a>
                      <a href="$view" class='btn btn-default btn-xs' data-toggle="tooltip" title="View record"><i class="glyphicon glyphicon-eye-open"></i></a>
                      <a href="$edit" class='btn btn-default btn-xs' data-toggle="tooltip" title="Edit record"><i class="glyphicon glyphicon-edit"></i></a>
                      <button type="submit" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure?')" data-toggle="tooltip" title="delete record"><i class="glyphicon glyphicon-trash"></i></button>
                  </div>
                </form>
EOF;
              return $buttons;
        }),
          new Part(new Tag('div', ['class' => 'top-buttons pull-right']), 'action-buttons', 'table_heading'),
          new Part(new Tag('tr'), 'control_row2', 'table_heading'),
          new Part(new Tag('td', ['class' => 'grid-id-column']), 'c1-row', 'control_row2'),
          new Part(new Tag('input', ['value' => $tenantName, 'type' => 'text', 'placeholder' => 'Tenant', 'class' => 'form-control grid-m-column', 'name' => 'tenant']), 'salon1', 'c2-row'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c2-row', 'control_row2'),
          new Part(new Tag('input', ['value' => $landlordName, 'type' => 'text', 'placeholder' => 'Landlord', 'class' => 'form-control grid-m-column', 'name' => 'landlord']), 'salon2', 'c3-row'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c9-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c10-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c11-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('checkin', $input->option('sort')),
          new ColumnSortingControl('checkout', $input->option('sort')),
          new ColumnSortingControl('status', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('property_id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('property_id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'property id',
            'inputType' => 'text',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
          (new FilterControl('property_pro_id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('property_pro_id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'property pro id',
            'inputType' => 'text',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c5-row'),
          (new FilterControl('status', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('status'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'offer status',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
            (new FilterControl('type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c7-row'),
          (new FilterControl('checkin', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('checkin'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'checkin',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c9-row'),
            (new FilterControl('checkout', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('checkout'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'checkout',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c10-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c11-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c11-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c11-row'),
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

        return view('offers.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Offer.
     *
     * @return Response
     */
    public function create()
    {
        return view('offers.create');
    }

    /**
     * Store a newly created Offer in storage.
     *
     * @param CreateOfferRequest $request
     *
     * @return Response
     */
    public function store(CreateOfferRequest $request)
    {
        $input = $request->all();

        $offer = $this->offerRepository->create($input);

        Flash::success('Offer saved successfully.');

        return redirect(route('offers.index'));
    }

    /**
     * Display the specified Offer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            Flash::error('Offer not found');

            return redirect(route('offers.index'));
        }

        return view('offers.show')->with('offer', $offer);
    }

    /**
     * Show the form for editing the specified Offer.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            Flash::error('Offer not found');

            return redirect(route('offers.index'));
        }
        
        $statuses =  [
          Offer::REQUEST => Offer::REQUEST,
          Offer::REJECT => Offer::REJECT,
          Offer::COUNTER => Offer::COUNTER,
          Offer::COUNTERED => Offer::COUNTERED,
          Offer::CANCEL => Offer::CANCEL,
          Offer::INITIAL_DEPOSIT_MADE => Offer::INITIAL_DEPOSIT_MADE,
        ];
        return view('offers.edit')->with('offer', $offer)->with('statuses', $statuses);
    }

    /**
     * Update the specified Offer in storage.
     *
     * @param  int              $id
     * @param UpdateOfferRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOfferRequest $request)
    {
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            Flash::error('Offer not found');

            return redirect(route('offers.index'));
        }

        $offer = $this->offerRepository->update($request->all(), $id);

        Flash::success('Offer updated successfully.');

        return redirect(route('offers.index'));
    }

    /**
     * Remove the specified Offer from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $offer = $this->offerRepository->findWithoutFail($id);

        if (empty($offer)) {
            Flash::error('Offer not found');

            return redirect(route('offers.index'));
        }

        $this->offerRepository->delete($id);

        Flash::success('Offer deleted successfully.');

        return redirect(route('offers.index'));
    }
}
