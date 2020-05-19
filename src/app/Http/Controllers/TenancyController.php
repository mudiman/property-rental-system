<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTenancyRequest;
use App\Http\Requests\UpdateTenancyRequest;
use App\Repositories\TenancyRepository;
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
use App\Models\Tenancy;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class TenancyController extends AppBaseController
{
    /** @var  TenancyRepository */
    private $tenancyRepository;

    public function __construct(TenancyRepository $tenancyRepo)
    {
        $this->tenancyRepository = $tenancyRepo;
    }

    /**
     * Display a listing of the Tenancy.
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
          list($query, $tenantName) = $this->applyUserFilter($query, Tenancy::class, $_GET['tenant'], 'tenant_id');
        }
        
        $landlordName = '';
        if (isset($_GET['landlord']) && !empty($_GET['landlord'])) {
          list($query, $landlordName) = $this->applyUserFilter($query, Tenancy::class, $_GET['landlord'], 'landlord_id');
        }
        
        if (!$query) {
          $query = Tenancy::class;
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
            $now = Carbon::now();
            $edit = route('tenancies.edit', [$row->id]);
            $view = route('tenancies.show', [$row->id]);
            $delete = route('tenancies.destroy', [$row->id]);
            $payout = route('tenancies.payout', [$row->id]);
            $payoutShowHide = $row->due_date->diffInDays($now) <= 31 ? 'show': 'hide';
            $document = route('documents.index', ['documentable_id'=> $row->id, 'documentable_type' => Tenancy::morphClass]);
            $transaction = route('transactions.index', ['transactionable_id'=> $row->id, 'transactionable_type' => Tenancy::morphClass]);
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
                      <a href="$payout" class='btn btn-default btn-xs $payoutShowHide' data-toggle="tooltip" title="Payout"><i class="glyphicon glyphicon-usd"></i></a>
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
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c3-row', 'control_row2'),
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

        return view('tenancies.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Tenancy.
     *
     * @return Response
     */
    public function create()
    {
        return view('tenancies.create');
    }

    /**
     * Store a newly created Tenancy in storage.
     *
     * @param CreateTenancyRequest $request
     *
     * @return Response
     */
    public function store(CreateTenancyRequest $request)
    {
        $input = $request->all();
        $this->removeEmptyFields($input);
        
        $tenancy = $this->tenancyRepository->create($input);

        Flash::success('Tenancy saved successfully.');

        return redirect(route('tenancies.index'));
    }

    /**
     * Display the specified Tenancy.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            Flash::error('Tenancy not found');

            return redirect(route('tenancies.index'));
        }

        return view('tenancies.show')->with('tenancy', $tenancy);
    }

    /**
     * Show the form for editing the specified Tenancy.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            Flash::error('Tenancy not found');

            return redirect(route('tenancies.index'));
        }
        $statuses =  [
          Tenancy::PRESIGN => Tenancy::PRESIGN,
          Tenancy::SIGNING_COMPLETE => Tenancy::SIGNING_COMPLETE,
          Tenancy::START => Tenancy::START,
          Tenancy::COMPLETE => Tenancy::COMPLETE,
          Tenancy::PRE_NOTICE => Tenancy::PRE_NOTICE,
          Tenancy::NOTICE => Tenancy::NOTICE,
          Tenancy::CANCEL => Tenancy::CANCEL,
          Tenancy::RENEWED => Tenancy::RENEWED,
          Tenancy::ROLLING => Tenancy::ROLLING,
        ];
        return view('tenancies.edit')->with('tenancy', $tenancy)->with('statuses', $statuses);
    }

    /**
     * Update the specified Tenancy in storage.
     *
     * @param  int              $id
     * @param UpdateTenancyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTenancyRequest $request)
    {
        $input = $request->all();
        $tenancy = $this->tenancyRepository->findWithoutFail($id);
        $this->removeEmptyFields($input);
        if (empty($tenancy)) {
            Flash::error('Tenancy not found');
            return redirect(route('tenancies.index'));
        }
        if (isset($input['status']) && $tenancy->status != $input['status']) {
          $tenancy->{'transition'.$input['status']}();
        }
        $tenancy = $this->tenancyRepository->update($request->all(), $id);

        Flash::success('Tenancy updated successfully.');

        return redirect(route('tenancies.index'));
    }

    /**
     * Remove the specified Tenancy from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $tenancy = $this->tenancyRepository->findWithoutFail($id);

        if (empty($tenancy)) {
            Flash::error('Tenancy not found');

            return redirect(route('tenancies.index'));
        }

        $this->tenancyRepository->delete($id);

        Flash::success('Tenancy deleted successfully.');

        return redirect(route('tenancies.index'));
    }
    
    public function timeShiftTenancy($id)
    {
      $res = Artisan::call('test:timeShiftTenancy', [
          'id' => $id
      ]);
      
      Flash::success('Tenancy successfully time travelled. '. $res);
      
      return redirect(route('tenancies.show', [$id]));
    }
    
    public function payout($id) {
      $tenancy = $this->tenancyRepository->findWithoutFail($id);

      if (empty($tenancy)) {
        Flash::error('Tenancy not found');
        return redirect(route('tenancies.index'));
      }
      $transaction = \App\Models\Transaction::firstOrNew([
            'due_date' => $tenancy->due_date->toDateString(),
            'user_id' => $tenancy->tenant_id,
            'title' => Transaction::TITLE_MONTHLY_RENT,
      ]);
      $transaction->amount = $tenancy->rent_per_month;
      $transaction->currency = $tenancy->offer->currency;
      $transaction->dividen_done = false;
      $transaction->landlord_commission = 0;
      $transaction->smoor_commission = 0;
      $transaction->status = Transaction::STATUS_DONE;
      $transaction->description = sprintf(Transaction::DESC_MONTHLY_RENT, $tenancy->id, $tenancy->due_date->month);
      $transaction->transactionable_id = $tenancy->id;
      $transaction->transactionable_type = Tenancy::morphClass;
      $transaction->save();

      Flash::success('Tenancy payout successfully created ');

      return redirect(route('tenancies.show', [$id]));
    }

}
