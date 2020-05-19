@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Offer
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($offer, ['route' => ['offers.update', $offer->id], 'method' => 'patch']) !!}

                        @include('offers.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection