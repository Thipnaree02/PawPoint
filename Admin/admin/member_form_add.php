<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มเพิ่มข้อมูลสมาชิก </h1>
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
                                        <label class="col-sm-2">Email/Username</label>
                                        <div class="col-sm-4">
                                            <input type="email" name="username" class="form-control" required
                                                placeholder="Email">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" name="password" class="form-control" required
                                                placeholder="Password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">คำนำหน้า</label>
                                        <div class="col-sm-2">
                                            <select name="title_name" class="form-control" required>
                                                <option value="">-- เลือกข้อมูล --</option>
                                                <option value="นาย">-- นาย --</option>
                                                <option value="นาง">-- นาง --</option>
                                                <option value="นางสาว">-- นางสาว --</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="name" class="form-control" required
                                                placeholder="ชื่อ">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">นามสกุล</label>
                                        <div class="col-sm-4">
                                            <input type="text" name="surname" class="form-control" required
                                                placeholder="นามสกุล">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
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
                            //เช็ค input ที่ส่งมาจากฟอร์ม
                            // echo '<pre>';
                            // print_r($_POST);
                            // exit;
                            
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
                                    if($stmtMemberDetail->rowCount() ==1){
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
                                    }else{
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

                                if($result){
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
                                    } //else if result
                                 } //เช็คข้อมูลซ้ำ
                            } //isset
?>