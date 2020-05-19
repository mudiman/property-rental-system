@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Agent
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($agent, ['route' => ['agents.update', $agent->id], 'method' => 'patch']) !!}

                        @include('agents.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection