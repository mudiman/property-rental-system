@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Document
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($document, ['route' => ['documents.update', $document->id], 'method' => 'patch']) !!}

                        @include('documents.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection