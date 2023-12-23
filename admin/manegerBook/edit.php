<?php 
require_once "../../functions/helper.php";
require_once "../../functions/connection.php";
require_once "../../functions/convert-Bytes.php";

// user just click on edit
if(!isset($_GET["book_id"]) && $_GET["book_id"] === ""){
    redirect("admin");
}
// check exist id and equle book_id
$qury = "SELECT books.*, categories.name,categories.id AS cat_id FROM books LEFT JOIN categories ON books.category_id = categories.id WHERE books.id = ?;";
$statment = $connection->prepare($qury);
$statment->execute([$_GET["book_id"]]);
$book = $statment->fetch();
if($book->id != $_GET["book_id"]){
    redirect("admin");
}

global $connection;
// address base project
$basePath = dirname(dirname(__DIR__));
//check is not null and empty
if(isset($_POST["nameBook"]) && $_POST["nameBook"] !== ""
&& isset($_POST["Publications"]) && $_POST["Publications"] !== ""
&& isset($_POST["Writer"]) && $_POST["Writer"] !== ""
&& isset($_POST["cat_id"]) && $_POST["cat_id"] !== ""){

    if(isset($_FILES["fileToUpload"]) or isset($_FILES["PicPdf"])){
        if($_FILES["fileToUpload"]["name"] !== ""){
            if(file_exists($basePath.$book->addressBook)){
                unlink($basePath.$book->addressBook);
            }
            $allowedMimesPdf = ['pdf'];
            $pdfMimes = pathinfo($_FILES["fileToUpload"]["name"], PATHINFO_EXTENSION);
            //dd("hello");
            if(!in_array($pdfMimes, $allowedMimesPdf)){
                redirect("admin");
            }
            $pdf = "/assets/pdf/".$_POST["nameBook"]."_from_".$_POST["Writer"].".".$pdfMimes;
            $move = move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $basePath.$pdf);
            //convert size pdf from byte to mg,gb,tb...
            $sizePdf = covertToReadableSize($_FILES["fileToUpload"]["size"]);
            if($move === true){
                $qury = "UPDATE books SET nameBook=?, Publications=?, Writer=?, category_id=?, Size=?, addressBook=? WHERE id=?;";
                $statment = $connection->prepare($qury);
                $statment->execute([$_POST["nameBook"], $_POST["Publications"], $_POST["Writer"], $_POST["cat_id"], $sizePdf, $pdf, $_GET["book_id"]]);

            }
        }
        if($_FILES["PicPdf"]["name"] !== ""){
            // for check type image 
            $allowedMimes = ['png', 'jpge', 'jpg', 'gif'];
            //Getting the type of photo from the user
            $imageMime = pathinfo($_FILES["PicPdf"]["name"], PATHINFO_EXTENSION);
            if(!in_array($imageMime, $allowedMimes)){
                redirect("admin");
            }
            //check exist old photo and delete file on server
            if(file_exists($basePath.$book->addressPic)){
                unlink($basePath.$book->addressPic);
            }
            // set sdress and set new name 
            $image = "/assets/images/PicBook/".date("Y_m_d_H_i_s").".".$imageMime;
            $move = move_uploaded_file($_FILES["PicPdf"]["tmp_name"], $basePath.$image);
            if($move === true){
                $qury = "UPDATE books SET nameBook=?, Publications=?, Writer=?, category_id=?, addressPic=? WHERE id=?;";
                $statment = $connection->prepare($qury);
                $statment->execute([$_POST["nameBook"], $_POST["Publications"], $_POST["Writer"], $_POST["cat_id"], $image, $_GET["book_id"]]);
                redirect("admin/manegerBook");
            }
        }
        
    }else{
        //if user dont submit pic and pdf just value updated
        $qury = "UPDATE books SET nameBook=?, Publications=?, Writer=?, category_id=? WHERE id=?;";
        $statment = $connection->prepare($qury);
        $statment->execute([$_POST["nameBook"], $_POST["Publications"], $_POST["Writer"], $_POST["cat_id"], $_GET["book_id"]]);
        redirect("admin/manegerBook");
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
    <title>Edit pdf</title>
</head>
<body >
<?php 
        require_once "../../layut/navbarTop.php";
    ?>
    <div class="btn-group" role="group" aria-label="Basic outlined example">
        <a href="" class="btn btn-outline-light">Back</a>
        <a href="" class="btn btn-outline-light">Logout</a>
    </div>
    <h2>Edite books</h2>
    <a href="<?=url("admin/manegerBook/uploadBook.php");?>" class="btn btn-outline-light m-3" type="button">Upload Book</a>
    <?php 
        require_once "../../layut/navbarDown.php";
    ?>
    <div class="container-xl fix">

        <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Edit Book</h4>
            <p dir="rtl"> سلام خوش آمدید <br> ادمین محترم شما میتوانید کتاب مدنظر خود را همراه با تصویر کتاب بارگذاری کنید روی سرور  </p>
            <hr>
            <p dir="rtl" class="mb-0"> توجه داشته باشید شما می‌توانید از قسمت manager Book و بازدن دکمه Edit کتاب خود را ویرایش کنید </p>
        </div>
        <form class="row g-3 mb-5" action="<?= url("admin/manegerBook/edit.php?book_id=".$_GET["book_id"]);?>" method="post" enctype="multipart/form-data">
        <div class="col-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile01">Edit pdf</label>
                    <input type="file" class="form-control" id="inputGroupFile01" name="fileToUpload">
                </div>
            </div>
            <div class="col-12">
                <div class="input-group mb-3">
                    <label class="input-group-text" for="inputGroupFile02">Upload Pic Pdf</label>
                    <!-- show imge book  -->
                    <img src="<?= asset($book->addressPic);?>" alt="#" style="width:60px;">
                    <input type="file" class="form-control" id="inputGroupFile02" name="PicPdf">
                </div>
            </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Name Book</label>
                <input type="text" class="form-control" id="inputEmail4" name="nameBook" value="<?= $book->nameBook?>" >
            </div>
            <div class="col-md-6">
                <label for="Publications" class="form-label">Publications</label>
                <input type="text" class="form-control" name="Publications" id="Publications" value="<?= $book->Publications?>">
            </div>
            <div class="col-6">
                <label for="Writer" class="form-label">Writer</label>
                <input type="text" class="form-control" id="Writer" placeholder=" Writer" name="Writer" value="<?= $book->Writer?>">
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
                foreach($catgorys as $category){?>
                <option value="<?= $category->id?>" <?php if($category->id === $book->cat_id){echo 'selected';} ?> > <?= $category->name?> </option>
                <?php }?>
            </div>
            <div class="col-12">
                    <h1>hello</h1>
             </div>
            <button type="submit" class="btn btn-primary">Edit</button>
        </form>    
    </div>

<script src="<?= asset('assrts/js/jquery.min.js') ;?>"></script>
<script src="<?= asset('assrts/js/bootstrap.min.js') ;?>"></script>
</body>
</html>