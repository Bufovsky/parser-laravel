{!! $array !!}


@if(ceil(collect($array)->count() / 100) > 1)
    <div class="row">
    	<div class="col-md-12">
            {{ $array->links() }}
        </div>
    </div>
@endif