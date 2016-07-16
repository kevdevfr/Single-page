<?php session_start(); require_once( '../php/functions.php' );  require_once( '../php/class.login.php' ); 	require_once( '../php/class.config.php' ); $config = new Config();

/* Dynamic definition of the server's root url, may shit the bed on some servers */
	if( basename( dirname( dirname( __FILE__ ) ) ) != basename( $_SERVER['DOCUMENT_ROOT'] ) )
		define( 'BASE_URL', "/".basename( dirname( dirname( __FILE__ ) ) ) . "/" );
	else
		define( 'BASE_URL', "/" );


?><!DOCTYPE html>
<html class="no-js" lang="en">
	<head>
	  <meta charset="utf-8">
	  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	  <title>Page Administration</title>
	  <meta name="viewport" content="width=device-width">
	  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/normalize.css">
	  <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/admin.css">
	</head>
	<body>
<?php

/* User identification */
$class = '';
if( isset( $_POST['user'] ) && isset( $_POST['password'] ) )
{
	if( isset( $_POST['confirm'] ) )
			if( $_POST['confirm'] != $_POST['password'] )
				$class = "error";
	if( $class != "error" )
	{
		$login = new Login( $_POST );
		if( $login -> confirm( $_POST['password'] ) )
			$_SESSION['connection'] = true;
		else $class = "error";
	}
}
if( !isset( $_SESSION['connection'] ) ) :
?>
		<form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>" name="login" id="loginform">
			<span class="field">
				<label for="user">User</label>
				<input class="<?php echo $class; ?>" type="text" name="user" id="user" <?php if( !empty( $_POST['user'] ) ) echo "value=\"" . $_POST['user'] . "\" "; ?>/>
			</span>
			<span class="field">
				<label for="password">Password</label>
				<input class="<?php echo $class; ?>" type="password" name="password" id="password" <?php if( !empty( $_POST['password'] ) ) echo "value=\"" . $_POST['password'] . "\" "; ?>/>
			</span>
		<?php
		if( !file_exists( dirname( dirname( __FILE__ ) ) . '/xml/ids.xml' ) ) :
		?>
			<span class="field">
				<label for="confirm">Confirm</label>
				<input class="<?php echo $class; ?>" type="password" name="confirm" id="confirm" <?php if( !empty( $_POST['confirm'] ) ) echo "value=\"" . $_POST['confirm'] . "\" "; ?>/>
			</span>
			<input type="submit" name="connection" id="connection" value="Create user" />
		<?php
		else :
		?>
	<input type="submit" name="connection" id="connection" value="Log in" />
		<?php
		endif;
		?>
</form>
	</body>
</html>
<?php
exit();
endif;
?>
<div class="wrap">
<header>
	<h2><?php echo $_SERVER['SERVER_NAME'];?></h2><span class="logout"><a href="./logout.php">Log Out</a> &nbsp; <a href="../">View</a></span>
</header>
<hr />
<?php
/* Message if admin folder is still named admin/ */
if( basename( str_replace( "index.php", "", $_SERVER['REQUEST_URI'] ) ) == 'admin' ) echo '<p style="text-align:center;">Change the name of the admin/ folder after saving.</p>';

	/* POST data processing */
	if( !empty( $_POST['edit'] ) )
	{
		$config -> update( $_POST );
		$config -> save();
		header( 'Location: ' . $_SERVER['REQUEST_URI'] ); /* PRG */
	}

	/* Parameters form */
	echo $config -> adminHtml();
?>
		<hr />
		<div id="margin"></div>
	</div>
	<footer>
		<p>Get a copy on <a href="https://github.com/kevdevfr/single-page">GitHub</a><span style="float: right;">&copy; 2016 <a href="http://kevdev.fr">Kevin Mouchené</a></span></p>
	</footer>

	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
	<script>window.jQuery || document.write('<script type="text/javascript" src="<?php echo BASE_URL; ?>/jsc/vendor/jquery-2.1.0.min.js"><\/script>')</script>
	<script type="text/javascript" src="<?php echo BASE_URL; ?>/jsc/admin.js"></script>

	</body>
</html>
