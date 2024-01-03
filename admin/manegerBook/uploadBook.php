<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/convert-Bytes.php";
require_once "../../functions/check_login_admin.php";
global $connection;
//check is not null and empty
if(isset($_POST["nameBook"]) && $_POST["nameBook"] !== ""
&& isset($_POST["Publications"]) && $_POST["Publications"] !== ""
&& isset($_POST["Writer"]) && $_POST["Writer"] !== ""
&& isset($_POST["cat_id"]) && $_POST["cat_id"] !== ""
&& isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["name"] !== ""
&& isset($_FILES["PicPdf"]) && $_FILES["PicPdf"]["name"] !== ""){

    //check exsist category id
    $qury = "SELECT * FROM categories WHERE id = ?;";
    $statment = $connection->prepare($qury);
    $statment->execute([$_POST["cat_id"]]);
    $catgory = $statment->fetch();
    

    // for check type image 
    $allowedMimes = ['png', 'jpge', 'jpg', 'gif'];
    //Getting the type of photo from the user
    $imageMimes = pathinfo($_FILES["PicPdf"]["name"], PATHINFO_EXTENSION);
    if(!in_array($imageMimes, $allowedMimes)){
       redirect("admin");
    }
    // dirname() = ../
    $basePath = dirname(dirname(__DIR__));
    // set sdress and set new name 
    $image = "/assets/images/PicBook/". date("Y_m_d_H_i_s").".".$imageMimes;
    $move_image = move_uploaded_file($_FILES["PicPdf"]["tmp_name"], $basePath.$image);

    //config upload pdf 
    $allowedMimesPdf = ['pdf'];
    $pdfMimes = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
    if(!in_array($pdfMimes, $allowedMimesPdf)){
        redirect("admin");
    }
    $pdf = "/assets/pdf/". $_POST["nameBook"]."_from_".$_POST["Writer"].".".$pdfMimes;
    $move_pdf = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $basePath.$pdf);

    //size pdf 
    $sizePdf =covertToReadableSize($_FILES["fileToUpload"]["size"]);


    if($catgory->id !== false && $move_image !== false && $move_pdf !== false ){

        $qury = "INSERT INTO `books` (`nameBook`, `Publications`, `Writer`,`category_id` ,`Size`, `addressBook`,`addressPic`) VALUES (?, ?, ? ,? , ?, ?, ?);";
        $statment = $connection->prepare($qury);
        $statment->execute([$_POST["nameBook"], $_POST["Publications"], $_POST["Writer"], $_POST["cat_id"], $sizePdf, $pdf, $image]);
        redirect("admin/manegerBook");
    }else{
        redirect("admin");
    }
    



}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= asset("assets/css/showitem.css")?>">
    <title>upload pdf</title>
</head>
<body >
<?php 
        require_once "../../layut/navbarTop.php";
    ?>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="<?=url("admin/manegerBook")?>" class="btn btn-outline-light">Back</a>
        <a href="<?=url("auth/logout.php");?>" class="btn btn-outline-light">Logout</a>
    </div>
    <h2>Upload books</h2>
    <a href="<?=url("admin/manegerBook/uploadBook.php");?>" class="btn btn-outline-light m-3" type="button">Upload Book</a>
    <?php 
        require_once "../../layut/navbarDown.php";
    ?>
    <div class="container-xl fix">

        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Upload Book</h4>
            <p dir="rtl"> سلام خوش آمدید <br> ادمین محترم شما میتوانید کتاب مدنظر خود را همراه با تصویر کتاب بارگذاری کنید روی سرور  </p>
            <hr>
            <p dir="rtl" class="mb-0"> توجه داشته باشید شما می‌توانید از قسمت manager Book و بازدن دکمه Edit کتاب خود را ویرایش کنید </p>
        </div>
        <form class="row g-3" action="<?= url("admin/manegerBook/uploadBook.php");?>" method="post" enctype="multipart/form-data">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Name Book</label>
                <input type="text" class="form-control" id="inputEmail4" name="nameBook" placeholder="Add name Book" >
            </div>
            <div class="col-md-6">
                <label for="Publications" class="form-label">Publications</label>
                <input type="text" class="form-control" name="Publications" id="Publications" placeholder="Publications">
            </div>
            <div class="col-6">
                <label for="Writer" class="form-label">Writer</label>
                <input type="text" class="form-control" id="Writer" placeholder=" Writer" name="Writer" placeholder="Writer">
            </div>
            <div class="col-6">
                <label class="form-label" for="cat_id">Category</label>
                <select class="form-control" name="cat_id" id="cat_id">
                <!-- get category.name in database and show whit <option></option> -->
                <?php 
                $qury = "SELECT * FROM categories ;";
                $statment = $connection->prepare($qury);
                $statment->execute();
                $catgorys = $statment->fetchAll();
                foreach($catgorys as $category){
                ?>
                <option value="<?= $category->id?>"><?= $category->name?></option>
                <?php }?>
            </div>
            <div class="col-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">Upload pdf</label>
                    <input type="file" class="form-control" id="inputGroupFile01" name="fileToUpload">
                </div>
            </div>
            <div class="col-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile02">Upload Pic Pdf</label>
                    <input type="file" class="form-control" id="inputGroupFile02" name="PicPdf">
                </div>
            </div>
            <div class="">
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>    
    </div>
    


<script src="<?= asset('assrts/js/jquery.min.js') ;?>"></script>
<script src="<?= asset('assrts/js/bootstrap.min.js') ;?>"></script>
</body>
</html>