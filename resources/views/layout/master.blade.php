<!DOCTYPE html>
<html lang="pl-PL">
    <head>
        <title>@yield('title') - Parser</title>

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex,nofollow">
		<link rel="icon" href="{{ asset('assets/images/favicon.ico') }}">

        <!-- Global stylesheets -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/icons/icomoon/styles.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/core.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('assets/css/components.css') }}" rel="stylesheet" type="text/css">
		<link href="{{ asset('assets/css/colors.css') }}" rel="stylesheet" type="text/css">
		<script type="text/javascript" src="{{ asset('assets/js/plugins/quantumAlert/quantumalert.js') }}"></script>
        <!-- /global stylesheets -->
    </head>
    <body>
		<!-- Main navbar -->
		<div class="navbar navbar-default header-highlight">
			<div class="navbar-header">
				<a href="{{ url('/') }}" class="navbar-brand"><img src="{{ asset('assets/images/logo.png') }}" alt=""></a>
				<ul class="nav navbar-nav d-md-none">
					<!--<li><a data-toggle="collapse" data-target="#navbar-mobile"><i class="icon-tree5"></i></a></li> -->
					<li><a class="sidebar-mobile-main-toggle"><i class="icon-paragraph-justify3"></i></a></li>
				</ul>
			</div>
		</div>
		<!-- /main navbar -->

		<!-- Page container -->
		<div class="page-container">

			<!-- Page content -->
			<div class="page-content">

				<!-- Main sidebar -->
				<div class="sidebar sidebar-main {{ Request::is('login') ? 'hidden' : '' }}">
					<div class="sidebar-content">
						@auth
							<!-- Main navigation -->
							<div class="sidebar-category sidebar-category-visible">
								<div class="category-content no-padding">
									<ul class="navigation navigation-main navigation-accordion">
										
										<!-- Menu -->
										<li class="navigation-header"><span>MENU</span> <i class="icon-menu" title="Aplikacja"></i></li>
										<li><a href="{{ route('phrase.create') }}"><i class="icon-copy"></i> <span>Dodaj Frazy</span></a></li>
										<li><a href="{{ route('phrase.index') }}"><i class="icon-stack2"></i> <span>Wyświetl Frazy</span></a></li>

										<li class="navigation-header"><span>Wykluczone</span> <i class="icon-menu" title="Wykluczone"></i></li>
										<li><a href="{{ route('exclude.create') }}"><i class="icon-unlink"></i> <span>Dodaj Wykluczone</span></a></li>
										<li><a href="{{ route('exclude.index') }}"><i class="icon-stack-cancel"></i> <span>Wykluczone URL</span></a></li>

										<li class="navigation-header"><span>Użytkownicy</span> <i class="icon-menu" title="Konta"></i></li>
										<li><a href="{{ route('account.create') }}"><i class="icon-user-plus"></i> <span>Dodaj użytkownika</span></a></li>
										<li><a href="{{ route('account.index') }}"><i class="icon-profile"></i> <span>Wyświetl użytkowników</span></a></li>

									</ul>

									@if(Informations::index('serp') == 1)
										<div class="card card-body has-bg-image serp-info bg-pink-400">
											<div class="media">
												<div class="mr-3 align-self-center">
													<i class="icon-spam icon-1x"></i>
												</div>

												<div class="media-body text-right">
													<span class="text-uppercase font-size-xs">Odnów subskrybcję SERP</span>
												</div>
											</div>
										</div>
									@endif

									<div class="card card-body current-stats opacity-25">
										<div class="media">
											<div class="mr-3 align-self-center">
												<i class="icon-checkmark-circle icon-1x"></i>
											</div>

											<div class="media-body text-right">
												<h4 class="mb-0"></h4>
												<span class="text-uppercase font-size-xs">{!! Informations::index('phrase') !!} ({!! Informations::index('position') !!})</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- /main navigation -->
							@endauth
					</div>
				</div>
				<!-- /main sidebar -->

				<!-- Main content -->
				<div class="content-wrapper">

					<div class="page-header page-header-light">
						<div class="page-header-content header-elements-md-inline">
							<div class="page-title d-flex">
								<h5>
									<i class="icon-arrow-left52 mr-2" onclick="window.history.go(-1);"></i> <span class="font-weight-semibold">@yield('title')</span> - Parser
									<small class="d-block text-muted">Panel administracyjny</small>
								</h5>
								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>

							@auth
								<div class="header-elements d-none">
									<form action="#">
										<div class="form-group-feedback form-group-feedback-right">
											<input type="search" class="form-control wmin-md-200" placeholder="Search">
											<div class="form-control-feedback">
												<i class="icon-search4 font-size-sm text-muted"></i>
											</div>
										</div>
									</form>
								</div>
							@endauth
						</div>

						<div class="breadcrumb-line breadcrumb-line-light">
							<div class="breadcrumb">
								{!! Breadcrumbs::index() !!}
							</div>
						</div>
					</div>
					
					<div class="content">
						@yield('content')
					</div>

				</div>
				<!-- /main content -->

			</div>
			<!-- /page content -->

		</div>
		<!-- /page container -->
		
		@include('partials.message')
	</body>
	
	
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/jquery.min.js') }}"></script>
	@stack('footer')

	<!-- Core JS files -->
	<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/pace.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/core/libraries/bootstrap.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/loaders/blockui.min.js') }}"></script>
	<!-- /core JS files -->
	
	<!-- Theme JS files -->
	<script type="text/javascript" src="{{ asset('assets/js/plugins/visualization/d3/d3.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/visualization/d3/d3_tooltip.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/switchery.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/styling/uniform.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/forms/selects/bootstrap_multiselect.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/ui/moment/moment.min.js') }}"></script>
	<script type="text/javascript" src="{{ asset('assets/js/plugins/pickers/daterangepicker.js') }}"></script>

	<script type="text/javascript" src="{{ asset('assets/js/core/app.js') }}"></script>

</html>