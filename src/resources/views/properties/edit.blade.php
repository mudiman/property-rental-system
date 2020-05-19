@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Property
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($property, ['route' => ['properties.update', $property->id], 'method' => 'patch']) !!}

                        @include('properties.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection