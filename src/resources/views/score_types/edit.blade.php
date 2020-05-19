@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Score Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($scoreType, ['route' => ['scoreTypes.update', $scoreType->id], 'method' => 'patch']) !!}

                        @include('score_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection