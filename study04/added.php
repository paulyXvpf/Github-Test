<?php # DISPLAY SHOPPING CART ADDITIONS PAGE.

# Access session.
session_start() ;

# Redirect if not logged in.
if ( !isset( $_SESSION[ 'user_id' ] ) ) { require ( 'login_tools.php' ) ; load() ; }

# Set page title and display header section.
$page_title = 'Cart Addition' ;
include ( 'includes/header.html' ) ;

# Get passed product id and assign it to a variable.
if ( isset( $_GET['id'] ) ) $id = $_GET['id'] ; 

# Open database connection.
require ('../connect_db.php') ;

# Retrieve selective item data from 'shop' database table. 
$q = "SELECT * FROM shop WHERE item_id = $id" ;
$r = mysqli_query( $dbc, $q ) ;
if ( mysqli_num_rows( $r ) == 1 )
{
  $row = mysqli_fetch_array( $r, MYSQLI_ASSOC );

  # Check if cart already contains one of this product id.
  if ( isset( $_SESSION['cart'][$id] ) )
  { 
    # Add one more of this product.
    $_SESSION['cart'][$id]['quantity']++; 
    echo '<center><p>Another '.$row["item_name"].' has been added to your cart</p></center>';
  } 
  else
  {
    # Or add one of this product to the cart.
    $_SESSION['cart'][$id]= array ( 'quantity' => 1, 'price' => $row['item_price'] ) ;
    echo '<center><p>A '.$row["item_name"].' has been added to your cart</p></center>' ;
  }
}

# Close database connection.
mysqli_close($dbc);

# Create navigation links.
echo '<center><p><a href="shop.php">Shop</a> | <a href="cart.php">View Cart</a> | <a href="home.php">Home</a> | <a href="goodbye.php">Logout</a></p></center.php>' ;

# Display footer section.
include ( 'includes/footer.html' ) ;

?>