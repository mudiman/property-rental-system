@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Viewing Request
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($viewingRequest, ['route' => ['viewingRequests.update', $viewingRequest->id], 'method' => 'patch']) !!}

                        @include('viewing_requests.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection