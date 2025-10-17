<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มอัพโหลดภาพสัตวแพทย์</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-outline card-info">
                    <div class="card-body">
                        <div class="card card-primary">
                            <!-- form start -->
                            <form action="" method="post" enctype="multipart/form-data">
                                <div class="card-body">

                                    <div class="form-group row">
                                        <label class="col-sm-2">ภาพ</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="vet_image[]" class="custom-file-input"
                                                        id="exampleInputFile" accept="image/*" multiple="multiple">
                                                    <label class="custom-file-label" for="exampleInputFile">Choose
                                                        file</label>
                                                </div>
                                                <div class="input-group-append">
                                                    <span class="input-group-text">Upload</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="p_id" value="<?=$_GET['id'];?>">
                                            <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
                                            <a href="vet.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>


                                </div><!-- /.card-body -->
                            </form>

                            <?php
                            // echo '<pre>';
                            // // print_r($_POST);
                            // // echo '<hr>';
                            // print_r($_FILES);
                            // exit;
                            
                            ?>







                        </div>
                    </div>
                </div>
            </div>
            <!-- /.col-->
        </div>
        <!-- ./row -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
//เช็ค input ที่ส่งมาจากฟอร์ม
// echo '<pre>';
// print_r($_POST);
// echo '<hr>';
// print_r($_FILES);
// exit;


if (isset($_POST['p_id'])) {

    //$files = array_filter($_FILES['upload']['name']); //something like that to be used before processing files.

    // Count # of uploaded files in array
    $total = count($_FILES['vet_image']['name']);
    // echo $total;
    // exit;

    // Loop through each file
    for ($i = 0; $i < $total; $i++) {

        //Get the temp file path
        $tmpFilePath = $_FILES['vet_image']['tmp_name'][$i];

        //Make sure we have a file path
        if ($tmpFilePath != "") {
            //Setup our new file path
            $newFilePath = "../assets/vet_gallery/" . $_FILES['vet_image']['name'][$i];

            //Upload the file into the temp dir
            if (move_uploaded_file($tmpFilePath, $newFilePath)) {

                //Handle other code here

            }
        }
    }
 
}   //if isset



exit;

if (isset($_POST['vet_name']) && isset($_POST['specialty']) && isset($_POST['phone'])) {
    //echo 'ถูกเงื่อนไข ส่งข้อมูลมาได้';

    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $specialty = $_POST['specialty'];
    $vet_name = $_POST['vet_name'];
    $vet_detail = $_POST['vet_detail'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];


    //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
    $date1 = date("Ymd_His");
    //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
    $numrand = (mt_rand());
    $vet_image = (isset($_POST['vet_image']) ? $_POST['vet_image'] : '');
    $upload = $_FILES['vet_image']['name'];

    //มีการอัพโหลดไฟล์
    if ($upload != '') {
        //ตัดขื่อเอาเฉพาะนามสกุล
        $typefile = strrchr($_FILES['vet_image']['name'], ".");

        //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
        if ($typefile == '.jpg' || $typefile == '.jpeg' || $typefile == '.png') {

            //โฟลเดอร์ที่เก็บไฟล์
            $path = "../assets/vet_img/";
            //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
            $newname = $numrand . $date1 . $typefile;
            $path_copy = $path . $newname;
            //คัดลอกไฟล์ไปยังโฟลเดอร์
            move_uploaded_file($_FILES['vet_image']['tmp_name'], $path_copy);


            //sql insert
            $stmtInsertVet = $connextdb->prepare("INSERT INTO tbl_vet 
                                (
                                    vet_name,
                                    vet_detail,
                                    specialty,
                                    phone,
                                    email,
                                    vet_image
                                )
                                VALUES 
                                (
                                    :vet_name,
                                    :vet_detail,
                                    :specialty,
                                    :phone,
                                    :email,
                                    '$newname'
                                )
                                ");

            //bindParam
            $stmtInsertVet->bindParam(':specialty', $specialty, PDO::PARAM_STR);
            $stmtInsertVet->bindParam(':vet_name', $vet_name, PDO::PARAM_STR);
            $stmtInsertVet->bindParam(':vet_detail', $vet_detail, PDO::PARAM_STR);
            $stmtInsertVet->bindParam(':phone', $phone, PDO::PARAM_STR);
            $stmtInsertVet->bindParam(':email', $email, PDO::PARAM_STR);
            $result = $stmtInsertVet->execute();

            $connextdb = null; //close connect  db

            //เงื่อนไขตรวจสอบการเพิ่มข้อมูล
            if ($result) {
                echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "เพิ่มข้อมูลสำเร็จ",
                          type: "success"
                      }, function() {
                          window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>';
            } else {
                echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "เกิดข้อผิดพลาด",
                          type: "error"
                      }, function() {
                          window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>';
            } //else ของ if result


        } else { //ถ้าไฟล์ที่อัพโหลดไม่ตรงตามที่กำหนด
            echo '<script>
                            setTimeout(function() {
                            swal({
                                title: "คุณอัพโหลดไฟล์ไม่ถูกต้อง",
                                type: "error"
                            }, function() {
                                window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
                            });
                            }, 1000);
                        </script>';
        } //else ของเช็คนามสกุลไฟล์

    } // if($upload !='') {


} //isset
?>