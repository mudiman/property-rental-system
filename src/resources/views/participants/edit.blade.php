@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Participant
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($participant, ['route' => ['participants.update', $participant->id], 'method' => 'patch']) !!}

                        @include('participants.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection