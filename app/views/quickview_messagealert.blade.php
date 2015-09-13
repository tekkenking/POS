<div class="itemdiv dialogdiv">
	<div class="user">
		 <?php 
            $genderx =  User::where('id', $msg['from'])->pluck('gender');
            $imagex = "uploads/img/". $genderx ."5.png"; 
        ?>
        {{HTML::image($imagex, 'Staff Avatar', array('width'=>'48', 'class'=>'img-circle'))}}
	</div>

	<div class="body" id="messageid_{{$msg['id']}}">
		<div class="time">
			<i class="icon-time"></i>
			<span class="green">{{ago_date_format($msg['created_at'])}}</span>
		</div>

		<div class="name">
			<a href="#">{{ucwords(User::getUsernameByID($msg['from'], false))}}</a>
			<span class="label label-success arrowed arrowed-in-right">admin</span>
		</div>
		<div class="messagebody">{{$msg['body']}}</div>

		<div class="tools">
			<a class="btn btn-minier btn-info" href="#">
				<i class="icon-only icon-share-alt"></i>
			</a>
		</div>
	</div>
</div>