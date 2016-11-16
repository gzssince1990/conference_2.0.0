<?php
include_once "header_auth.php";

$page_title = "IT Conferences";
include_once "header.php";
?>
    <h1>Paper Review!</h1>
<?php
$response = $auth->get_papers($_SESSION['username']);

//if($_SESSION['table'] == 'reviewer'){
//    $DB_table = 'reviewer';
//    $dir = 'uploads/';
//    require_once 'DBConfig.php';
//    $files = $user->get_papers($_SESSION['username']);
//}
//else{
//    $dir = 'uploads/'.$username.'/';
//    if(!file_exists($dir)){
//        mkdir($dir,0777);
//    }
//
//    $files = scandir($dir);
//
//    if(count($files)<3){
//        echo 'No file submission yet.';
//    }
//}

switch ($response['error_code']){
    case "I00001":
        foreach ($response['files'] as $index => $file){ ?>
                <button onclick='show_paper("<?php echo $file['file_path']; ?>")'><?php echo $file['file_name']; ?></button><br>
        <?php }
        break;
    case "PA00200":
        echo "No paper is assigned to you yet.";
        break;
    case "PA00210":
        echo "Admin has no right to view paper.";
        break;
    case "PA00220";
        echo "No file submission yet.";
        break;
    default:
        echo "Invalid action";
}


//foreach ($files as $index=>$file){
//    if($file!='.' and $file!='..'){
//        //$fileStr = substr($file,0, strripos($file, '.'));
//        $path = $dir.$file;
//        echo "<button onclick=\"test('$path')\">$file</button>".'<br>';
//    }
//}

?>

    <br>
    <div id="frameDiv">
        <iframe id="frame" src="http://www.w3schools.com" width='700px' height='700px' type='application/pdf'></iframe>
    </div>
    <script type="text/javascript">
        document.getElementById('frameDiv').style.display = 'none';

        function show_paper(path){
            var pdfWindow = document.getElementById('frame').contentWindow;
            pdfWindow.location.href = path;
            if(document.getElementById('frameDiv').style.display === 'none'){
                document.getElementById('frameDiv').style.display = 'block';
            }
        }

    </script>


<?php include_once 'footer.php';