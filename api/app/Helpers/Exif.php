<?php

namespace App\Helpers;

use Exception;

class Exif
{
	public static function get(string $path) : array
	{
		try {
			return exif_read_data($path);
		} catch (Exception $e) {
			return [];
		}
	}

	public static function exists(int $fileType) : bool
	{
		return $fileType === IMAGETYPE_JPEG;
	}
}
