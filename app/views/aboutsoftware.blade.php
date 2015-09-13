<div class="row-fluid">	
	<div class="span12 label label-inverse">
		<h1 class="center bolder">{{HTML::image('vendor/bucketcodes/img/small_logo.png', 'Activa logo by BUCKETCODES', array('width'=>'65px'))}} {{Config::get('software.karanamkiahe')}} <small> {{Config::get('software.version')}} </small></h1>
	</div>
</div>

<div class="row-fluid">
	<div class="span12 label label-purple">
		<div class="span4" style="text-align:right">
			<h5> Computer Name: </h5>
			<h5> Installed Date: </h5>
			<h5> License Type: </h5>
			<h5> License Key: </h5>
			@if($aboutsoftware['license']['type'] === 'Demo' )
			<h5> Remaining Days: </h5>
			@endif
		</div>

		<div class="span8">
			<h5> {{$aboutsoftware['pc-info']['hostname']}} </h5>
			<?php $c = new Libraries\Coded(); ?>
			<h5> {{$c->ini_moment($aboutsoftware['pc-info']['installed_time'])->format('jS M, Y')}} </h5>
			<h5> {{$aboutsoftware['license']['type']}} </h5>
			<h5> {{$aboutsoftware['license']['code']}} </h5>
			@if(strtolower( $aboutsoftware['license']['type'] ) === 'demo' )
			<h5> @if($aboutsoftware['demodays'] > 0 ){{$aboutsoftware['demodays']}}@else Expired @endif </h5>
			@endif
		</div>
	</div>
</div>
<hr>
<div class="row-fluid">	
	<div class="span12 label label-light">
		<div class="span6" style="text-align:right">
			<h5> Developer Company: </h5>
			<h5> Phone Number: </h5>
			<h5> Email Address: </h5>
		</div>

		<div class="span6">
			<h5> Bucketcodes Limited </h5>
			<h5> 08092293336 </h5>
			<h5> mytansill@yahoo.com </h5>
		</div>
	</div>
</div>