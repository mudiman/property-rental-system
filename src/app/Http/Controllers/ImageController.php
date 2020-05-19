<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateImageRequest;
use App\Http\Requests\UpdateImageRequest;
use App\Repositories\ImageRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use ViewComponents\Eloquent\EloquentDataProvider;
use ViewComponents\ViewComponents\Data\Operation\FilterOperation;
use ViewComponents\ViewComponents\Component\Control\SelectFilterControl;
use Illuminate\Database\Eloquent\Relations\Relation;
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
use App\Models\Image;

class ImageController extends AppBaseController
{
    /** @var  ImageRepository */
    private $imageRepository;

    public function __construct(ImageRepository $imageRepo)
    {
        $this->imageRepository = $imageRepo;
    }

    /**
     * Display a listing of the Image.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $provider = new EloquentDataProvider(Image::class);
            
        $components = [
          new Column('id'),
          (new Column('path', 'Image'))->setValueFormatter(function($value) {
              return "<img src = '$value' onerror=\"this.src='../img/imagenotfound.png'\" height=\"90\" width=\"90\"/>";
          }),
          new Column('imageable_id'),
          new Column('imageable_type'),
          new Column('mimetype'),
          new Column('type'),
          new Column('filename'),
          new Column('primary'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('images.edit', [$row->id]);
            $view = route('images.show', [$row->id]);
            $main = $front = $back = '';
            $delete = route('images.destroy', [$row->id]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
              $buttons = <<<EOF
                <form action="$delete" method="POST" accept-charset="UTF-8">
                  <input name="_method" type="hidden" value="DELETE">
                  $deleteCsrfToken
                  <div class='btn-group'> 
                      $main
                      $front
                      $back
                      <a href="$view" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-eye-open"></i></a>
                      <a href="$edit" class='btn btn-default btn-xs'><i class="glyphicon glyphicon-edit"></i></a>
                      <button type="submit" class="btn btn-danger btn-xs hide" onclick="return confirm('Are you sure?')"><i class="glyphicon glyphicon-trash"></i></button>
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
          new Part(new Tag('td'), 'c1-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-id-column']), 'c1-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-m-column']), 'c2-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c9-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('imageable_id', $input->option('sort')),
          new ColumnSortingControl('imageable_type', $input->option('sort')),
          new ColumnSortingControl('type', $input->option('sort')),
          new ColumnSortingControl('primary', $input->option('sort')),
            (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'width' => '10px',
            'class' => 'grid-custom-filter-id',
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('imageable_id', FilterOperation::OPERATOR_EQ, $input->option('imageable_id'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'imageable id',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c3-row'),
          (new SelectFilterControl(
              'imageable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('imageable_type')
          ))->setDestinationParentId('c4-row')->enableAutoSubmitting(),
            (new FilterControl('type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
          (new FilterControl('mimetype', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('mimetype'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'mimetype',
            'inputType' => 'text',
            'width' => '10px',
            'class' => 'grid-custom-filter-id',
          ])))->setDestinationParentId('c5-row'),
            (new FilterControl('filename', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('filename'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'filename',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c7-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c9-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c9-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c9-row'),
          new PaginationControl($input->option('page', 1), 10),
        ];

        $components[1]->getDataCell()->setAttribute('class', 'grid-id-column');
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

        return view('images.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Image.
     *
     * @return Response
     */
    public function create()
    {
        return view('images.create');
    }

    /**
     * Store a newly created Image in storage.
     *
     * @param CreateImageRequest $request
     *
     * @return Response
     */
    public function store(CreateImageRequest $request)
    {
        $input = $request->all();

        $image = $this->imageRepository->create($input);

        Flash::success('Image saved successfully.');

        return redirect(route('images.index'));
    }

    /**
     * Display the specified Image.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('images.index'));
        }

        return view('images.show')->with('image', $image);
    }

    /**
     * Show the form for editing the specified Image.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('images.index'));
        }

        return view('images.edit')->with('image', $image);
    }

    /**
     * Update the specified Image in storage.
     *
     * @param  int              $id
     * @param UpdateImageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImageRequest $request)
    {
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('images.index'));
        }

        $image = $this->imageRepository->update($request->all(), $id);

        Flash::success('Image updated successfully.');

        return redirect(route('images.index'));
    }

    /**
     * Remove the specified Image from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $image = $this->imageRepository->findWithoutFail($id);

        if (empty($image)) {
            Flash::error('Image not found');

            return redirect(route('images.index'));
        }

        $this->imageRepository->delete($id);

        Flash::success('Image deleted successfully.');

        return redirect(route('images.index'));
    }
}
