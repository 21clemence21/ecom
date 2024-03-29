<?php

// // test de la connection db
// if($connection){
//     echo "is connected";
// }

// // test to see if functions.php is linked
// echo "from functions"



// HELPERS FUNCTIONS

function set_message($msg)
{
    if (!empty($msg)) {
        $_SESSION['message'] = $msg;
    } else {
        $msg = "";
    }
}

function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
}

function redirect($location)
{
    header("Location: $location");
}

function query($sql)
{
    global $connection; // uses $connection from config
    return mysqli_query($connection, $sql);
}

function confirm($result)
{
    global $connection;
    if (!$result) {
        die("QUERY FAILED " . mysqli_error($connection));
    }
}

//Protège les caractères spéciaux d'une chaîne pour l'utiliser dans une requête SQL, en prenant en compte le jeu de caractères courant de la connexion
function escape_string($string)
{
    global $connection;
    return mysqli_real_escape_string($connection, $string);
}

function fetch_array($result)
{
    return mysqli_fetch_array($result);
}

/****************************FRONT END FUNCTIONS**************************/


// get products


function get_products()
{
    $query = query(" SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {
        //echo $row['product_price'];
        $product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
<div class="thumbnail">
    <a href="item.php?id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
    <div class="caption">
        <h4 class="pull-right">{$row['product_price']}&#8364</h4>
        <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
        </h4>
        <p>{$row['product_description']}</p>
        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Ajouter au panier</a>
    </div>
</div>
</div>
DELIMETER;

        echo $product;
    }
}

function get_categories()
{
    $query =query("SELECT * FROM categories");
    confirm($query);

    while ($row = fetch_array($query)) {
        $category_links = <<<DELIMETER

<a href='category.php?id={$row[cat_id]}' class='list-group-item'>{$row['cat_title']}</a>

DELIMETER;
    
        echo $category_links;
    }
}


function get_products_in_cat_page()
{
    $query = query(" SELECT * FROM products WHERE product_category_id = " . escape_string($_GET['id']) . "");
    confirm($query);

    while ($row = fetch_array($query)) {
        //echo $row['product_price'];
        $product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
<div class="thumbnail">
    <a href="item.php?id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
    <div class="caption">
        <h4 class="pull-right">{$row['product_price']}&#8364</h4>
        <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
        </h4>
        <p>{$row['product_description']}</p>
        <a class="btn btn-secondary" target="_blank" href="item.php?id={$row['product_id']}">More info</a>
        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Ajouter au panier</a>

    </div>
</div>
</div>
DELIMETER;

        echo $product;
    }
}


function get_products_in_shop_page()
{
    $query = query(" SELECT * FROM products");
    confirm($query);

    while ($row = fetch_array($query)) {
        //echo $row['product_price'];
        $product = <<<DELIMETER

<div class="col-sm-4 col-lg-4 col-md-4">
<div class="thumbnail">
    <a href="item.php?id={$row['product_id']}"><img src="{$row['product_image']}" alt=""></a>
    <div class="caption">
        <h4 class="pull-right">{$row['product_price']}&#8364</h4>
        <h4><a href="item.php?id={$row['product_id']}">{$row['product_title']}</a>
        </h4>
        <p>{$row['product_description']}</p>
        <a class="btn btn-primary" target="_blank" href="item.php?id={$row['product_id']}">More info</a>
        <a class="btn btn-primary" target="_blank" href="cart.php?add={$row['product_id']}">Ajouter au panier</a>

    </div>
</div>
</div>
DELIMETER;

        echo $product;
    }
}


function login_user()
{
    if (isset($_POST['submit'])) {
        $username = escape_string($_POST['username']);
        $password = escape_string($_POST['password']);
        $query = query(" SELECT * FROM users WHERE username = '{$username}' AND password = '{$password}' ");
        confirm($query);

        if (mysqli_num_rows($query) == 0) {
            set_message("Your password or username is wrong");
            redirect("login.php");
        } else {
            $_SESSION['username'] = $username;
            set_message("Welcome to admin {$username}");
            redirect("admin");
        }
    }
}

function send_message()
{
    if (isset($_POST['submit'])) {
        $to           =    "depernetclemence@gmail.com";
        $from_name    =    $_POST['name'];
        $subject      =    $_POST['subject'];
        $email        =    $_POST['email'];
        $message =    $_POST['message'];

        $headers = "From: {$from_name} {$email}";


        $result = mail($to, $subject, $message, $headers);
        if (!$result) {
            set_message("sorry we could not deliver your email");
            redirect("contact.php");
        } else {
            set_message("your message has been sent");
            redirect("contact.php");
        }
    }
}

/****************************BACK END FUNCTIONS**************************/
