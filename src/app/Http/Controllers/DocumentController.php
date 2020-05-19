<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Repositories\DocumentRepository;
use App\Http\Controllers\AppBaseController;
use App\Models\Document;
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
use Illuminate\Support\Facades\Storage;

class DocumentController extends AppBaseController
{
    /** @var  DocumentRepository */
    private $documentRepository;

    public function __construct(DocumentRepository $documentRepo)
    {
        $this->documentRepository = $documentRepo;
    }

    /**
     * Display a listing of the Document.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $provider = new EloquentDataProvider(Document::class);
            
        $components = [
          new Column('id'),
          new Column('documentable_id'),
          new Column('documentable_type'),
          new Column('name'),
          new Column('type'),
          new Column('verified'),
          new Column('issuing_country'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('documents.edit', [$row->id]);
            $view = route('documents.show', [$row->id]);
            $main = $front = $back = '';
            if (isset($row->filename)) {
              $main = "<a href='".route('documents.get.document', [$row->id, 'main'])."' class='btn btn-default btn-xs'><i class=''>Main</i></a>";
            }
            if (isset($row->file_front_filename)) {
              $front = "<a href='".route('documents.get.document', [$row->id, 'front'])."' class='btn btn-default btn-xs'><i class=''>Front</i></a>";
            }
            if (isset($row->file_back_filename)) {
              $back = "<a href='".route('documents.get.document', [$row->id, 'back'])."' class='btn btn-default btn-xs'><i class=''>Back</i></a>";
            }
            $delete = route('documents.destroy', [$row->id]);
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
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('documentable_id', $input->option('sort')),
          new ColumnSortingControl('documentable_type', $input->option('sort')),
          new ColumnSortingControl('issuing_country', $input->option('sort')),
          new ColumnSortingControl('verified', $input->option('sort')),
            (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'width' => '10px',
            'class' => 'grid-custom-filter-id',
          ])))->setDestinationParentId('c1-row'),
          (new FilterControl('documentable_id', FilterOperation::OPERATOR_EQ, $input->option('documentable_id'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'documentable id',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c2-row'),
          (new SelectFilterControl(
              'documentable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('documentable_type')
          ))->setDestinationParentId('c3-row')->enableAutoSubmitting(),
            (new FilterControl('name', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('name'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'name',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
            (new FilterControl('type', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('type'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'type',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c5-row'),
          (new FilterControl('issuing_country', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('issuing_country'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'issuing country',
            'inputType' => 'text',
            'width' => '10px',
            'class' => 'grid-custom-filter-id',
          ])))->setDestinationParentId('c7-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c8-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c8-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c8-row'),
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

        return view('documents.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Document.
     *
     * @return Response
     */
    public function create()
    {
        return view('documents.create');
    }

    /**
     * Store a newly created Document in storage.
     *
     * @param CreateDocumentRequest $request
     *
     * @return Response
     */
    public function store(CreateDocumentRequest $request)
    {
        $input = $request->all();

        $document = $this->documentRepository->create($input);

        Flash::success('Document saved successfully.');

        return redirect(route('documents.index'));
    }

    /**
     * Display the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents.index'));
        }

        return view('documents.show')->with('document', $document);
    }

    /**
     * Show the form for editing the specified Document.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents.index'));
        }

        return view('documents.edit')->with('document', $document);
    }

    /**
     * Update the specified Document in storage.
     *
     * @param  int              $id
     * @param UpdateDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocumentRequest $request)
    {
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents.index'));
        }

        $document = $this->documentRepository->update($request->all(), $id);

        Flash::success('Document updated successfully.');

        return redirect(route('documents.index'));
    }

    /**
     * Remove the specified Document from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $document = $this->documentRepository->findWithoutFail($id);

        if (empty($document)) {
            Flash::error('Document not found');

            return redirect(route('documents.index'));
        }

        $this->documentRepository->delete($id);

        Flash::success('Document deleted successfully.');

        return redirect(route('documents.index'));
    }
    
    
    public function showDocument($id, $type)
    {
      $document = $this->documentRepository->findWithoutFail($id);
      if (empty($document)) {
          return $this->sendError('Document not found');
      }
      switch ($type)
      {
        case 'main':
          $filename = $document->filename;
          $mimeType = $document->mimetype;
          break;
        case 'front':
          $filename = $document->file_front_filename;
          $mimeType = $document->file_front_mimetype;
          break;
        case 'back':
          $filename = $document->file_back_filename;
          $mimeType = $document->file_back_mimetype;
          break;
      }
      if (empty($document) || !Storage::cloud('s3_document')->exists($filename)) {
          return $this->sendError('Document not found');
      }
      return response(Storage::cloud('s3_document')->get($filename))
          ->withHeaders([
              'Content-Type' => $mimeType,
          ]);
    }
}
