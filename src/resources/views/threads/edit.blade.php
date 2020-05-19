@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Thread
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($thread, ['route' => ['threads.update', $thread->id], 'method' => 'patch']) !!}

                        @include('threads.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection