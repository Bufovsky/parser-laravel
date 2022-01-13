@extends('layout.master')

@section('title', 'Logowanie')

@section('content')
	<div class="content-wrapper loginForm">
		<div class="content d-flex justify-content-center align-items-center">
			<form class="login-form" action="{{ route('login') }}" method="POST">
				{!! csrf_field() !!}
				
				<div class="card mb-0">
					<div class="card-body">
						<div class="text-center mb-3">
							<i class="icon-reading icon-2x text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
							<h5 class="mb-0">Zaloguj się na konto</h5>
							
							<span class="d-block text-muted">Wprowadź poniżej dane logowania</span>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="email" type="text" class="{{ $errors->has('email') ? 'border-danger' : '' }} form-control" placeholder="Użytkownik" value="{{ old('email') }}" required="">
							<div class="form-control-feedback">
								<i class="icon-user text-muted"></i>
							</div>
						</div>

						<div class="form-group form-group-feedback form-group-feedback-left">
							<input name="password" type="password" class="{{ $errors->has('password') ? 'border-danger' : '' }} form-control" placeholder="Hasło" required="">
							<div class="form-control-feedback">
								<i class="icon-lock2 text-muted"></i>
							</div>
						</div>

						<div class="form-group">
							<input type="submit" class="btn btn-primary btn-block" value="Zaloguj">
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection