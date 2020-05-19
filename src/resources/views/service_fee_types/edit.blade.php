@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Service Fee Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($serviceFeeType, ['route' => ['serviceFeeTypes.update', $serviceFeeType->id], 'method' => 'patch']) !!}

                        @include('service_fee_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection