<?php
    if(isset($_GET['id']) && $_GET['act'] == 'edit'){

    // single row query แสดงแค่ 1 รายการ
      $stmtMemberDetail = $connextdb->prepare("SELECT* FROM member WHERE id=?");
      $stmtMemberDetail->execute([$_GET['id']]);
      $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

    // echo '<pre>';
    // print_r($row);
    // exit;
    // echo $stmtMemberDetail->rowCount();
    // exit;

      //ถ้าคิวรี่ผิดพลาดให้หยุดการทำงาน
      if($stmtMemberDetail->rowCount() !=1){
          exit();
      }
    }//isset
?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มแก้ไขข้อมูลสมาชิก </h1>
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
                                        <label class="col-sm-1">ชื่อ</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required
                                                placeholder="ชื่อ" value="<?php echo $row['name'];?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-1">นามสกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="surname" class="form-control" required
                                                placeholder="นามสกุล" value="<?php echo $row['surname'];?>">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                                            <button type="submit" class="btn btn-primary">บันทึก</button>
                                            <a href="member.php" class="btn btn-danger">ยกเลิก</a>
                                        </div>
                                    </div>


                                </div><!-- /.card-body -->

                            </form>

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
                        //    echo '<pre>';
                        //    print_r($_POST);
                        //    //exit;

                           if(isset($_POST['id']) && isset($_POST['name']) && isset($_POST['surname'])){
                        
                            // echo 'เข้ามาในเงื่อนไขได้';
                            // exit;

                            // ประกาศตัวแปรรับค่าจากฟอร์ม
                            $id = $_POST['id'];
                            $name = $_POST['name'];
                            $surname = $_POST['surname'];


                            //sql update
                            $stmtUpdate = $connextdb->prepare("UPDATE  member SET 
                            name=:name, 
                            surname=:surname 
                            WHERE id=:id
                            ");
                            //bindParam
                            $stmtUpdate->bindParam(':id', $id , PDO::PARAM_INT);
                            $stmtUpdate->bindParam(':name', $name , PDO::PARAM_STR);
                            $stmtUpdate->bindParam(':surname', $surname , PDO::PARAM_STR);
                            $stmtUpdate->execute();

                            $connextdb = null; //close connect  db

                             if($result){
                                echo '<script>
                                    setTimeout(function() {
                                    swal({
                                        title: "แก้ไขข้อมูลสำเร็จ",
                                        type: "success"
                                    }, function() {
                                        window.location = "member.php?"; //หน้าที่ต้องการให้กระโดดไป
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
                                        window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
                                    });
                                    }, 1000);
                                </script>';
                            }


                           } //isset

                           //window.location = "member.php?id='.id.'&act=edit"; //ถ้าอยากแก้ไขแล้วไม่อยากให้เปลี่ยนหน้า
                        ?>