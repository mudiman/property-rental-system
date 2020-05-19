@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Payin
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($payin, ['route' => ['payins.update', $payin->id], 'method' => 'patch']) !!}

                        @include('payins.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection