<?php
  include 'header.php';
  include 'navbar.php';
  include 'sidebar_menu.php';

  $act = (isset($_GET['act']) ? $_GET['act'] : '');

  //สร้างเงื่อนไขในการเรียกใช้ไฟล์
  if($act == 'add'){
     include 'member_form_add.php' ;
  }else{
     include 'member_list.php';
  }

 
  include 'footer.php';

?>

