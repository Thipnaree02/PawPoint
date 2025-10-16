<?php
if(isset($_GET['id']) && $_GET['act']=='delete'){
    
    $id = $_GET['id'];
    //echo $id;

    // single row query แสดงแค่ 1 รายการ
      $stmtVetDetail = $connextdb->prepare("SELECT vet_image FROM tbl_vet WHERE id=?");
      $stmtVetDetail->execute([$_GET['id']]);
      $row = $stmtVetDetail->fetch(PDO::FETCH_ASSOC);

      // แสดงชื่อไฟล์ภาพ
    //   echo 'image name'. $row['vet_image'];
    //   exit;

    // แสดงจำนวนคิวรี่ที่ได้ row
    // echo $stmtVetDetail->rowCount();
    // exit;

    // สร้างเงื่อนไขในการลบภาพ

    if($stmtVetDetail->rowCount() == 0){
        //echo 'เด้งออกไป';
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
    }else{
        //echo 'ส่งไปลบข้อมูลและภาพได้';

        // sql delete
$stmtDelVet = $connextdb->prepare('DELETE FROM tbl_vet WHERE id=:id');
$stmtDelVet->bindParam(':id', $id , PDO::PARAM_INT);
$stmtDelVet->execute();

$connextdb = null; //close connect  db
//echo 'จำนวน row ที่ลบได้' .$stmtDelVet->rowCount();

 if($stmtDelVet->rowCount() ==1){

    // ลบไฟล์ภาพ
    unlink('../assets/vet_img/'.$row['vet_image']);

        echo '<script>
             setTimeout(function() {
              swal({
                  title: "ลบข้อมูลสำเร็จ",
                  type: "success"
              }, function() {
                  window.location = "vet.php"; //หน้าที่ต้องการให้กระโดดไป
              });
            }, 1000);
        </script>';
        exit();
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
    } // sweet alert

    } //row count
} //isset

?>