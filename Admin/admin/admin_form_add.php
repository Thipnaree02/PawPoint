<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>ฟอร์มเพิ่มข้อมูล Admin </h1>
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
                                        <label class="col-sm-2">Username</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" required placeholder="Username">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2">Password</label>
                                        <div class="col-sm-4">
                                            <input type="password" class="form-control" required placeholder="password">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ชื่อ Admin</label>
                                        <div class="col-sm-4">
                                            <input type="text" class="form-control" required placeholder="ชื่อ Admin">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-2">ภาพ Profile</label>
                                        
                                        <div class="col-sm-4">

                                        <div class="input-group">
                                            
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="exampleInputFile">
                                                <label class="custom-file-label" for="exampleInputFile">เลือก file ภาพ</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                        </div>   <!-- ./ input-group -->
                                        </div>   <!-- ./ col-sm-4 -->

                                    </div>


                                    <div class="form-group row">
                                        <label class="col-sm-2"></label>
                                        <div class="col-sm-4">
                                            <button type="submit" class="btn btn-primary">เพิ่มข้อมูล</button>
                                            <a href="" class="btn btn-danger">ยกเลิก</a>
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