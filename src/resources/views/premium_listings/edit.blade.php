@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Premium Listing
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($premiumListing, ['route' => ['premiumListings.update', $premiumListing->id], 'method' => 'patch']) !!}

                        @include('premium_listings.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection