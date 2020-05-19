<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePropertyProRequest;
use App\Http\Requests\UpdatePropertyProRequest;
use App\Repositories\PropertyProRepository;
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
use App\Models\PropertyPro;

class PropertyProController extends AppBaseController
{
    /** @var  PropertyProRepository */
    private $propertyProRepository;

    public function __construct(PropertyProRepository $propertyProRepo)
    {
        $this->propertyProRepository = $propertyProRepo;
    }

    /**
     * Display a listing of the PropertyPro.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    { 
        $input = new InputSource($_GET);
        $query = null;
        $landlordName = $propertyProName = '';
        if (isset($_GET['landlord']) && !empty($_GET['landlord'])) {
          list($query, $landlordName) = $this->applyUserFilter($query, Property::class, $_GET['landlord'], 'landlord_id');
        }
        if (isset($_GET['propertyPro']) && !empty($_GET['propertyPro'])) {
          list($query, $propertyProName) = $this->applyUserFilter($query, Property::class, $_GET['propertyPro'], 'property_pro_id');
        }
        if (!$query) {
          $query = PropertyPro::class;
        }
        $provider = new EloquentDataProvider($query);

        $components = [
          new Column('id'),
          (new Column('landlord'))->setValueCalculator(function($row) {
            return isset($row->landlord) ? $row->landlord->first_name:'';
          }),
          (new Column('propertyPro'))->setValueCalculator(function($row) {
            return isset($row->propertyPro) ? $row->propertyPro->first_name:'';
          }),
          new Column('property_id'),
          new Column('price_type'),
          new Column('price'),
          new Column('status'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('propertyPros.edit', [$row->id]);
            $view = route('propertyPros.show', [$row->id]);
            $delete = route('propertyPros.destroy', [$row->id]);
            $document = route('documents.index', ['documentable_id'=> $row->id, 'documentable_type' => PropertyPro::morphClass]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
            $buttons = <<<EOF
              <form action="$delete" method="POST" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                $deleteCsrfToken
                  <div class='btn-group'> 
                      <a href="$document" class='btn btn-default btn-xs' data-toggle="tooltip" title="View documents"><i class="glyphicon glyphicon-book"></i></a>
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
          new Part(new Tag('td', ['class' => 'grid-column']), 'c2-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('input', ['value' => $landlordName, 'type' => 'text', 'placeholder' => 
            'Landlord', 'class' => 'form-control grid-m-column', 'name' => 'landlord']), 'salon1', 'c2-row'),
          new Part(new Tag('input', ['value' => $propertyProName, 'type' => 'text', 'placeholder' => 
            'Property Pro', 'class' => 'form-control grid-m-column', 'name' => 'propertyPro']), 'salon2', 'c3-row'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c8-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('price_type', $input->option('sort')),
          new ColumnSortingControl('price', $input->option('sort')),
          new ColumnSortingControl('property_id', $input->option('sort')),
          new ColumnSortingControl('status', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('price_type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('price_type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'price type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c5-row'),
          (new FilterControl('price', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('price'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'price',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
          (new FilterControl('status', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('status'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'status',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c7-row'),
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


        return view('property_pros.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new PropertyPro.
     *
     * @return Response
     */
    public function create()
    {
        return view('property_pros.create');
    }

    /**
     * Store a newly created PropertyPro in storage.
     *
     * @param CreatePropertyProRequest $request
     *
     * @return Response
     */
    public function store(CreatePropertyProRequest $request)
    {
        $input = $request->all();

        $propertyPro = $this->propertyProRepository->create($input);

        Flash::success('Property Pro saved successfully.');

        return redirect(route('propertyPros.index'));
    }

    /**
     * Display the specified PropertyPro.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            Flash::error('Property Pro not found');

            return redirect(route('propertyPros.index'));
        }

        return view('property_pros.show')->with('propertyPro', $propertyPro);
    }

    /**
     * Show the form for editing the specified PropertyPro.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            Flash::error('Property Pro not found');

            return redirect(route('propertyPros.index'));
        }

        return view('property_pros.edit')->with('propertyPro', $propertyPro);
    }

    /**
     * Update the specified PropertyPro in storage.
     *
     * @param  int              $id
     * @param UpdatePropertyProRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePropertyProRequest $request)
    {
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            Flash::error('Property Pro not found');

            return redirect(route('propertyPros.index'));
        }

        $propertyPro = $this->propertyProRepository->update($request->all(), $id);

        Flash::success('Property Pro updated successfully.');

        return redirect(route('propertyPros.index'));
    }

    /**
     * Remove the specified PropertyPro from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $propertyPro = $this->propertyProRepository->findWithoutFail($id);

        if (empty($propertyPro)) {
            Flash::error('Property Pro not found');

            return redirect(route('propertyPros.index'));
        }

        $this->propertyProRepository->delete($id);

        Flash::success('Property Pro deleted successfully.');

        return redirect(route('propertyPros.index'));
    }
}
