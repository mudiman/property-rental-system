@extends('errors.layout')

@section('content')
<main role="main" class="container">
		{{ $error or '' }}
    {{ isset($exception) ? $exception->getMessage(): '' }}
	</main>
@endsection
