	<div class="widget-box transparent">

		<div class="profile-user-info profile-user-info-striped">
			<div class="profile-info-row">
				<div class="profile-info-name"> Full name </div>

				<div class="profile-info-value">
					<span id="name" data-pk="{{$customer['id']}}" class="editable editable-click"> {{{ucwords($customer['name'])}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Birthday </div>

				<div class="profile-info-value">
					<i class="icon-gift light-orange bigger-110"></i>
					<span id="birthday" data-pk="{{$customer['id']}}"  class="editable editable-click">{{{dob_date_format($customer['birthday'])}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Token-ID </div>

				<div class="profile-info-value">
					<i class="icon-filter light-orange bigger-110"></i>
					<span id="usertoken" class=""> {{{$customer['token']}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Phone number </div>

				<div class="profile-info-value">
					<i class="icon-phone light-orange bigger-110"></i>
					<span id="phone" data-pk="{{$customer['id']}}" class="editable editable-click"> {{{$customer['phone']}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Email </div>

				<div class="profile-info-value">
					<i class="icon-phone light-orange bigger-110"></i>
					<span id="email" data-pk="{{$customer['id']}}" class="editable editable-click"> {{{$customer['email']}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Customer type </div>

				<div class="profile-info-value">
					<i class="icon-star light-orange bigger-110"></i>
					<span id="mode" data-pk="{{$customer['id']}}" class="editable editable-click"> {{{ucwords(Mode::getModeNameFromID($customer['mode_id']))}}}</span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Profile created </div>

				<div class="profile-info-value">
					<i class="icon-calendar light-orange bigger-110"></i>
					<span id="signup" class=""> {{{format_date($customer['created_at'])}}} </span>
				</div>
			</div>

			<div class="profile-info-row">
				<div class="profile-info-name"> Created By </div>

				<div class="profile-info-value">
				<i class="icon-user light-orange bigger-110"></i>
					<span id="login" class=""> {{{$customer['createdby']}}} </span>
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