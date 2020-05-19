@extends('layouts.app')

@section('content')
  <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>


<section class="content-header">
        <h1 class="pull-left">Messages Client</h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    <div class="row">
            <div class="col-lg-8 col-lg-offset-2" >
              <div id="messages" ></div>
            </div>
        </div>
            </div>
        </div>
    </div>
     
 
    <div class="container">
        
    </div>
    <script>
      // Enable pusher logging - don't include this in production
      var url = '{{ url('/') }}:8890';
      console.info('url',url);
      var token = '{{ env("SMOOR_ADMIN_ACCESS_TOKEN") }}';
      var socket = io.connect(url, {
        'secure': true,
        'reconnect': true,
        'reconnection delay': 500,
        'max reconnection attempts': 10
      });
      socket.on('connect', function () {
        console.info("on connect");
        socket.emit('authenticate', {token: token, id: '{{ Auth::user()->id }}' });
      });
      socket.on("disconnect", function(){
          console.log("client disconnected from server");
      });
      var chosenEvent = 'private-App.User.{{ Auth::user()->id }}'; 
      console.info(chosenEvent);
      socket.on(chosenEvent, function (data) {
        console.log(data);
      });
    </script>
@endsection

