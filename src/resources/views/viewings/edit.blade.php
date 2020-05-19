@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Viewing
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($viewing, ['route' => ['viewings.update', $viewing->id], 'method' => 'patch']) !!}

                        @include('viewings.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection