<?php
/* Handles image upload */
require dirname( __FILE__ ) . '/functions.php';

var_dump($_FILES);


$path = "../ups/tmp/";
$valid_formats = array( "jpg", "jpeg", "png", "svg", "JPG", "JPEG", "gif", "GIF", "ico");

if( isset( $_POST ) and $_SERVER['REQUEST_METHOD'] == "POST" )
{
	if( isset( $_POST['action'] ) && $_POST['action'] == 'del' )
		unlink( "../ups/" . $_POST['name'] );
	else {
		$name = NULL;
		if( isset( $_FILES ) )
		{
			foreach( $_FILES as $file )
			{
				if( $file['size'] < file_upload_max_size() )
				{
					$name = $file['name'];
					if( strlen( $name ) )
					{
						list( $txt, $ext ) = explode( ".", $name );
						if( in_array( $ext, $valid_formats ) )
						{
							$actual_image_name = idfy( $name );
							$tmp = $file['tmp_name'];
							if( move_uploaded_file( $tmp, $path.$actual_image_name ) )
							{
								echo "$" . $path . idfy( $actual_image_name );
								if( $ext!='svg' && $ext!='ico' )
									resizeImage( 1080, $path.$actual_image_name, $path.$actual_image_name );
							}
						}
					}
				}
				exit;
			}
		}
	}
}
?>
