<!-- Modal เพิ่มผู้ใช้ (เวอร์ชันใหม่ สวยกว่าเดิม) -->
<div class="modal fade" id="addModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content border-0 shadow-lg">
      <form method="post" enctype="multipart/form-data">
        <div class="modal-header bg-gradient p-3" style="background:linear-gradient(90deg, #6fba82, #4ca771); color:white;">
          <h5 class="modal-title fw-bold"><i class="bi bi-person-plus-fill me-2"></i>เพิ่มผู้ใช้ใหม่</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>

        <div class="modal-body px-4 py-3">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person-badge me-1 text-success"></i> ชื่อ-นามสกุล</label>
              <input type="text" name="fullname" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="เช่น น.ส. ทิพย์นารี เพตาเสน" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person me-1 text-success"></i> ชื่อผู้ใช้</label>
              <input type="text" name="username" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Username" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-lock-fill me-1 text-success"></i> รหัสผ่าน</label>
              <input type="password" name="password" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="Password" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-envelope-fill me-1 text-success"></i> อีเมล</label>
              <input type="email" name="email" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="example@email.com">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-telephone-fill me-1 text-success"></i> เบอร์โทร</label>
              <input type="text" name="phone" class="form-control form-control-lg rounded-3 shadow-sm" placeholder="08xxxxxxxx">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold"><i class="bi bi-person-gear me-1 text-success"></i> สิทธิ์การใช้งาน</label>
              <select name="role" class="form-select form-select-lg rounded-3 shadow-sm">
                <option value="Staff">Staff</option>
                <option value="Manager">Manager</option>
              </select>
            </div>
            <div class="col-md-12">
              <label class="form-label fw-semibold"><i class="bi bi-image me-1 text-success"></i> รูปโปรไฟล์</label>
              <input type="file" name="profile_image" id="profileInput" class="form-control form-control-lg rounded-3 shadow-sm">
              <div class="text-center mt-3">
                <img id="profilePreview" src="https://cdn-icons-png.flaticon.com/512/847/847969.png" 
                     width="100" class="rounded-circle shadow-sm border border-2 border-success-subtle" alt="preview">
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer bg-light rounded-bottom-3">
          <button type="submit" name="add_user" class="btn btn-success btn-lg px-4 rounded-3 shadow-sm">
            <i class="bi bi-check-circle me-1"></i> บันทึก
          </button>
          <button type="button" class="btn btn-secondary btn-lg px-4 rounded-3" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> ยกเลิก
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
