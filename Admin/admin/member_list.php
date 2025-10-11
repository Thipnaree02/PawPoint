<?php
// คิวรี่ข้อมูลสมาชิก
    $queryMember = $connextdb->prepare("SELECT* FROM member");
    $queryMember->execute();
    $rsmember= $queryMember->fetchAll();

    // echo '<pre>';
    // $queryMember->debugDumpParams();
    // exit;
?>


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>จัดการข้อมูลสมาชิก

            <a href = "member.php?act=add" class = "btn btn-info">+ข้อมูล</a>
            
            </h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example1" class="table table-bordered table-striped table-sm">
                  <thead>
                  <tr class="table-primary">
                    <th width = "10%" class = "text-center">ID.</th>
                    <th width = "70%" class = "text-center">ชื่อ - นามสกุล</th>
                    <th width = "10%" class = "text-center">แก้ไขข้อมูล</th>
                    <th width = "10%" class = "text-center">ลบ</th>
                  </tr>
                  </thead>
                  <tbody>
                <?php foreach($rsmember as $row){
                ?>
                  <tr>
                    <td align = "center"><?=$row['id'];?></td>
                    <td><?=$row['name'].' '.$row['surname'];?></td>

                    <td align = "center">
                      <a href = "member.php?id=<?=$row['id'];?>&act=edit" class="btn btn-warning btn-sm">แก้ไข</a>
                    </td>

                    <td align = "center">
                      <a href = "member.php?id=<?=$row['id'];?>&act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลยข้อมูล?')">ลบ</a>
                    </td>
                  </tr>
                <?php } ?>
                  </tbody>
                  <tfoot>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col -->
        </div>
        <!-- /.row -->
      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->