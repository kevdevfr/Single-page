<?php require_once( './php/class.config.php' ); $config = new Config();

/* Dynamic definition of the server's root url, may shit the bed on some servers */
if( basename( dirname( __FILE__ ) ) != basename( $_SERVER['DOCUMENT_ROOT'] ) )
	define( 'BASE_URL', "/" . basename( dirname( __FILE__ ) ) . "/" );
else
	define( 'BASE_URL', "/" );

if( !is_dir( 'admin' ) ) /* if installed, */
{

?><!DOCTYPE html>
<html class="no-js"<?php if( $config -> get( 'Language Code' ) ) echo ' lang="' . $config -> get( 'Language Code' ) . '"'; ?>>
	<head>
	    <meta charset="utf-8">
	    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	    <title><?php
				if( $config -> get( 'Meta-Title' ) && $config -> get( 'Meta-Title' ) != "" )
						echo $config -> get( 'Meta-Title' );
				else {
					if( $config -> get( 'Prefix' ) ) { echo $config -> get( 'Prefix' ) . ' '; }
					echo $config -> get( 'Name' );
					if( $config -> get( 'Suffix' ) ) { echo $config -> get( 'Suffix' ) .' '; }
				}

			?></title>
	    <meta name="description" content="<?php if( $config -> get( 'Meta-Description' ) ) echo $config -> get( 'Meta-Description' ); ?>">
	    <meta name="viewport" content="width=device-width">

	    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/normalize.css">
	    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/fontello.css">
	    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">
	<?php if( $config -> get( 'Favicon' ) ) : ?>
	<link rel="shortcut icon" href="ups/<?php echo $config -> get( 'Favicon'); ?>">
	<?php endif;

			/* Custom styles */
				$style = '';
				if( $config -> get( 'Custom CSS' ) )
					$style .= $config -> get( 'Custom CSS' );
				if( !empty( $style ) ) echo "\t" . '<style>' . "\n\t\t\t" . $style . "\n\t\t"  . '</style>' . "\n\t";
			 ?></head>
	<body<?php
		/* Quick custom styles */
		$style = '';
		if( $config -> get( 'Font Color' ) )
			$style .= 'background-color: ' . $config -> get( 'Background Color' ) . ';';
		if( $config -> get( 'Background Color' ) )
			$style .= 'color: ' . $config -> get( 'Font Color') . ';';
		if( !empty( $style ) )
			echo ' style="' . $style . '"';
	 ?>>
	<?php
 		/* Background element */
	 if( $config -> get( 'Background' ) )
			echo "\t" . '<div class="background" style="background-image: url(ups/' . $config -> get( 'Background') . ')';
		if( $config -> get( 'Background' ) && $config -> get( 'Background Alpha' ) )
			echo ';opacity:' . $config -> get( 'Background Alpha' );
		if( $config -> get( 'Background' ) )
			echo '"></div>' . "\n\t";
	 ?>
	 <div class='wrap'>
			<?php echo $config->comingSoon(); /* Content building */ ?>
		</div>
	</body>
</html>
<?php

} else { /* if not installed, */
	header( 'Location: ./admin/' );
	exit;
}
