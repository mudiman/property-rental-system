@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Like
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($like, ['route' => ['likes.update', $like->id], 'method' => 'patch']) !!}

                        @include('likes.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection