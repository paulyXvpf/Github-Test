<?php # DISPLAY COMPLETE LOGIN PAGE.

# Set page title and display header section.
$page_title = 'Login' ;
include ( 'includes/header.html' ) ;

# Display any error messages if present.
if ( isset( $errors ) && !empty( $errors ) )
{
 echo '<p id="err_msg">Oops! There was a problem:<br>' ;
 foreach ( $errors as $msg ) { echo " - $msg<br>" ; }
 echo 'Please try again or <a href="register.php">Register</a></p>' ;
}
?>

<!-- Display body section. -->
<center><h1>Login</h1></center>
<form action="login_action.php" method="post">
<center><p>Email Address: <input type="text" name="email"> </p></center>
<center><p>Password: <input type="password" name="pass"></p></center>
<center><p><input type="submit" value="Login" ></p></center>
</form>

<?php 

# Display footer section.
include ( 'includes/footer.html' ) ; 

?>
