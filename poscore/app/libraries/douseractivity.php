<?php

class DoUserActivity{

	public $user_id;
	public $username;


	public static function saveActivity(){
		$args = func_get_args();
		$type = array_shift($args);

		$self = new self;
		$self->$type($args);
	}


	private function loggedin(){
		$data['username'] = Auth::user()->username;
		//$data['type'] = 'loggedin';
		//$viewx = View::make('activity_layouts.user', $data);

		$this->save('loggedin', $data);
	}

	private function loggedout(){
		$data['username'] = Auth::user()->username;
		//$data['type'] = 'loggedout';
		//$viewx = View::make('activity_layouts.user', $data);

		$this->save('loggedout', $data);
	}

	private function sale($params){
		$data['username'] = Auth::user()->username;
		$data['totalprice'] = $params[0]; //TOtal price
		$data['customername'] = $params[1]; // Customer Name
		$data['receipt_number'] = $params[2]; // Receipt Number
		//$data['type'] = 'sale';
		//$viewx = View::make('activity_layouts.user', $data);

		$this->save('sale', $data);
	}

	private function stock($data){
		$data = $data[0];
		$data['username'] = Auth::user()->username;
		//tt($data['stocktype']);
		//if( $data['stocktype'] === 'create'){
			$this->save('stock', $data);
		//}
	}

	private function updateloggedTime($data){
		$user = User::find(Auth::user()->id);
		$user->isloggedin = $data[0];
		$user->loggedtime = sqldate();
		$user->save();
	}

	private function save($type, $data){
		$dbcontent['user_id'] = Auth::user()->id;
		$dbcontent['activity_type'] = $type;
		$dbcontent['message_body'] = json_encode($data);

		$save = new Usersactivity;
		$save->fill($dbcontent);
		$save->save();
	}
}