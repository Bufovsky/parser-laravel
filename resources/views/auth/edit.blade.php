@extends('layout.master')
@section('title', 'Edytuj użytkownika')
@push('footer')
	<script type="text/javascript" src="{{ asset('js/register.js') }}"></script>
@endpush
@section('content')
<form class="form-horizontal" method="POST" action="{{ route('account.edit', $user->id) }}">
	<input type="hidden" name="id" value="{{ $user->id }}" required>

    <div class="card">
        <div class="card-header bg-card-header header-elements-inline">
            <span class="card-title font-weight-semibold">Wprowadź dane użytkownika</span>
        </div>

        <div class="card-body">
            {{ csrf_field() }}

            <div class="row{{ $errors->has('name') ? ' has-error' : '' }}">
                <label for="name" class="col-md-2 control-label text-right">Nazwa</label>

                <div class="col-md-10">
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input id="name" type="text" class="{{ $errors->has('name') ? 'border-danger' : '' }} form-control" name="name" value="{{ $user->name }}" required autofocus>

                        <div class="form-control-feedback">
                            <i class="icon-user text-muted"></i>
                        </div>
                    </div>

                    @if ($errors->has('name'))
                        <span class="help-block">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row{{ $errors->has('email') ? ' has-error' : '' }}">
                <label for="email" class="col-md-2 control-label text-right">E-Mail Adres</label>

                <div class="col-md-10">
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input id="email" type="email" class="{{ $errors->has('email') ? 'border-danger' : '' }} form-control" name="email" value="{{ $user->email }}" required>
                        
                        <div class="form-control-feedback">
                            <i class="icon-mention text-muted"></i>
                        </div>
                    </div>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-2 control-label text-right">Hasło</label>

                <div class="col-md-10">
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input id="password" type="password" class="{{ $errors->has('password') ? 'border-danger' : '' }} form-control" name="password">

                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="row">
                <label for="password-confirm" class="col-md-2 control-label text-right">Potwierdź hasło</label>

                <div class="col-md-10">
                    <div class="form-group form-group-feedback form-group-feedback-left">
                        <input id="password-confirm" type="password" class="{{ $errors->has('password') ? 'border-danger' : '' }} form-control" name="password_confirmation">

                        <div class="form-control-feedback">
                            <i class="icon-lock2 text-muted"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <label for="password-confirm" class="col-md-2 control-label text-right">Typ konta</label>

				<div class="col-md-10">
					<div class="btn-group btn-group-justified">
						<div class="btn-group">
							<button type="button" class="btn bg-slate" onclick="selectButton('access', 0)">Użytkownik</button>
						</div>
						<input type="radio" name="access" value="0" class="hidden" {{ $user->access == 0 ? 'checked' : '' }}/>

						<div class="btn-group">
							<button type="button" class="btn bg-slate" onclick="selectButton('access', 1)">Administrator</button>
						</div>
						<input type="radio" name="access" value="1" class="hidden" {{ $user->access == 1 ? 'checked' : '' }}/>
					</div>
				</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-primary full-width">
                Zarejestruj
            </button>
        </div>
    </div>
</form>
@endsection
