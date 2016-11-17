<?php
/**
 * Created by PhpStorm.
 * User: Zhisong Ge
 * Date: 11/17/2016
 * Time: 1:29 PM
 */

include_once "header_auth.php";

$page_title = "IT Conferences";
include_once "header.php";

require_once "class/Book.php";
$book = new Book();
$book_list = $book->get_book_list();

//var_dump($book_list);
?>
<h1>Book Store!</h1>

<table border="1">
    <thead>
    <tr>
        <th>No.</th>
        <th>Book Name</th>
        <th>Price</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($book_list as $key => $book_item){ ?>
        <tr>
            <td><?php echo $key; ?></td>
            <td><?php echo $book_item['book_name']; ?></td>
            <td><?php echo $book_item['book_price']; ?></td>
            <td><button>add to cart</button></td>
        </tr>
    <?php } ?>
    </tbody>
</table>