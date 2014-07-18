<?php

class UploadController extends \BaseController {

	public function index()
	{
		$file = 'Test';
	
	
		return Response::json (array (
				'error' => false,
				'files' => $file
		), 200);
	
		/*
			$file = new UploadedFile ();
		$file->path = 'public/';
		$file->filename = time ();
	
		$file->save ();
	
		return Response::json (array (
				'error' => false,
				'files' => $file->toArray ()
		), 200);
	
		*/
	
	}
	
	
	/**
	 * Upload a file
	 *
	 * @return Response
	 */
	public function store ()
	{
		$path_upload_local = '/tmp_images/';
		$path_upload_remote = '/';
		
		
		/*
		$method = Request::method();
		
		if (Request::isMethod('post'))
		{
			return 'Is a POST'; 
		}*/
		
		
		//$data = Input::json();
		
		//$file = $data->data;
		
		//$data = Input::get('data');
		//$data = Input::get('data');
		
		//$file = json_decode($data);
		
		//$file = Input::json();
		
		//$file = Input::get('data');
		//$file = Input::put('data');
		
		$request = Input::json()->all();
		
		//var_dump($file->data);
		//var_dump($request['data']);

		$data = isset($request['data']) ? $request['data'] : '';
		
		
		
		list($type, $data)   = explode(';', $data);
		list($format, $data) = explode(',', $data);
		$data = base64_decode($data);
		
		//var_dump($type,$format);
		
		$type = str_ireplace('data:','',strtolower($type));
		
		if ($format != 'base64') {
			return Response::json (array (
				'error' => true,
				'message' => 'Image sent in wrong encoding format!'
			), 200);
		}
		
		switch($type) {
		case 'image/png':
		case 'image/jpeg':
			// Good mime formats
		break;
		default:
			return Response::json (array (
				'error' => true,
				'message' => 'Image sent in wrong MIME format!'
			), 200);
		}
		
		$filename = md5(uniqid()) .'.png';
		
		file_put_contents($path_upload_local . '/'. $filename, $data);
		
		echo "type: $type";
		
		
		/*
		if (Request::format() == 'json')
		{
			
			return Response::json (array (
				'error' => false,
				'files' => 'Image sent'
			), 200);
			
		} else {
			return Response::json (array (
				'error' => true,
				'message' => 'Image not sent in the proper format!'
			), 200);
		}
		*/
		
		/*
		$file = new UploadedFile ();
		$file->path = 'public/';
		$file->filename = time ();

		$file->save ();

		return Response::json (array (
				'error' => false,
				'files' => $file->toArray ()
		), 200);
		
		*/
		
	}


}
