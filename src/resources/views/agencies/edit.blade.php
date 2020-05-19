@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agency
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($agency, ['route' => ['agencies.update', $agency->id], 'method' => 'patch']) !!}

                        @include('agencies.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection