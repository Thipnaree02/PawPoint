<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มเพิ่มข้อมูลสัตวแพทย์</h1>
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
                                        <div class="col-sm-2">
                                            <select name="specialty" class="form-control" required>
                                                <option value="">-- เลือกข้อมูล --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ - สกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="vet_name" class="form-control" required
                                                placeholder="ชื่อสัตวแพทย์">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">เบอร์โทร</label>
                                        <div class="col-sm-4">
                                            <input type="tel" name="phone" class="form-control" required
                                                placeholder="กรอกเบอร์โทรศัพท์" pattern="[0-9]{10}" maxlength="10">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Email</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="email" class="form-control" required
                                                placeholder="กรอกอีเมลของคุณ">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ภาพ</label>
                                        <div class="col-sm-4">
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
                                            <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
                                            <a href="vet.php" class="btn btn-danger">ยกเลิก</a>
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
//เช็ค input ที่ส่งมาจากฟอร์ม
// echo '<pre>';
// print_r($_POST);
exit;

if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['surname'])) {
    //echo 'ถูกเงื่อนไข ส่งข้อมูลมาได้';

    //ประกาศตัวแปรรับค่าจากฟอร์ม
    $username = $_POST['username'];
    $password = sha1($_POST['password']);
    // echo $password;
    // exit;
    $title_name = $_POST['title_name'];
    $name = $_POST['name'];
    $surname = $_POST['surname'];

    //เช็ค Username ซ้ำ
    // single row query แสดงแค่ 1 รายการ
    $stmtMemberDetail = $connextdb->prepare("SELECT username FROM member 
                                    WHERE username=:username
                                    ");
    //bindParam
    $stmtMemberDetail->bindParam(':username', $username, PDO::PARAM_STR);
    $stmtMemberDetail->execute();
    $row = $stmtMemberDetail->fetch(PDO::FETCH_ASSOC);

    //นับจำนวนการคิวรี่ ถ้าได้ 1 คือ username ซ้ำ
    // echo $stmtMemberDetail->rowCount();
    // echo '<hr>';
    if ($stmtMemberDetail->rowCount() == 1) {
        // echo '๊Username ซ้ำ';
        echo '<script>
                                            setTimeout(function() {
                                            swal({
                                                title: "Username ซ้ำ !!",
                                                text: "กรุณาเพิ่มข้อมูลใหม่อีกครั้ง",
                                                type: "error"
                                            }, function() {
                                                window.location = "member.php?act=add"; //หน้าที่ต้องการให้กระโดดไป
                                            });
                                            }, 1000);
                                        </script>';
    } else {
        // echo 'ไม่มี username ซ้ำ';
        //sql insert
        $stmtInserMember = $connextdb->prepare("INSERT INTO member 
                                (
                                    username,
                                    password,
                                    title_name,
                                    name, 
                                    surname
                                )
                                VALUES 
                                (
                                    :username,
                                    '$password',
                                    :title_name,
                                    :name, 
                                    :surname
                                )
                                ");

        //bindParam
        $stmtInserMember->bindParam(':username', $username, PDO::PARAM_STR);
        $stmtInserMember->bindParam(':title_name', $title_name, PDO::PARAM_STR);
        $stmtInserMember->bindParam(':name', $name, PDO::PARAM_STR);
        $stmtInserMember->bindParam(':surname', $surname, PDO::PARAM_STR);
        $result = $stmtInserMember->execute();

        $connextdb = null; //close connect  db

        if ($result) {
            echo '<script>
                                            setTimeout(function() {
                                            swal({
                                                title: "เพิ่มข้อมูลสำเร็จ",
                                                type: "success"
                                            }, function() {
                                                window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
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
                                                window.location = "member.php"; //หน้าที่ต้องการให้กระโดดไป
                                            });
                                            }, 1000);
                                        </script>';
        } //else if result
    } //เช็คข้อมูลซ้ำ
} //isset
?>