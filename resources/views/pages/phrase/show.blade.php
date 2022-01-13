@extends('layout.master')

@section('title', 'Fraza')

@section('content')
	<div class="table-responsive">
		<table class="table table-striped card">
			<thead>
				<tr class="table-active">
					<th width="20%">Fraza</th>
					<th width="70%">Miasto</th>
					<th width="10%">&#9989;</th>
				</tr>
			</thead>
			<tbody>
			
				@if(collect($cities)->isNotEmpty())
					@foreach($cities as $city)
						@php ($cityUrl = empty($city->city) ? $phrase->phrase : $city->city)

						<tr>
							<td>{{ $phrase->phrase }}</td>
							<td><a href="{{ route('phrase.city', [$phrase->id, $cityUrl]) }}">{{ $cityUrl }}</a></td>
							<td>{!! $checked[$city->checked] !!}</td>
						</tr>
					@endforeach
				@endif
			
			</tbody>
		</table>
	</div>
@endsection