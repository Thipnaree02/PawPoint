<?php
// คิวรี่ข้อมูลสมาชิก
    $queryvet = $connextdb->prepare("SELECT* FROM tbl_vet");
    $queryvet->execute();
    $rsvet= $queryvet->fetchAll();

    // echo '<pre>';
    // $queryvet->debugDumpParams();
    // exit;
?>


<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>จัดการข้อมูลสัตวแพทย์

            <a href = "vet.php?act=add" class = "btn btn-info">+ข้อมูล</a>
            
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
                    <th width = "5%" class = "text-center">No.</th>
                    <th width = "5%">ภาพ</th>
                    <th width = "13%" class = "text-center">ความเชี่ยวชาญ</th>
                    <th width = "26%">ชื่อ - สกุล</th>
                    <th width = "16%" class = "text-center">เบอร์โทร</th>
                    <th width = "20%" class = "text-center">Email</th>
                    <th width = "10%" class = "text-center">แก้ข้อมูล</th>
                    <th width = "5%" class = "text-center">ลบ</th>
                  </tr>
                  </thead>
                  <tbody>
                <?php 
                $i = 1; //start number
                foreach($rsvet as $row){
                ?>
                  <tr>
                    <td align = "center"> <?php echo $i++ ?> </td>
                    <td><img src="" width="70px"></td>
                    <td><?=$row['specialty'];?></td>
                    <td><?=$row['vet_name'];?></td>
                    <td><?=$row['phone'];?></td>
                    <td><?=$row['email'];?></td>

                    <td align = "center">
                      <a href = "vet.php?id=<?=$row['id'];?>&act=edit" class="btn btn-warning btn-sm">แก้ไข</a>
                    </td>

                    <td align = "center">
                      <a href = "vet.php?id=<?=$row['id'];?>&act=delete" class="btn btn-danger btn-sm" onclick="return confirm('ยืนยันการลยข้อมูล?')">ลบ</a>
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