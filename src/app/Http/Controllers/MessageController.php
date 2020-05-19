<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Repositories\MessageRepository;
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
use LRedis;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Models\Thread;
use App\Models\Participant;
use App\Jobs\SendMessage;
use App\Events\NewMessage;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class MessageController extends AppBaseController
{
    use DispatchesJobs;
    /** @var  MessageRepository */
    private $messageRepository;

    public function __construct(MessageRepository $messageRepo)
    {
        $this->messageRepository = $messageRepo;
    }

    /**
     * Display a listing of the Message.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
      $input = new InputSource($_GET);
      $query = null;
      if (!$query) {
        $query = Message::class;
      }
      $provider = new EloquentDataProvider($query);
      $components = [
        new Column('id'),
        (new Column('user'))->setValueCalculator(function($row) {
            return isset($row->ByUser) ? $row->ByUser->first_name:'';
          }),
        new Column('thread_id'),
        new Column('title'),
        new Column('message'),
        new Column('status'),
        new Column('messageable_id'),
        new Column('messageable_type'),
        new Column('archived'),
        
        (new Column('Actions'))->setValueCalculator(function ($row) {
          $edit = route('messages.edit', [$row->id]);
          $view = route('messages.show', [$row->id]);
          $delete = route('messages.destroy', [$row->id]);
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
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c3-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-column']), 'c5-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c6-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-id-column']), 'c7-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-column']), 'c8-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c9-row', 'control_row2'),
        new Part(new Tag('td', ['class' => 'grid-m-column']), 'c10-row', 'control_row2'),
        new ColumnSortingControl('id', $input->option('sort')),
        new ColumnSortingControl('thread_id', $input->option('sort')),
        new ColumnSortingControl('messageable_id', $input->option('sort')),
        new ColumnSortingControl('messageable_type', $input->option('sort')),
        new ColumnSortingControl('status', $input->option('sort')),
        new ColumnSortingControl('archived', $input->option('sort')),
        (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
          'label' => null,
          'placeholder' => 'id',
          'inputType' => 'number',
          'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c1-row'),
        (new FilterControl('by_user', FilterOperation::OPERATOR_EQ, $input->option('by_user'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'by_user',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c2-row'),
        (new FilterControl('thread_id', FilterOperation::OPERATOR_EQ, $input->option('thread_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'thread_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c3-row'),
          (new FilterControl('title', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('title'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'title',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c4-row'),
          (new FilterControl('message', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('message'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'message',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c5-row'),
          (new FilterControl('status', FilterOperation::OPERATOR_EQ, $input->option('status'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'status',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c6-row'),
          (new FilterControl('messageable_id', FilterOperation::OPERATOR_EQ, $input->option('messageable_id'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'messageable_id',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c7-row'),
          (new SelectFilterControl(
              'messageable_type',
              array_combine(array_prepend(array_keys(Relation::morphMap()), ''), array_prepend(array_keys(Relation::morphMap()), '')),
             $input->option('messageable_type')
          ))->setDestinationParentId('c8-row')->enableAutoSubmitting(),
          (new FilterControl('archived', FilterOperation::OPERATOR_EQ, $input->option('archived'), new TemplateView('input', [
      'label' => null,
      'placeholder' => 'archived',
      'inputType' => 'text',
      'class' => 'grid-custom-filter'
        ])))->setDestinationParentId('c9-row'),
       
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

      $components[1]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[2]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[3]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[4]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[5]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[6]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[7]->getTitleCell()->setAttribute('class', 'grid-column');
      $components[8]->getTitleCell()->setAttribute('class', 'grid-column');

      $grid = new Grid($provider, $components);

      $customization = new BootstrapStyling();
      $customization->apply($grid);

      return view('messages.index')->with('grid', $grid);
    }

    /**
     * Show the form for creating a new Message.
     *
     * @return Response
     */
    public function create()
    {
        return view('messages.create');
    }

    /**
     * Store a newly created Message in storage.
     *
     * @param CreateMessageRequest $request
     *
     * @return Response
     */
    public function store(CreateMessageRequest $request)
    {
        $input = $request->all();
        if (empty($input['thread_id'])) {
          $thread = new Thread();
          $thread->title = 'thread'.$input['by_user'];
          $thread->save();
          $input['thread_id'] = $thread->id;
          $participant = new Participant();
          $participant->thread_id = $thread->id;
          $participant->user_id = $input['by_user'];
          $participant->save();
        }
        $message = $this->messageRepository->create($input);

        Flash::success('Message saved successfully.');

        return redirect(route('messages.index'));
    }

    /**
     * Display the specified Message.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            Flash::error('Message not found');

            return redirect(route('messages.index'));
        }

        return view('messages.show')->with('message', $message);
    }

    /**
     * Show the form for editing the specified Message.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            Flash::error('Message not found');

            return redirect(route('messages.index'));
        }

        return view('messages.edit')->with('message', $message);
    }

    /**
     * Update the specified Message in storage.
     *
     * @param  int              $id
     * @param UpdateMessageRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMessageRequest $request)
    {
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            Flash::error('Message not found');

            return redirect(route('messages.index'));
        }

        $message = $this->messageRepository->update($request->all(), $id);

        Flash::success('Message updated successfully.');

        return redirect(route('messages.index'));
    }

    /**
     * Remove the specified Message from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $message = $this->messageRepository->findWithoutFail($id);

        if (empty($message)) {
            Flash::error('Message not found');

            return redirect(route('messages.index'));
        }

        $this->messageRepository->delete($id);

        Flash::success('Message deleted successfully.');

        return redirect(route('messages.index'));
    }
    
    /**
     * Show the form for listening a new Message.
     *
     * @return Response
     */
    public function client()
    {
        return view('messages.client');
    }
}
