<?php

class CDNURL
{
	
	static public function folderByFilename ($filename) {
		
		// --------------------------------------------------------------------
		// Improved format (not used)
		// --------------------------------------------------------------------
		// {id}         => ID of the image
		// {hash}       => HASH SHA256 random & unique
		// original     => "original" image
		// {resolution} => resolution of image
		// {number}     => order number / sequence of the image
		// {ext}        => extension of the file format
		// --------------------------------------------------------------------
		//            {id}_{hash}_original_{number}.{ext}

		// filename = 101_xxxxxxxxxxxxxxxxxxxx_original_1.png
		// filename = 102_xxxxxxxxxxxxxxxxxxxx_original_1.jpg
		// filename = 103_xxxxxxxxxxxxxxxxxxxx_original_1.gif

		//            {id}_{hash}_{resolution}_{number}.{ext}

		// filename = 101_xxxxxxxxxxxxxxxxxxxx_640x640_1.jpg
		// filename = 102_xxxxxxxxxxxxxxxxxxxx_640x640_2.jpg
		// filename = 103_xxxxxxxxxxxxxxxxxxxx_640x640_3.jpg

		// filename = 101_xxxxxxxxxxxxxxxxxxxx_250x250_1.jpg
		// filename = 102_xxxxxxxxxxxxxxxxxxxx_250x250_2.jpg
		// filename = 103_xxxxxxxxxxxxxxxxxxxx_250x250_3.jpg

		// --------------------------------------------------------------------
		// Simple format, parse by HASH
		// --------------------------------------------------------------------
		// filename = xxxxxxxxxxxxxxxxxxxx_original.png
		// --------------------------------------------------------------------
		
		// $hash = explode("_", $filename, 1);
		
		return '';
	}
	
	
	static public function generateFilenameByType ($type) {
		
		$path_upload_local = '/tmp_images';
		$path_upload_local = 'c:/tmp_images';
		
		$filename_ext = '';
		
		$hash = hash('sha256', rand(1,999999999) . uniqid() . rand(1,999999999) . time() . rand(1,999999999));
		//$hash = md5(rand(1,999999999) . uniqid() . rand(1,999999999) . time() . rand(1,999999999));
		
		
		// Get file type by MIME type 
		switch($type) {
			case 'image/png':
				$filename_ext = 'png';
				break;
			case 'image/jpeg':
				$filename_ext = 'jpg';
				break;
			default:
				$filename_ext = 'tmp';
		}

		$filename = $hash .'_original.'. $filename_ext;
		
		$md5_folders = CDNURL::md5FoldersByHash($hash);
	
		$filename_fullpath = $path_upload_local .'/'. $md5_folders .'/'. $filename;
	
		return array (
			'filename'  => $filename,
			'extension' => $filename_ext,
			'folder'    => $md5_folders,
			'local'     => $filename_fullpath,
			'remote'    => "http://images.cloudwalkers.be/$md5_folders/$filename"
		);
	}	
	
	static public function generateFilename () {
	
		// Get configuration
		$api_settings = Config::get('api.settings');
		
		//$api_settings['storage_url']; // => http://cloudwalkers-storage.local/
		//$api_settings['cdn_remote'];  // => http://images.cloudwalkers.be/
		//$api_settings['cdn_local'];   // => /tmp_images/
		
		$path_upload_local =  $api_settings['cdn_local'];
		
		$filename_ext = 'tmp';
	
		// Generate an unique filename
		$hash = hash('sha256', rand(1,999999999) . uniqid() . rand(1,999999999) . time() . rand(1,999999999));
		//$hash = md5(rand(1,999999999) . uniqid() . rand(1,999999999) . time() . rand(1,999999999));
	
		$filename = $hash .'_original.'. $filename_ext;
	
		$md5_folders = CDNURL::md5FoldersByHash($hash);
	
		$filename_fullpath = $path_upload_local .'/'. $md5_folders .'/'. $filename;
	
		return array (
				'filename'  => $filename,
				'extension' => $filename_ext,
				'folder'    => $md5_folders,
				'local'     => $filename_fullpath,
				'remote'    => $api_settings['cdn_remote'] . "$md5_folders/$filename" // http://images.cloudwalkers.be/FF/FF/0123456789..._original.tmp
		);
	}
	
	static public function getImageType ($filename) {
		// Get file type
		$type = @exif_imagetype ($filename);
		
		if ($type)
		{
			$ext = false;
			
			switch ($type)
			{
				case IMAGETYPE_GIF:
				$ext = 'gif';
				break;
				case IMAGETYPE_JPEG:
				$ext = 'jpg';
				break;
				case IMAGETYPE_PNG:
				$ext = 'png';
				break;
			}
		}
		
		if ($ext) {
			return $ext;
		} else {
			return null;
		}
	}
	
	public static function md5FoldersByHash($hash) {
		$folder1 = substr($hash, 0, 2);
		$folder2 = substr($hash, 2, 2);
		
		$ret = "$folder1/$folder2";
		
		// Check if has 5 chars and is hexadecimal
		return strlen($ret) === 5 && ctype_xdigit($folder1) && ctype_xdigit($folder2) ? $ret : '';
	}
	
	
}



