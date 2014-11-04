	<div class="widget-box transparent">

		<div class="profile-user-info profile-user-info-striped">
			<div class="profile-info-row">
				<div class="profile-info-name"> Full name </div>

				<div class="profile-info-value">
					<i class="icon-user @if( $staff['isloggedin'] === 1 )green @else red @endif "></i>
					<span id="name" data-pk="{{$staff['id']}}" class="editable editable-click"> {{{ucwords($staff['name'])}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Username </div>

				<div class="profile-info-value">
					<i class="icon-user light-orange bigger-110"></i>
					<span id="username" class=""> {{{$staff['username']}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Password </div>

				<div class="profile-info-value">
					<i class="icon-key light-orange bigger-110"></i>
					<span id="password" data-pk="{{$staff['id']}}" class="editable editable-click"> ****** </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Birthday </div>

				<div class="profile-info-value">
					<i class="icon-gift light-orange bigger-110"></i>
					<span id="birthday" data-pk="{{$staff['id']}}"  class="editable editable-click">{{{dob_date_format($staff['birthday'])}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Token-ID </div>

				<div class="profile-info-value">
					<i class="icon-filter light-orange bigger-110"></i>
					<span id="usertoken" class=""> {{{$staff['usertoken']}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Location </div>

				<div class="profile-info-value">
					<i class="icon-map-marker light-orange bigger-110"></i>
					<span id="houseaddress" data-pk="{{$staff['id']}}" class="editable editable-click">{{{$staff['houseaddress']}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Phone number </div>

				<div class="profile-info-value">
					<i class="icon-phone light-orange bigger-110"></i>
					<span id="phone" data-pk="{{$staff['id']}}" class="editable editable-click"> {{{$staff['phone']}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Email </div>

				<div class="profile-info-value">
					<i class="icon-phone light-orange bigger-110"></i>
					<span id="email" data-pk="{{$staff['id']}}" class="editable editable-click"> {{{$staff['email']}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Role </div>

				<div class="profile-info-value">
					<i class="icon-star light-orange bigger-110"></i>
					<span id="role" data-pk="{{$staff['id']}}" class="editable editable-click">{{{implode(', ',User::getUserRoleName($staff['id']))}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Profile created </div>

				<div class="profile-info-value">
					<i class="icon-calendar light-orange bigger-110"></i>
					<span id="signup" class=""> {{{format_date($staff['created_at'])}}} </span>
				</div>
			</div>

			@if($staff['isloggedin'] === 0)
			<div class="profile-info-row">
				<div class="profile-info-name"> Last Online </div>

				<div class="profile-info-value">
				<i class="icon-calendar light-orange bigger-110"></i>
					<span id="login" class="">@if($staff['loggedtime'] === '') Never logged in @else {{{format_date($staff['loggedtime'])}}} @endif</span>
				</div>
			</div>
			@endif

			<div class="profile-info-row">
				<div class="profile-info-name"> Staff status </div>

				<div class="profile-info-value">
					@if( $staff['isenabled'] !== 1 )
						<i class="icon-thumbs-down light-orange bigger-110"></i>
						<span id="isenabled" data-pk="{{$staff['id']}}" class="editable editable-click">Disabled</span>
					@else
						<i class="icon-thumbs-up light-orange bigger-110"></i>
						<span id="isenabled" data-pk="{{$staff['id']}}" class="editable editable-click">Enabled</span>
					@endif
				</div>
			</div>

			<!--<div class="profile-info-row">
				<div class="profile-info-name"> About Me </div>

				<div class="profile-info-value">
					<span id="about" class="editable editable-click">Editable as WYSIWYG</span>
				</div>
			</div>-->
		</div>
	</div> <!-- PERSONAL DETAILS ENDS HERE -->

<!--	<div class="space-20"></div>

-->