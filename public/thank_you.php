<?php require_once("../resources/config.php"); ?>
<?php require_once("cart.php"); ?>
<?php include(TEMPLATE_FRONT . DS . "header.php") ?>

<?php 

// catch order informations from paypal in order to store them into the database and then destroy the session with cart content
if (isset($_GET['tx'])) { 
    $amount = $_GET['amt'];
    $currency = $_GET['cc'];
    $transaction = $_GET['tx'];
    $status = $_GET['st'];

    $query = query("INSERT INTO orders (order_amount, order_transaction, order_status, order_currency) VALUES('{$amount}','{$currency}', '{$transaction}', '{$status}')");
    confirm($query);
    session_destroy();


    // check if the status is ok and payment is done
}  else {
    redirect("index.php");
}

?>

    <!-- Page Content -->
    <div class="container">
        <h1 class="text-center">Thank you</h1>
    </div>
    <!-- /.container -->

    <?php include(TEMPLATE_FRONT . DS . "footer.php") ?>
