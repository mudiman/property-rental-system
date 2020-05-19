@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Feedback
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($feedback, ['route' => ['feedback.update', $feedback->id], 'method' => 'patch']) !!}

                        @include('feedback.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection