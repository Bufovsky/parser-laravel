@extends('layout.master')

@section('title', 'Fraza - Miasto')

@push('footer')
	<script type="text/javascript" src="{{ asset('assets/js/plugins/changeEmail/index.js') }}"></script>
@endpush

@section('content')
	@if(collect($urls)->count() > 0)
		<div class="table-responsive">
			<table class="table table-striped card">
				<thead>
					<tr class="table-active">
						<th width="5%">Pozycja</th>
						<th width="15%">Fraza</th>
						<th width="20%">Miasto</th>
						<th width="30%">Adres URL</th>
						<th width="20%">Email</th>
						<th width="10%">&#9989;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($urls as $url)
						<tr>
							<td>{{ $url->position }}</td>
							<td>{{ $phrase->phrase }}</td>
							<td>{{ $url->city }}</td>
							<td><a href="{{ $url->url }}" target="_blank">{{ $url->url }}</a></td>
							<td class="email{!! $url->id !!}" onclick="updateEmail('{!! $url->id !!}')">{{ $url->email }}</td>
							<td>{!! $checked[$url->checked] !!}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@else
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<h3 class="card-title">Brak adresów url.</h3>
		</div>
	@endif
@endsection