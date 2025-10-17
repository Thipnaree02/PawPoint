<?php
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu.php';

  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //สร้างเงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'add'){
     include 'vet_form_add.php' ;
  }else if ($act == 'delete'){
      include 'vet_delete.php';
  }else if ($act == 'edit'){
      include 'vet_form_edit.php';
  }else if ($act == 'image'){
      include 'vet_form_upload_image.php';
  }else{
     include 'vet_list.php';
  }

 
  include 'footer.php';

?>

