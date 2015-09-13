<?php

class MessageController extends BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$this->layout->title = 'Welcome to your inbox';
		$this->layout->content = View::make('messageInbox');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$inputs = Input::all();

		$type = Message::getIntegerTypeBy($inputs['name']);
		$to = $inputs['pk'];
		$from = $inputs['sentby_id'];
		$body = $inputs['value'];
		$subject = truncate_string($body, 24, '...');

		$msg = new Message;
		$msg->from = $from;
		$msg->to = $to;
		$msg->type = $type;
		$msg->subject = $subject;
		$msg->body = $body;
		$msg->save();

		$data['status'] = 'success';
		$data['message'] = 'Sent successfully';

		return Response::json($data);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	public function quickView($id){
		$msg = Message::where('id', '=', $id);
		$msgx = $msg->where('to', '=',Auth::user()->id)
					->get()
					->toArray();

		$msgx = empty($msgx) ? tt('Access denied to this message') : $msgx[0];

		//tt($msg->get()->toArray());

		$msg->update(array('checked'=>1));
		//tt($msg);
		$data['msg'] = $msgx;
		return View::make('quickview_messagealert', $data);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}