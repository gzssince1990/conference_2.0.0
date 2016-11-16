<?php
include_once "header_auth.php";

$page_title = "IT Conferences";
include_once "header.php";
?>
    <h1>Paper Submission!</h1>

    <form action="controller.php" method="post" enctype="multipart/form-data">

        <input type="hidden" name="action" value="paper_upload">
        <table>
            <tr>
                <td><label for="title">Paper Title</label></td>
                <td><input type="text" name="title" id="title"></td>
            </tr>
            <tr>
                <td><label for="area">Area</label></td>
                <td><input type="text" name="area" id="area"></td>
            </tr>
            <tr>
                <td><label for="subarea">Subarea</label></td>
                <td><input type="text" name="subarea" id="subarea"></td>
            </tr>
<!--            <tr>-->
<!--                <td>Author</td>-->
<!--                <td><input type="text" name="author" id="author"></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Affiliation</td>-->
<!--                <td><input type="text" name="affiliation" id="affiliation"></td>-->
<!--            </tr>-->
<!--            <tr>-->
<!--                <td>Email</td>-->
<!--                <td><input type="text" name="email" id="email"></td>-->
<!--            </tr>-->
            <tr>
                <td>Select PDF file to upload:</td>
                <td><input type="file" name="fileToUpload" id="fileToUpload"></td>
            </tr>
            <tr>
                <td><input type="submit" value="Submit" name="submit"></td>
            </tr>
        </table>
    </form>
<?php include_once 'footer.php';