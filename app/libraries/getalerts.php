<?php

class GetAlerts{

	public static function unreadInboxMessageAlert(){
		$new = Message::where('to','=',Auth::user()->id)
						->where('checked', '=', 0)
						->where('type', '=', 1);

		$data['count'] 		= $new->count();
		$data['messages']	= $new->get()->toArray();

		//tt($data);

		return $data;
	}


}