	<div class="widget-box transparent">

		<div class="profile-user-info profile-user-info-striped">
			<div class="profile-info-row">
				<div class="profile-info-name"> All time quantity bought </div>

				<div class="profile-info-value">
					<i class="icon-shopping-cart light-green bigger-110"></i>
					<span id="alltimequantity">
						@if($customerlog['alltime_quantity'] != null || $customerlog['alltime_quantity'] != 0)
						 {{{$customerlog['alltime_quantity']}}}
						@else
						 0
						@endif
					</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> All time visits </div>

				<div class="profile-info-value">
					<i class="icon-coffee light-green bigger-110"></i>
					<span id="alltimevisits">
						@if($customerlog['alltime_visits'] != null || $customerlog['alltime_visits'] != 0)
						 {{{$customerlog['alltime_visits']}}}
						@else
						 Never visted
						@endif
					</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Last visit </div>

				<div class="profile-info-value">
					<i class="icon-time light-green bigger-110"></i>
					<span id="lastvisit">
					@if($customerlog['updated_at'] != null || $customerlog['updated_at'] != 0)
						{{{format_date($customerlog['updated_at'])}}} </span>
					@else
						Never visited
					@endif
				</div>
			</div>
	</div>