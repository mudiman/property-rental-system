@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            History
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($history, ['route' => ['histories.update', $history->id], 'method' => 'patch']) !!}

                        @include('histories.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection