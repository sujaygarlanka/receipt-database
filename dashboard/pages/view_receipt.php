  <!DOCTYPE html>
  <html lang="en">
      <title>Receipt</title>
      
</html>

<?php
include "../php/functions.php";
// session started in the functions.php file which is included


if (isset($_POST['receipt_id'])){ // gets the id for the receipt clicked from tables.php 
                                  // sets the id in a session variable so when this page refreshes the page can show the email body with this id
    $_SESSION['id'] = $_POST['receipt_id'];


}
if (isset($_SESSION['id'])){
    $id = $_SESSION['id'];
    print_r(readEmail($id));
}



?>