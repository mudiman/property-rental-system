<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Repositories\UserRepository;
use App\Models\User;
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
use Illuminate\Support\Facades\Hash;

class UserController extends AppBaseController
{
    /** @var  UserRepository */
    private $userRepository;

    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * Display a listing of the User.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $input = new InputSource($_GET);
        $provider = new EloquentDataProvider(User::class);
            
        $components = [
          (new Column('profile_picture', 'Profile Image'))->setValueFormatter(function($value) {
              return "<img src = '$value' onerror=\"this.src='../img/imagenotfound.png'\" height=\"90\" width=\"90\"/>";
          }),
          new Column('id'),
          new Column('first_name'),
          new Column('email'),
          new Column('role'),
          new Column('mobile'),
          new Column('status'),
          new Column('verified'),
          new Column('admin_verified'),
          (new Column('Actions'))->setValueCalculator(function ($row) {
            $edit = route('users.edit', [$row->id]);
            $view = route('users.show', [$row->id]);
            $delete = route('users.destroy', [$row->id]);
            $document = route('documents.index', ['documentable_id'=> $row->id, 'documentable_type' => User::morphClass]);
            $image = route('images.index', ['imageable_id'=> $row->id, 'imageable_type' => User::morphClass]);
            $properties = route('properties.index', ['landlord'=> $row->first_name]);
            $tenancies = route('tenancies.index', ['tenant'=> $row->first_name]);
            $payins = route('payins.index', ['user'=> $row->first_name]);
            $payouts = route('payouts.index', ['user'=> $row->first_name]);
            $deleteCsrfToken = \Collective\Html\FormFacade::token();
              $buttons = <<<EOF
                <form action="$delete" method="POST" accept-charset="UTF-8">
                  <input name="_method" type="hidden" value="DELETE">
                  $deleteCsrfToken
                  <div class='btn-group'> 
                      <a href="$document" class='btn btn-default btn-xs' data-toggle="tooltip" title="View documents"><i class="glyphicon glyphicon-book"></i></a>
                      <a href="$image" class='btn btn-default btn-xs' data-toggle="tooltip" title="View Images"><i class="glyphicon glyphicon-camera"></i></a>
                      <a href="$properties" class='btn btn-default btn-xs' data-toggle="tooltip" title="View properties"><i class="fa fa-home"></i></a>
                      <a href="$tenancies" class='btn btn-default btn-xs' data-toggle="tooltip" title="View tenancies"><i class="glyphicon glyphicon-scissors"></i></a>
                      <a href="$payins" class='btn btn-default btn-xs' data-toggle="tooltip" title="View payins"><i class="fa fa-credit-card"></i></a>
                      <a href="$payouts" class='btn btn-default btn-xs' data-toggle="tooltip" title="View payouts"><i class="fa fa-credit-card"></i></a>
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
          new Part(new Tag('td'), 'c1-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-id-column']), 'c2-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c3-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c4-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-sm-column']), 'c5-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c6-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c7-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c8-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c9-row', 'control_row2'),
          new Part(new Tag('td', ['class' => 'grid-column']), 'c10-row', 'control_row2'),
          new ColumnSortingControl('id', $input->option('sort')),
          new ColumnSortingControl('first_name', $input->option('sort')),
          new ColumnSortingControl('role', $input->option('sort')),
          new ColumnSortingControl('status', $input->option('sort')),
          new ColumnSortingControl('verified', $input->option('sort')),
          new ColumnSortingControl('admin_verified', $input->option('sort')),
          (new FilterControl('id', FilterOperation::OPERATOR_EQ, $input->option('id'), new TemplateView('input', [
            'label' => null,
            'placeholder' => 'id',
            'inputType' => 'number',
            'width' => '10px',
            'class' => 'grid-custom-filter-id',
          ])))->setDestinationParentId('c2-row'),
          (new FilterControl('first_name', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('first_name'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'first name',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c3-row'),
            (new FilterControl('email', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('email'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'email',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c4-row'),
          (new FilterControl('role', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('role'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'role',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c5-row'),
            (new FilterControl('mobile', FilterOperation::OPERATOR_STR_CONTAINS, $input->option('mobile'), new TemplateView('input', [
        'label' => null,
        'placeholder' => 'mobile',
        'inputType' => 'text',
        'class' => 'grid-custom-filter'
          ])))->setDestinationParentId('c6-row'),
          new Part(new TagWithText('button', '<i class="fa fa-filter"></i> Filter', ['type' => 'submit', 'class' => 'btn']), 'submit_button', 'c10-row'),
          (new ResetButton('<i class="fa fa-repeat"></i> Reset', ['class' => 'btn']))->setDestinationParentId('c10-row'),
          (new CsvExport($input('csv')))->setDestinationParentId('c10-row'),
          new PaginationControl($input->option('page', 1), 10),
        ];

        $components[1]->getDataCell()->setAttribute('class', 'grid-column');
        $components[2]->getDataCell()->setAttribute('class', 'grid-id-column');
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

        return view('users.index')
            ->with('grid', $grid);
    }

    /**
     * Show the form for creating a new User.
     *
     * @return Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created User in storage.
     *
     * @param CreateUserRequest $request
     *
     * @return Response
     */
    public function store(CreateUserRequest $request)
    {
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        $user = $this->userRepository->create($input);

        Flash::success('User saved successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Display the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.show')->with('user', $user);
    }

    /**
     * Show the form for editing the specified User.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }

        return view('users.edit')->with('user', $user);
    }

    /**
     * Update the specified User in storage.
     *
     * @param  int              $id
     * @param UpdateUserRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateUserRequest $request)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        $input = $request->all();
        if (isset($input['password']) && !empty($input['password'])) {
          $input['password'] = Hash::make($input['password']);
        } else {
          unset($input['password']);
        }
        $user = $this->userRepository->update($input, $id);

        Flash::success('User updated successfully.');

        return redirect(route('users.index'));
    }

    /**
     * Remove the specified User from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $user = $this->userRepository->findWithoutFail($id);

        if (empty($user)) {
            Flash::error('User not found');

            return redirect(route('users.index'));
        }
        
        foreach($user->tokens as $token) {
          $token->revoke();
          $token->delete(); 
        }
        $this->userRepository->delete($id);

        Flash::success('User deleted successfully.');

        return redirect(route('users.index'));
    }
}
