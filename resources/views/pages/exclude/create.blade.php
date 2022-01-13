@extends('layout.master')

@section('title', 'Dodaj wykluczone')

@section('content')
	
		<form action="{{ route('exclude.create') }}" method="POST">
			{!! csrf_field() !!}
			<div class="card">
				<div class="card-header bg-card-header header-elements-inline">
					<span class="card-title font-weight-semibold">Wprowadź wykluczone adresy</span>
				</div>
				
				<div class="content">
					<div class="row">
						<div class="col-md-12">
							<textarea row="3" cols="3" class="form-control" name="exclude" placeholder="Wprowadź wykluczone adresy oddzielone Enterem" required></textarea>
						</div>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-12">
					<input name="addExcludes" type="submit" value="Dodaj adresy" class="btn btn-primary full-width">
				</div>
			</div>
		</form>
	
@endsection