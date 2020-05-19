@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Event
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($event, ['route' => ['events.update', $event->id], 'method' => 'patch']) !!}

                        @include('events.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection