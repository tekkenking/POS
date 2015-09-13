<?php namespace App\Libraries;

//Depends on Make::random;

use Session, Makehash;

class cartSession {

	private $_name = 'cartsession';
	private $_current = 'current';

	private function _cartName()
	{
		return $this->_rootName() . '.' . $this->_currentName();
	}

	private function _suspendName()
	{
		return $this->_rootName() .'.suspend.' . $this->_generateName();
	}

	private function _rootName()
	{
		return $this->_name;
	}

	private function _currentName()
	{
		return $this->_current;
	}

	public function saveCurrent($key, $value)
	{
		Session::put($this->_cartName() . '.' . $key, $value);
	}

	public function getCurrent($name, $default ='')
	{
		return Session::get($this->_cartName() . '.'. $name, $default);
	}

	public function getCurrentContent()
	{
		return Session::get($this->_cartName());
	}

	public function removeCurrent()
	{
		return Session::forget( $this->_cartName() );
	}

	public function getRoot()
	{
		return Session::get( $this->_rootName() );
	}

	public function suspend()
	{
		Session::put( $this->_suspendName(), $this->getCurrentContent() );
		$this->removeCurrent();
	}

	public function getSuspend()
	{
		$datas = Session::get( $this->_rootName() .'.suspend', '' );
		return $datas;
	}

	public function resumeCart($key)
	{
		$datas = $this->getSuspendByKey($key);
		Session::put($this->_cartName(), $datas);
		$this->removeSuspended($key);
		return $datas;
	}

	public function removeSuspended($key)
	{
		Session::forget( $this->_suspendedByKeyName($key) );
	}

	public function getSuspendByKey($key)
	{
		return Session::get( $this->_suspendedByKeyName($key), '' );
	}

	private function _suspendedByKeyName($key)
	{
		return $this->_rootName() .'.suspend.' . $key;
	}

	private function _generateName()
	{
		return Makehash::random(20, 'letter', 'number');
	}

}