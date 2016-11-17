<?php
/**
 * Created by PhpStorm.
 * User: Zhisong Ge
 * Date: 11/17/2016
 * Time: 2:00 PM
 */

require_once "../class/Book.php";
$book = new Book();

$edit_book_id = 0;

if(filter_input(INPUT_POST, 'action')){
    //Handle different post request
    $action = filter_input(INPUT_POST, 'action');
    if($action == 'book_edit'){//book edit
        $update_data['book_name'] = filter_input(INPUT_POST, 'book_name');
        $update_data['book_price'] = filter_input(INPUT_POST, 'book_price');
        $book_id = filter_input(INPUT_POST, 'book_id');
        $book->update_book($update_data, $book_id);
    }
    elseif ($action == 'book_add'){
        $insert_data['book_name'] = filter_input(INPUT_POST, 'book_name');
        $insert_data['book_price'] = filter_input(INPUT_POST, 'book_price');
        $book->add_book($insert_data);
    }
    elseif ($action == 'book_delete'){
        $book_id = filter_input(INPUT_POST, 'book_id');
        $book->delete_book($book_id);
    }
}
elseif (filter_input(INPUT_GET, 'action')) {
    //Handle different get request;
    $action = filter_input(INPUT_GET, 'action');
    if($action == 'edit_mode'){//Log out;
        $edit_book_id = filter_input(INPUT_GET, 'book_id');
    }
}


$book_list = $book->get_book_list();

//var_dump($book_list);
?>
<h1>Book Store Manager</h1>

<table border="1">
    <thead>
    <tr>
        <th>No.</th>
        <th><label for="book_name">Book Name</label></th>
        <th><label for="book_price">Price</label></th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($book_list as $key => $book_item){ ?>
        <tr>
            <td><?php echo $key+1; ?></td>
            <?php if ($edit_book_id == $book_item['book_id']){ ?>
                <form action="book_store.php" method="post">
                    <input type="hidden" name="action" value="book_edit">
                    <input type="hidden" name="book_id" value="<?php echo $book_item['book_id']; ?>">
                    <td><input type="text" name="book_name" id="book_name" value="<?php echo $book_item['book_name']; ?>"></td>
                    <td><input type="text" name="book_price" id="book_price" value="<?php echo $book_item['book_price']; ?>"></td>
                    <td>
                        <input type="submit" value="confirm">
                        <button type="button" onclick="window.location.href='book_store.php'">cancel</button>
                    </td>
                </form>
            <?php } else { ?>
                <td><?php echo $book_item['book_name']; ?></td>
                <td><?php echo $book_item['book_price']; ?></td>
                <td>
                    <button onclick="book_edit('<?php echo $book_item['book_id']; ?>')">edit</button>
                    <button onclick="book_delete('<?php echo $book_item['book_id']; ?>')">delete</button>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
    <tr>
        <form action="book_store.php" method="post">
            <input type="hidden" name="action" value="book_add">
            <td><?php echo count($book_list)+1; ?></td>
            <td><input type="text" name="book_name"></td>
            <td><input type="text" name="book_price"></td>
            <td><input type="submit" value="add"></td>
        </form>
    </tr>
    </tbody>
</table>

<script>
    function book_edit(book_id) {
        window.location.href = "book_store.php?action=edit_mode&book_id="+book_id;
    }

    function book_delete(book_id) {
        var form = document.createElement("form");
        form.method = "post";
        form.action = "book_store.php";
        form.style.display = 'none';

        var input_action = document.createElement("input");
        input_action.value = "book_delete";
        input_action.name = "action";

        var input_book_id = document.createElement("input");
        input_book_id.value = book_id;
        input_book_id.name = "book_id";

        form.appendChild(input_action);
        form.appendChild(input_book_id);
        document.body.appendChild(form);
        form.submit();
    }
</script>