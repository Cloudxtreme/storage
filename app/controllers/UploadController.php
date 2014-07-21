<?php

class UploadController extends \BaseController {

	public function index()
	{
		$file = 'Testing...';
	
		return Response::json (array (
			'test' => $file
		), 200);
	
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

		// Get file
		$request = Input::json()->all();
		
		$data = isset($request['data']) ? $request['data'] : '';
		
		if (!$data) {
			return Response::json (array (
				'error' => true,
				'message' => 'Image not sent properly!'
			), 200);
		}
		
		list($type, $data)   = explode(';', $data); // {'image/png', 'image/jpeg'}
		list($format, $data) = explode(',', $data); // base64
		$data = base64_decode($data);
		
		$type = str_ireplace('data:','',strtolower($type)); 
		
		if ($format != 'base64') {
			return Response::json (array (
				'error' => true,
				'message' => 'Image sent in wrong encoding format!'
			), 200);
		}
		
		
		//$file_generated = CDNURL::generateFilenameByType($type);
		$file_generated = CDNURL::generateFilename ($type);
		
		// Check for a valid file type
		/*
		if ($file_generated['extension'] == '') {
			return Response::json (array (
				'error' => true,
				'message' => 'Image sent in wrong MIME format!'
			), 200);
		}
		*/
		
		//echo "<pre>"; var_dump($file_generated); die('...');
		
		$filename_local = $file_generated['local'];
		
		// Create MD5 folders recursively
		$dir_local = dirname($filename_local);
		
		if (!file_exists($dir_local)) {
			mkdir($dir_local, 0777, true);
		}
		
		// Write file to disk
		try
		{
			// Save temporary file, unknown file type
			file_put_contents($filename_local, $data);
			
			// Get EXIF properties
			$ext = CDNURL::getImageType($filename_local);
			
			// Check if we have a valid image and rename to a valid file type {'image/png', 'image/jpeg'}
			if ($ext) {
				$new_filename_local = substr($filename_local, 0, -3) . $ext;
				
				// Update remote file the proper extension
				$file_generated['remote'] = substr($file_generated['remote'], 0, -3) . $ext;
				
				//die("filename_local = $filename_local # new_filename_local = $new_filename_local");
				
				// Rename file with the proper extension
				@rename($filename_local, $new_filename_local);
				
				// Update filenames with the proper extension
				$filename_local = $new_filename_local;
			} else {
				// Delete useless temporary files
				@unlink($filename_local);
			}
			
			
			
		} catch (\Exception $e) {
			// Suppress errors
		}
		
		// Check if the file was written properly
		if (filesize($filename_local) !== strlen($data)) {
			return Response::json (array (
				'error' => true,
				'message' => 'File not saved properly!'
			), 200);
		}
		
		if (!is_readable($filename_local)) {
			return Response::json (array (
				'error' => true,
				'message' => 'File saved but it\'s not readable!'
			), 200);
		}
		
		// --------------------------------------------------------------------
		// Resize image to the default stream size
		// --------------------------------------------------------------------
		
		// --------------------------------------------------------------------
		// Return image generated
		// --------------------------------------------------------------------
		return $file_generated['remote'];
		
	}


}
