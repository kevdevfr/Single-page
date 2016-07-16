<?php

function formatBytes( $size , $precision = 2 )
{
    $base = log( $size, 1024 );
    $suffixes = array( '', 'K', 'M', 'G', 'T' );

    return round( pow( 1024, $base - floor( $base ) ), $precision ) . '' . $suffixes[floor( $base )];
}

function file_upload_max_size()
{
  static $max_size = -1;

  if ( $max_size < 0 ) {
    // Start with post_max_size.
    $max_size = parse_size( ini_get( 'post_max_size' ) );

    // If upload_max_size is less, then reduce. Except if upload_max_size is
    // zero, which indicates no limit.
    $upload_max = parse_size( ini_get( 'upload_max_filesize' ) );
    if ( $upload_max > 0 && $upload_max < $max_size ) {
      $max_size = $upload_max;
    }
  }
  return $max_size;
}

function parse_size( $size )
{
  $unit = preg_replace( '/[^bkmgtpezy]/i', '', $size ); // Remove the non-unit characters from the size.
  $size = preg_replace( '/[^0-9\.]/', '', $size ); // Remove the non-numeric characters from the size.
  if ( $unit ) {
    // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
    return round( $size * pow( 1024, stripos( 'bkmgtpezy', $unit[0] ) ) );
  }
  else {
    return round( $size );
  }
}

function removeAccents( $str, $charset='utf-8' )
{
    $str = htmlentities( $str, ENT_NOQUOTES, $charset );

    $str = preg_replace( '#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
    $str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
    $str = preg_replace( '#&[^;]+;#', '', $str );

    return $str;
}


/**
 * Mostly used for field names derived form their label
 *
 * @param array $str label value
 *
 */
function classify( $str = "" )
{
  $str = removeAccents( $str );
  $str = str_replace( '\'', '', $str );
  $str = preg_replace( '/\s+/', '', $str );
  $str = strtolower( $str );

  return $str;
}

/**
 * String to HTML formated id
 *
 * @param array $str
 *
 */
function idfy( $str )
{
		$str = htmlentities( $str, ENT_NOQUOTES, 'utf-8' );
		$str = preg_replace( '#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $str );
		$str = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $str );
		$str = preg_replace( '#&[^;]+;#', '', $str );
		$str = str_replace( '\'','-',$str );
		$str = str_replace( '/','-',$str );
		$str = str_replace( ',','',$str );
		$str = preg_replace( '/\s+/', '-', $str );
		$str = str_replace( ' ','_',$str );
		$str = strtolower( $str );
		return $str;
}

function resizeImage( $newHeight, $targetFile, $originalFile )
{

    $info = getimagesize( $originalFile );

    $mime = $info['mime'];

    switch ( $mime )
    {
      case 'image/jpeg':
        $image_create_func = 'imagecreatefromjpeg';
        $image_save_func = 'imagejpeg';
        $new_image_ext = 'jpg';
      break;

      case 'image/png':
        $image_create_func = 'imagecreatefrompng';
        $image_save_func = 'imagepng';
        $new_image_ext = 'png';
      break;

      case 'image/gif':
        $image_create_func = 'imagecreatefromgif';
        $image_save_func = 'imagegif';
        $new_image_ext = 'gif';
      break;

      case 'image/svg':
        exit();
      break;

      default:
        throw Exception( 'Unknown image type.' );

    }

    $img = $image_create_func( $originalFile );
    if( $mime == 'image/png' )
    {
      imageAlphaBlending( $img, true );
      imageSaveAlpha( $img, true );
    }
    list( $width, $height ) = getimagesize( $originalFile );

    if( $height <= $newHeight ) return false;

    $newWidth = ( $width / $height ) * $newHeight;

    $tmp = imagecreatetruecolor( $newWidth, $newHeight );
    imagecopyresampled( $tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height );

    if ( file_exists( $targetFile ) ) unlink($targetFile);

    $image_save_func( $tmp, "$targetFile" );

    return true;

}
