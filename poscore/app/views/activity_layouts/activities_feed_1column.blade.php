<div class="widget-box">
	<div class="widget-header header-color-green">
		<h4 class=""> <i class="icon-money bigger-110"></i> Recent stockupdate </h4>

		<div class="widget-toolbar no-border">
			<ul id="myTab" class="nav nav-tabs padding-32">
				<li class="active">
					<a href="#stockupdate_today" data-toggle="tab" class="ajaxable" data-mode="stockupdate_today" data-url={{URL::route('admindashboard')}} >
						<i class="icon-time bigger-120"></i>
						 Today
					</a>
				</li>

				<li class="">
					<a href="#stockupdate_yesterday" data-toggle="tab" class="ajaxable" data-mode="stockupdate_yesterday" data-url={{URL::route('admindashboard')}} >
					<i class="icon-time bigger-120"></i>
						Yesterday
					</a>
				</li>

				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						More...
						<b class="caret"></b>
					</a>

					<ul class="dropdown-menu dropdown-info">

						<li class="">
							<a href="#stockupdate_lastweek" data-toggle="tab" class="ajaxable" data-url={{URL::route('admindashboard')}} data-mode="stockupdate_lastweek">
							<i class="icon-time bigger-120"></i> Last week</a>
						</li>

						<li class="">
							<a href="#stockupdate_lastmonth" data-toggle="tab" class="ajaxable" data-url={{URL::route('admindashboard')}} data-mode="stockupdate_lastmonth">
							<i class="icon-time bigger-120"></i> Last month</a>
						</li>
					</ul>
				</li>

			</ul>

		</div>
	</div>

	<div class="widget-body">
		<div class="widget-main no-padding">
			<div class="no-padding overflow-visible tab-content">
				<div class="tab-pane active" id="stockupdate_today">
						@include('admin.stockupdate')
				</div>
			</div>
		</div>
	</div>
</div>