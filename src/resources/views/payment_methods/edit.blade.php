@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payment Method
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($paymentMethod, ['route' => ['paymentMethods.update', $paymentMethod->id], 'method' => 'patch']) !!}

                        @include('payment_methods.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection