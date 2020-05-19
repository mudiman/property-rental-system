@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Service Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($serviceType, ['route' => ['serviceTypes.update', $serviceType->id], 'method' => 'patch']) !!}

                        @include('service_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection