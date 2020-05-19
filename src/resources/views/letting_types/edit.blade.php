@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Letting Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($lettingType, ['route' => ['lettingTypes.update', $lettingType->id], 'method' => 'patch']) !!}

                        @include('letting_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection