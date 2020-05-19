@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Score
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($score, ['route' => ['scores.update', $score->id], 'method' => 'patch']) !!}

                        @include('scores.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection