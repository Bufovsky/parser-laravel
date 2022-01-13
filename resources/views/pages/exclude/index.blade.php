@extends('layout.master')

@section('title', 'Wykluczone adresy')

@section('content')
	@if(collect($excludes)->count() > 0)
		@include('partials.pagination', ['array' => $excludes])

		<hr>

		<div class="table-responsive">
			<table class="table table-striped card">
				<thead>
					<tr class="table-active">
						<th width="10%">#</th>
						<th width="20%">Dodano</th>
						<th width="60%">URL</th>
						<th width="10%">Usuń</th>
					</tr>
				</thead>
				<tbody>	
					@foreach($excludes as $element)
						<tr>
							<td>{{ $element->id }}</td>
							<td>{{ $element->created_at }}</td>
							<td><a href="//{{ $element->url }}" target="_blank">{{ $element->url }}</a></td>
							<td>
								<form method="POST" action="{{ route('exclude.delete', $element->id) }}">
									{!! csrf_field() !!}
									{{ method_field('DELETE') }}
									<button class="badge badge-danger" onclick="return confirm('Czy na pewno chcesz usunąć: <b>{{ $element->url }}</b> wykluczony adres?'">USUŃ</button>
								</form>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>

		<hr>

		@include('partials.pagination', ['array' => $excludes])
	@else
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<h3 class="card-title">Brak wykluczonych adresów.</h3>
		</div>
	@endif
@endsection