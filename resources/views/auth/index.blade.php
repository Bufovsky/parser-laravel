@extends('layout.master')

@section('title', 'Użytkownicy')

@section('content')
	@if(collect($users)->count() > 0)
		<div class="table-responsive">
			<table class="table table-striped card">
				<thead>
					<tr class="table-active">
						<th width="5%" style="text-align:center;">#</th>
						<th width="20%">Dodano</th>
						<th width="55%">Email</th>
						<th width="20%">Edycja</th>
					</tr>
				</thead>
				<tbody>
					@foreach($users as $user)
						<tr>
							<td>
								<a class="btn bg-blue rounded-round btn-icon btn-sm">
									<span class="letter-icon">{{ $user->id }}</span>
								</a>
							</td>
							<td>{{ $user->created_at }}</td>
							<td>
								
								<div>
									<a class="text-default font-weight-semibold">{{ $user->name }}</a>
									<div class="text-muted font-size-sm">
										<span class="badge badge-mark border-blue mr-1"></span>
										{!! $accessName[$user->access] !!}
									</div>
								</div>
							</td>
							<td>
								<div class="btn-group btn-group-justified">
								<div class="btn-group">
									<a href="{{ route('account.edit', $user->id) }}"><button type="button" class="btn bg-primary-400 btn-labeled btn-labeled-left"><b><i class="icon-pencil"></i></b>Edytuj</button></a>
								</div>
								<div class="btn-group">
									<form method="POST" action="{{ route('account.delete', $user->id) }}">
										{!! csrf_field() !!}
										{{ method_field('DELETE') }}
										<button type="submit" class="btn bg-warning-400 btn-labeled btn-labeled-left" onclick="return confirm('Czy na pewno chcesz usunąć użytkownika: {{ $user->name }}?')"><b><i class="icon-bin"></i></b>Usuń</button>
									</form>
								</div>
								</div>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@else
		<div class="alert alert-info alert-styled-left alert-dismissible">
			<h3 class="card-title">Brak użytkowników.</h3>
		</div>
	@endif
@endsection