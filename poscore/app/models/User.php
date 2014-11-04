<?php

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');

	//We can't mass assign ID
    protected $guarded = array('id');

    //Default Password
    public $defaultPassword = 'newuser';

    public static $rulex;


    public function roles(){
    	return $this->belongsToMany('Role');
    }

    public function receipts(){
    	return $this->hasMany('receipt', 'user_id');
    }

    public function bankentries(){
    	return $this->hasMany('Bankentry', 'user_id');
    }

    public function expenditures(){
    	return $this->hasMany('Expenditure', 'user_id');
    }

    public function vendors(){
    	return $this->hasMany('Vendor', 'user_id');
    }

    //This would fetch the Users Roles
    public static function getUserRoleDetail($id){
    	return Static::find($id)->roles()->get()->toArray();
    }

    public static function getUserRoleName($id){
    	return static::getOnlyUserRoleID($id, 'name');
    }

    //This would fetch the Users Roles and pluck only the Role_id
    public static function getOnlyUserRoleID($id, $type='id'){
    	$rolesID = array();
    	foreach(Static::getUserRoleDetail($id) as $array){
    		$rolesID[] = $array[$type];
    	}
    	return $rolesID;
    }

    //Would return true if User is role is permitted
    public static function permitted($role_id){
    	$id = Auth::user()->id;
    	$roles = static::getOnlyUserRoleID($id);
    	$roleIDs = Config::get($role_id);
    	
    	if( !is_array($roleIDs) ){
    		 if( in_array($roleIDs, $roles) || in_array(Config::get('role.admin'), $roles) ){
    		 	return true;
    		 }
    	}

    	return false;

    	/*if( is_array($roleIDs) ){

    		foreach( $roleIDs as $role ){
    			if( ! in_array($role, $roles) ){
    				return false;
    			}
    		}

    		return true;
    	}*/
    }

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	public function getAuthIdentifier()
	{
		return $this->getKey();
	}

	/**
	 * Get the password for the user.
	 *
	 * @return string
	 */
	public function getAuthPassword()
	{
		return $this->password;
	}

	/**
	 * Get the e-mail address where password reminders are sent.
	 *
	 * @return string
	 */
	public function getReminderEmail()
	{
		return $this->email;
	}

	/*public function setPasswordAttribute()
	{
	    $this->password = Hash::make($this->password);
	}*/

	public function setNewDefaultPassword()
	{
		return Hash::make($this->defaultPassword);
	}

	public function getRememberToken()
	{
	    return $this->remember_token;
	}

	public function setRememberToken($value)
	{
	    $this->remember_token = $value;
	}

	public function getRememberTokenName()
	{
	    return 'remember_token';
	}

	public function user_activity(){
		return $this->hasMany('usersactivity', 'user_id');
	}	

	public function salelogs(){
		return $this->hasMany('salelog', 'user_id');
	}

	public static function lastID(){
		return static::max('id');
	}

	public static function userID(){
		if( ($r = static::lastID()) !== null ){
			return $r + 1;
		}else{
			return 1;
		}
	}

	public static function boot(){
		parent::boot();

		static::creating(function($query){
			return $query->isValid();
		});

		static::updating(function($query){
			//return $query->isValid();
		});
	}

	public function isValid(){
		Xvalidate::filter(static::$rulex);
		$validation = Xvalidate::these($this->toArray());

		if ( ! $validation->passes() ){
		    	$this->validatorStatus = $validation->messages();
		    	return false;
		    }

		return true;
	}

	/*public static function getStaffRole($role){
		return static::$getRoleName[$role];
	}*/

	public static function getUsernameByID($user_id, $withtoken=true){
		//tt( $user_id );
		$user = static::where('id', '=', $user_id)->select('name', 'usertoken')->first();

		if($user == NULL){
			return 'Anonymous';
		}

		$name = $user->name;
		$usertoken = $user->usertoken;

		if( $withtoken === true ){
			$r = $name . " [{$usertoken}]";
		}else{
			$r = $name;
		}

		return $name;
	}

	public static function selectUserDetailByID($id, $details){
		$user = static::where('id', '=', $id)->select($details)->first();
		return $user[$details];
	}

	public static function assignRole($id, $type=''){
		$type = ($type === '') ? Config::get('role.sales') : $type;

		$staff = Static::find($id);
		$staff->roles()->attach($type);
	}

	public static function generateUsername($fullname){
		$nameArray = explode(' ', $fullname);
		$username = $nameArray[0] . Static::userID();
		return strtolower($username);
	}

	public static function generateUsertoken(){
		return Makehash::random('number',6);
	}

}