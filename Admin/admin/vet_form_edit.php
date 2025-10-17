<?php
    //คิวรี่รายละเอียดสัตวแพทย์ single row
    $stmtVetDetail = $connextdb->prepare("
    SELECT p.*, t.type_name 
    FROM tbl_vet as p 
    INNER JOIN tbl_type as t ON p.specialty = t.type_id
    WHERE p.id=:id");
    //bindParam
    $stmtVetDetail->bindParam(':id', $_GET['id'], PDO::PARAM_INT);
    $stmtVetDetail->execute();
    $rowVet = $stmtVetDetail->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>';
    // print_r($rowVet);
    //exit;
    // echo $stmtVetDetail->rowCount();
    // exit;

    //สร้างเงื่อนไขการตรวจสอบคิวรี่

    if($stmtVetDetail->rowCount() == 0){    //คิวรี่ผิดพลาด
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
        exit;

    }

    // คิวรี่ข้อมูลแผนกสัตวแพทย์
    $queryType = $connextdb->prepare("SELECT* FROM tbl_type");
    $queryType ->execute();
    $rsvet= $queryType->fetchAll();
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มแก้ไขข้อมูลสัตวแพทย์</h1>
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
                                        <label class="col-sm-2">ความเชี่ยวชาญ</label>
                                        <div class="col-sm-4">
                                            <select name="specialty" class="form-control" required>

                                                <option value="<?php echo $rowVet['specialty']; ?>">-- <?php echo $rowVet['type_name']; ?> --</option>
                                                
                                                <option disabled>-- เลือกข้อมูลใหม่ --</option>

                                                <?php foreach($rsvet as $row){ ?>

                                                <option value="<?php echo $row['type_id']; ?>">-- <?php echo $row['type_name']; ?> --</option>
                                                
                                                <?php } ?>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ - สกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="vet_name" class="form-control" required
                                                placeholder="ชื่อสัตวแพทย์" value="<?php echo $rowVet['vet_name'];?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">รายละเอียดเกี่ยวกับสัตวแพทย์</label>
                                        <div class="col-sm-10">
                                            <textarea name="vet_detail" id="summernote"><?php echo $rowVet['vet_detail'];?></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">เบอร์โทร</label>
                                        <div class="col-sm-4">
                                            <input type="tel" name="phone" class="form-control" required
                                                placeholder="กรอกเบอร์โทรศัพท์" pattern="[0-9]{10}" maxlength="10" value="<?php echo $rowVet['phone'];?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Email</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="email" class="form-control" required
                                                placeholder="กรอกอีเมลของคุณ" value="<?php echo $rowVet['email'];?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ภาพ</label>
                                        <div class="col-sm-4">
                                            ภาพเก่า <br>
                                            <img src="../assets/vet_img/<?php echo $rowVet['vet_image'];?>" width="200px">
                                            <br> <br>
                                            เลือกภาพใหม่
                                            <br>
                                            <div class="input-group">
                                                <div class="custom-file">
                                                    <input type="file" name="vet_image" class="custom-file-input" id="exampleInputFile">
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
                                            <input type="hidden" name="id" value="<?php echo $rowVet['id'];?>">
                                            <input type="hidden" name="oldImg" value="<?php echo $rowVet['vet_image'];?>">
                                            <button type="submit" class="btn btn-primary">บันทึก</button>
                                            <a href="vet.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>


                                </div><!-- /.card-body -->
                            </form>
                            
                            <?php
                                // echo '<pre>';
                                // print_r($_POST);
                                // echo '<hr>';
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
            // exit;

if (isset($_POST['vet_name']) && isset($_POST['specialty']) && isset($_POST['phone'])) {
    //echo 'ถูกเงื่อนไข ส่งข้อมูลมาได้';

    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $specialty = $_POST['specialty'];
    $vet_name = $_POST['vet_name'];
    $vet_detail = $_POST['vet_detail'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $id = $_POST['id'];
    $upload=$_FILES['vet_image']['name'];

    //สร้างเงื่อนไขตรวจสอบการอัพโหลดไฟล์
    if($upload ==''){
        //echo 'ไม่มีการอัพโหลดไฟล์';
        //sql update without update file
        $stmtUpdateVet = $connextdb->prepare("UPDATE tbl_vet SET
            vet_name=:vet_name,
            vet_detail=:vet_detail,
            specialty=:specialty,
            phone=:phone,
            email=:email
            WHERE id=:id
        ");

        //bindParam
        $stmtUpdateVet->bindParam(':id', $id, PDO::PARAM_INT);
        $stmtUpdateVet->bindParam(':specialty', $specialty, PDO::PARAM_STR);
        $stmtUpdateVet->bindParam(':vet_name', $vet_name, PDO::PARAM_STR);
        $stmtUpdateVet->bindParam(':vet_detail', $vet_detail, PDO::PARAM_STR);
        $stmtUpdateVet->bindParam(':phone', $phone, PDO::PARAM_STR);
        $stmtUpdateVet->bindParam(':email', $email, PDO::PARAM_STR);
        $result = $stmtUpdateVet->execute();
        if($result){
                echo '<script>
                     setTimeout(function() {
                      swal({
                          title: "ปรับปรุงข้อมูลสำเร็จ",
                          type: "success"
                      }, function() {
                          window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
                      });
                    }, 1000);
                </script>';
            }else{
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


    }else{
            //echo 'มีการอัพโหลดไฟล์';
            //สร้างตัวแปรวันที่เพื่อเอาไปตั้งชื่อไฟล์ใหม่
            $date1 = date("Ymd_His");
            //สร้างตัวแปรสุ่มตัวเลขเพื่อเอาไปตั้งชื่อไฟล์ที่อัพโหลดไม่ให้ชื่อไฟล์ซ้ำกัน
            $numrand = (mt_rand());
            $vet_image = (isset($_POST['vet_image']) ? $_POST['vet_image'] : '');
            
    
                //ตัดขื่อเอาเฉพาะนามสกุล
                $typefile = strrchr($_FILES['vet_image']['name'],".");

                // echo $typefile;
                // exit;
        
                //สร้างเงื่อนไขตรวจสอบนามสกุลของไฟล์ที่อัพโหลดเข้ามา
                if($typefile =='.jpg' || $typefile  =='.jpeg' || $typefile  =='.png'){
                    // echo 'อัพโหลดไฟล์ไม่ถูกต้อง';
                    // exit;
                    
        
                    //ลบภาพเก่า
                    unlink('../assets/vet_img/'.$_POST['oldImg']);

                    //โฟลเดอร์ที่เก็บไฟล์
                    $path="../assets/vet_img/";
                    //ตั้งชื่อไฟล์ใหม่เป็น สุ่มตัวเลข+วันที่
                    $newname = $numrand.$date1.$typefile;
                    $path_copy=$path.$newname;
                    //คัดลอกไฟล์ไปยังโฟลเดอร์
                    move_uploaded_file($_FILES['vet_image']['tmp_name'],$path_copy); 

                    //sql update with upload file
                     $stmtUpdateVet = $connextdb->prepare("UPDATE tbl_vet SET
                        vet_name=:vet_name,
                        vet_detail=:vet_detail,
                        specialty=:specialty,
                        phone=:phone,
                        email=:email,
                        vet_image='$newname'
                        WHERE id=:id
                    ");

                    //bindParam
                    $stmtUpdateVet->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmtUpdateVet->bindParam(':specialty', $specialty, PDO::PARAM_STR);
                    $stmtUpdateVet->bindParam(':vet_name', $vet_name, PDO::PARAM_STR);
                    $stmtUpdateVet->bindParam(':vet_detail', $vet_detail, PDO::PARAM_STR);
                    $stmtUpdateVet->bindParam(':phone', $phone, PDO::PARAM_STR);
                    $stmtUpdateVet->bindParam(':email', $email, PDO::PARAM_STR);
                    $result = $stmtUpdateVet->execute();
                    if($result){
                            echo '<script>
                                setTimeout(function() {
                                swal({
                                    title: "ปรับปรุงข้อมูลสำเร็จ",
                                    type: "success"
                                }, function() {
                                    window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
                                });
                                }, 1000);
                            </script>';
                        }else{
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

                }else{

                echo '<script>
                            setTimeout(function() {
                            swal({
                                title: "คุณอัพโหลดไฟล์ไม่ถูกต้อง",
                                type: "error"
                            }, function() {
                                window.location = "vet.php?id='.$id.'&act=edit";
                            });
                            }, 1000);
                        </script>';
                        //exit;
                        
                }   //else update file

    }   //else not upload file
    
} //isset
?>