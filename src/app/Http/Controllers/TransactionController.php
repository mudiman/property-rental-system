<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use App\Repositories\TransactionRepository;
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
use App\Models\Transaction;

class TransactionController extends AppBaseController
{
    /** @var  TransactionRepository */
    private $transactionRepository;

    public function __construct(TransactionRepository $transactionRepo)
    {
        $this->transactionRepository = $transactionRepo;
    }

    /**
     * Display a listing of the Transaction.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
      $input = new InputSource($_GET);
      $query = null;
      if (!$query) {
        $query = Transaction::class;
      }
      $provider = new EloquentDataProvider($query);
      $components = [
        new Column('id'),
        new Column('payin_id'),
        new Column('payout_id'),
        new Column('transactionable_id'),
        new Column('transactionable_type'),
        new Column('type'),
        new Column('status'),
        new Column('title'),
        new Column('amount'),
        new Column('due_date'),
        new Column('smoor_commission','smoor'),
        new Column('landlord_commission','landlord'),
        new Column('dividen_done','dividen'),
        
        (new Column('Actions'))->setValueCalculator(function ($row) {
          $edit = route('transactions.edit', [$row->id]);
          $view = route('transactions.show', [$row->id]);
          $delete = route('transactions.destroy', [$row->id]);
          $deleteCsrfToken = \Collective\Html\FormFacade::token();
          $paydividen = route('transactions.dividen', ['id'=> $row->id]);
          $hideDividen = $row->type == Transaction::TYPE_CREDIT 
              && !$row->dividen_done && $row->status == Transaction::STATUS_DONE 
              && $row->transactionable_type == \App\Models\Tenancy::morphClass ? 'show' : 'hide';
          
          $buttons = <<<EOF
            <form action="$delete" method="POST" accept-charset="UTF-8">
              <input name="_method" type="hidden" value="DELETE">
              $deleteCsrfToken
                <div class='btn-group'> 
                    <a href="$view" class='btn btn-default btn-xs' data-toggle="tooltip" title="View record"><i class="glyphicon glyphicon-eye-open"></i></a>
                    <a href="$edit" class='btn btn-default btn-xs' data-toggle="tooltip" title="Edit record"><i class="glyphicon glyphicon-edit"></i></a>
                    <a href="$paydividen" class='btn btn-default btn-xs $hideDividen' data-toggle="tooltip" title="Pay dividen"><i class="glyphicon glyphicon-usd"></i></a>
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
        new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c5-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c8-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c9-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c10-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c11-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c12-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c13-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c14-row', 'control_row2'),
        new ColumnSortingControl('id', $input->option('sort')),
        new ColumnSortingControl('payin_id', $input->option('sort')),
        new ColumnSortingControl('payout_id', $input->option('sort')),
        new ColumnSortingControl('payout_id', $input->option('sort')),
        new ColumnSortingControl('transactionable_id', $input->option('sort')),
        new ColumnSortingControl('transactionable_type', $input->option('sort')),
        new ColumnSortingControl('status', $input->option('sort')),
        new ColumnSortingControl('type', $input->option('sort')),
        new ColumnSortingControl('smoor_commission', $input->option('sort')),
        new ColumnSortingControl('landlord_commission', $input->option('sort')),
        new ColumnSortingControl('dividen_done', $input->option('sort')),
        new ColumnSortingControl('due_date', $input->option('sort')),
        (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
          'label' => null,
          'placeholder' => 'id',
          'inputType' => 'number',
          'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c1-row'),
        (new FilterControl('payin_id', FilterOperation::OPERATOR_EQ, $input->option('payin_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'payin_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c2-row'),
        (new FilterControl('payout_id', FilterOperation::OPERATOR_EQ, $input->option('payout_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'payout_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c3-row'),
          (new FilterControl('transactionable_id', FilterOperation::OPERATOR_EQ, $input->option('transactionable_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'transactionable_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c4-row'),
          (new SelectFilterControl(
              'transactionable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('transactionable_type')
          ))->setDestinationParentId('c5-row')->enableAutoSubmitting(),
        (new FilterControl('type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('type'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'type',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c6-row'),
           (new FilterControl('status', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('status'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'status',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c7-row'),
          (new FilterControl('title', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('title'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'title',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c8-row'),
          (new FilterControl('amount', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('amount'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'amount',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c9-row'),
       
        new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c14-row'),
        (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c14-row'),
        (new CsvExport($input('csv')))->setDestinationParentId('c14-row'),
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

      $grid = new Grid($provider, $components);

      $customization = new BootstrapStyling();
      $customization->apply($grid);

      return view('transactions.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Transaction.
     *
     * @return Response
     */
    public function create()
    {
        return view('transactions.create');
    }

    /**
     * Store a newly created Transaction in storage.
     *
     * @param CreateTransactionRequest $request
     *
     * @return Response
     */
    public function store(CreateTransactionRequest $request)
    {
        $input = $request->all();

        $transaction = $this->transactionRepository->create($input);

        Flash::success('Transaction saved successfully.');

        return redirect(route('transactions.index'));
    }

    /**
     * Display the specified Transaction.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);

        if (empty($transaction)) {
            Flash::error('Transaction not found');

            return redirect(route('transactions.index'));
        }

        return view('transactions.show')->with('transaction', $transaction);
    }

    /**
     * Show the form for editing the specified Transaction.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);

        if (empty($transaction)) {
            Flash::error('Transaction not found');

            return redirect(route('transactions.index'));
        }
        
        return view('transactions.edit')->with('transaction', $transaction);
    }

    /**
     * Update the specified Transaction in storage.
     *
     * @param  int              $id
     * @param UpdateTransactionRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTransactionRequest $request)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);

        if (empty($transaction)) {
            Flash::error('Transaction not found');

            return redirect(route('transactions.index'));
        }

        $transaction = $this->transactionRepository->update($request->all(), $id);

        Flash::success('Transaction updated successfully.');

        return redirect(route('transactions.index'));
    }

    /**
     * Remove the specified Transaction from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $transaction = $this->transactionRepository->findWithoutFail($id);

        if (empty($transaction)) {
            Flash::error('Transaction not found');

            return redirect(route('transactions.index'));
        }

        $this->transactionRepository->delete($id);

        Flash::success('Transaction deleted successfully.');

        return redirect(route('transactions.index'));
    }
    
    public function performDividen($id)
    {
      
      $tenancyRepository = \App::make(\App\Repositories\TenancyRepository::class);
      
      $transaction = Transaction::with('transactionable')->with('transactionable.offer')
          ->with('transactionable.property')->with('transactionable.property.propertyProAcceptedRequests')
          ->with('transactionable.payinLandlord')
          ->with('payout')
          ->with('childTransactions')
          ->with('childTransactions.payin')
          ->with('childTransactions.payin.user')
          ->findorfail($id);
      
      if ($transaction->status == Transaction::STATUS_DONE) {
        if ($transaction->title == Transaction::TITLE_FIRST_RENT) {
          $tenancyRepository->payinSecurityDepositToLandlord($transaction->transactionable, $transaction);
        }
        $tenancyRepository->performDividenTransaction($transaction);
      }
      
      Flash::success('Dividen payed out');
      
      return redirect(route('transactions.show', [$id]));
    }
}
