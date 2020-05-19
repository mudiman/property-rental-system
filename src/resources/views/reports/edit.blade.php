@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Report
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($report, ['route' => ['reports.update', $report->id], 'method' => 'patch']) !!}

                        @include('reports.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection