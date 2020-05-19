@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Document Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($documentType, ['route' => ['documentTypes.update', $documentType->id], 'method' => 'patch']) !!}

                        @include('document_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection