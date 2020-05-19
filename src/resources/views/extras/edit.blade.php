@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Extra
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($extra, ['route' => ['extras.update', $extra->id], 'method' => 'patch']) !!}

                        @include('extras.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection