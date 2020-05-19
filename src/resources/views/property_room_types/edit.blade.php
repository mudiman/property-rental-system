@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Property Room Type
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($propertyRoomType, ['route' => ['propertyRoomTypes.update', $propertyRoomType->id], 'method' => 'patch']) !!}

                        @include('property_room_types.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection