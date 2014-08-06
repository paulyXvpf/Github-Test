<?php 

$page_title = 'Register';
include('./includes/header.html');

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{


require ('../connect_db.php');
$errors = array();

if ( empty( $_POST['first_name']))
{ $errors[] = 'Enter your first name.';}
else
{ $fn = mysqli_real_escape_string( $dbc, trim( $_POST['first_name']));}


}
?>


