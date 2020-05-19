@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            User Service
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($userService, ['route' => ['userServices.update', $userService->id], 'method' => 'patch']) !!}

                        @include('user_services.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection