<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Repositories\PropertyRepository;
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
use App\Models\Property;
use App\Models\User;

class PropertyController extends AppBaseController
{
    /** @var  PropertyRepository */
    private $propertyRepository;

    public function __construct(PropertyRepository $propertyRepo)
    {
        $this->propertyRepository = $propertyRepo;
    }

    /**
     * Display a listing of the Property.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $query = null;
        $landlordName = '';
        if (isset($_GET['landlord']) && !empty($_GET['landlord'])) {
          list($query, $landlordName) = $this->applyUserFilter($query, Property::class, $_GET['landlord'], 'landlord_id');
        }
        if (!$query) {
          $query = Property::class;
        }
        $provider = new EloquentDataProvider($query);

        $components = [
          new Column('id'),
          (new Column('profile_picture', 'Profile Image'))->setValueFormatter(function($value) {
              return "<img src = '$value' onerror=\"this.src='../img/imagenotfound.png'\" height=\"90\" width=\"90\"/>";
          }),
          (new Column('landlord'))->setValueCalculator(function($row) {
            return isset($row->landlord) ? $row->landlord->first_name:'';
          }),
          new Column('title'),
          new Column('letting_type'),
          new Column('property_type'),
          new Column('room_suitable'),
          new Column('status'),
          new Column('completion_phase'),
          new Column('available_date'),
          new Column('postcode'),
          new Column('reference'),
          new Column('street'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('properties.edit', [$row->id]);
            $view = route('properties.show', [$row->id]);
            $delete = route('properties.destroy', [$row->id]);
            $document = route('documents.index', ['documentable_id'=> $row->id, 'documentable_type' => Property::morphClass]);
            $image = route('images.index', ['imageable_id'=> $row->id, 'imageable_type' => Property::morphClass]);
            $tenancies = route('tenancies.index', ['property_id'=> $row->id]);
            $offers = route('offers.index', ['property_id'=> $row->id]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
            $buttons = <<<EOF
              <form action="$delete" method="POST" accept-charset="UTF-8">
                <input name="_method" type="hidden" value="DELETE">
                $deleteCsrfToken
                  <div class='btn-group'> 
                      <a href="$document" class='btn btn-default btn-xs' data-toggle="tooltip" title="View documents"><i class="glyphicon glyphicon-book"></i></a>
                      <a href="$offers" class='btn btn-default btn-xs' data-toggle="tooltip" title="View offers"><i class="	glyphicon glyphicon-envelope"></i></a>
                      <a href="$tenancies" class='btn btn-default btn-xs' data-toggle="tooltip" title="View tenacies"><i class="glyphicon glyphicon-scissors"></i></a>
                      <a href="$image" class='btn btn-default btn-xs' data-toggle="tooltip" title="View Images"><i class="glyphicon glyphicon-camera"></i></a>
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
          new Part(new Tag('input', ['value' => $landlordName, 'type' => 'text', 'placeholder' => 'Landlord', 'class' => 'form-control grid-m-column', 'name' => 'landlord']), 'salon', 'c4-row'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c9-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c10-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c11-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c12-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c13-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c14-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c15-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('postcode', $input->option('sort')),
          new ColumnSortingControl('letting_type', $input->option('sort')),
          new ColumnSortingControl('property_type', $input->option('sort')),
          new ColumnSortingControl('available_date', $input->option('sort')),
          new ColumnSortingControl('status', $input->option('sort')),
          new ColumnSortingControl('completion_phase', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('title', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('title'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'title',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c5-row'),
          (new FilterControl('letting_type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('letting_type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'letting type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
          (new FilterControl('property_type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('property_type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'property type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c7-row'),
            (new FilterControl('postcode', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('postcode'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'postcode',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c12-row'),
          (new FilterControl('reference', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('reference'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'reference',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c13-row'),
          (new FilterControl('street', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('street'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'street',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c14-row'),
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

        $grid = new Grid($provider, $components);

        $customization = new BootstrapStyling();
        $customization->apply($grid);

        return view('properties.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Property.
     *
     * @return Response
     */
    public function create()
    {
        return view('properties.create');
    }

    /**
     * Store a newly created Property in storage.
     *
     * @param CreatePropertyRequest $request
     *
     * @return Response
     */
    public function store(CreatePropertyRequest $request)
    {
        $input = $request->all();

        $property = $this->propertyRepository->create($input);

        Flash::success('Property saved successfully.');

        return redirect(route('properties.index'));
    }

    /**
     * Display the specified Property.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        return view('properties.show')->with('property', $property);
    }

    /**
     * Show the form for editing the specified Property.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }
    
        $statuses =  [
          Property::STATUS_DRAFT => Property::STATUS_DRAFT,
          Property::STATUS_LIVE => Property::STATUS_LIVE,
          Property::STATUS_OCCUPIED => Property::STATUS_OCCUPIED,
        ];
        return view('properties.edit')->with('property', $property)->with('statuses', $statuses);
    }

    /**
     * Update the specified Property in storage.
     *
     * @param  int              $id
     * @param UpdatePropertyRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePropertyRequest $request)
    {
        $property = $this->propertyRepository->findWithoutFail($id);
        $input = $request->all();
        
        if (empty($property)) {
            Flash::error('Property not found');
            return redirect(route('properties.index'));
        }
        try {
          if (isset($input['status']) && $property->status != $input['status']) {
            $property->{'transition'.$input['status']}();
          }
        } catch (Exception $ex) {
            \App::abort(500, 'Validation failed in changing state');
        }
        $property = $this->propertyRepository->update($request->all(), $id);

        Flash::success('Property updated successfully.');

        return redirect(route('properties.index'));
    }

    /**
     * Remove the specified Property from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $property = $this->propertyRepository->findWithoutFail($id);

        if (empty($property)) {
            Flash::error('Property not found');

            return redirect(route('properties.index'));
        }

        $this->propertyRepository->delete($id);

        Flash::success('Property deleted successfully.');

        return redirect(route('properties.index'));
    }
}
