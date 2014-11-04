<div class="alert alert-danger no-margin">
	<h3 class="no-margin grey bolder">
		{{$count}} {{istr::title($type)}}(s)
	</h3>
</div>

@if(!empty($extracted))
<div class="alert bolder red">
	{{$extracted}}
</div>
@endif