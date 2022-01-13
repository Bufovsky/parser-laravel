@extends('layout.master')

@section('title', 'Frazy')

@push('footer')
	<script type="text/javascript" src="{{ asset('js/estimate.js') }}"></script>
@endpush

@section('content')
	@if(collect($phrases)->count() > 0)
		@include('partials.pagination', ['array' => $phrases])

		<hr>

		@if($estimate != '00:00:00')
			<div class="estimate" style="padding-bottom:0px;"><div class="alert alert-primary alert-dismissible">Przybliżony czas parsacji fraz: <b>{{ $estimate }}</b></div></div>
			<hr>
		@endif

		<div class="table-responsive">
			<table class="table table-striped card">
				<thead>
					<tr class="table-active">
						<th width="5%">ID</th>
						<th width="20%">Dodano</th>
						<th width="50%">Fraza</th>
						<th width="5%">Dodano</th>
						<th width="5%">Sprawdzono</th>
						<th width="5%">Zaimportowano</th>
						<th width="5%">Export</th>
						<th width="5%">Usuwanie</th>
					</tr>
				</thead>
				<tbody>
					@foreach($phrases as $phrase)
						<tr>
							<td>{{$phrase->id}}</td>
							<td>{{$phrase->created_at}}</td>
							<td><a href="{{ route('phrase.show', $phrase->id) }}"><h4 class="font-weight-semibold mb-0">{{$phrase->phrase}}</h4></a></td>
							<td></td><td></td><td></td>
							<td>
								<form method="POST" action="{{ route('phrase.export', $phrase->id) }}">
									{!! csrf_field() !!}
									<button type="submit" class="badge badge-primary">EXPORT</button>
								</form>
							</td>
							<td>
								<form method="POST" action="{{ route('phrase.delete', $phrase->id) }}">
									{!! csrf_field() !!}
									{{ method_field('DELETE') }}
									<button type="submit" class="badge badge-danger" onclick="confirm('Czy na pewno chcesz usunąć frazę {{$phrase->phrase}}?')">USUŃ</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<hr>

		@include('partials.pagination', ['array' => $phrases])
	@else
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<h3 class="card-title">Brak dodanych fraz.</h3>
		</div>
	@endif
@endsection