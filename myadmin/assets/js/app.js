
// Sidebar toggle (mobile)
const sidebar = document.getElementById('sidebar');
document.getElementById('btnOpenSidebar')?.addEventListener('click', ()=> sidebar.classList.add('show'));
document.getElementById('btnCloseSidebar')?.addEventListener('click', ()=> sidebar.classList.remove('show'));

// Seed data (แทน API ชั่วคราว)
const patients = [
  {pet:'โบโบ้ 🐶', owner:'ปิ่น', service:'วัคซีนรวม', date:'2025-10-22', status:'เสร็จสิ้น'},
  {pet:'เหมียวจัง 🐱', owner:'เอิร์ธ', service:'อาบน้ำตัดขน', date:'2025-10-22', status:'กำลังดำเนินการ'},
  {pet:'ชิกกี้ 🐔', owner:'จูน', service:'ตรวจสุขภาพ', date:'2025-10-22', status:'รอคิว'},
  {pet:'แบมบู 🐰', owner:'เปรม', service:'ทำหมัน', date:'2025-10-21', status:'นัดหมายแล้ว'},
  {pet:'ปุกปุย 🐹', owner:'แจน', service:'วัคซีนพิษสุนัขบ้า', date:'2025-10-21', status:'เสร็จสิ้น'},
  {pet:'คุมะ 🐻', owner:'บอม', service:'เอ็กซเรย์', date:'2025-10-21', status:'ยกเลิก'},
];

function statusBadge(s){
  const map = {
    'เสร็จสิ้น':'success',
    'กำลังดำเนินการ':'primary',
    'รอคิว':'secondary',
    'นัดหมายแล้ว':'warning',
    'ยกเลิก':'danger'
  };
  const cls = map[s] || 'secondary';
  return `<span class="badge text-bg-${cls}">${s}</span>`;
}

function renderTable(){
  const tbody = document.querySelector('#tblPatients tbody');
  tbody.innerHTML = patients.map(p => `
    <tr>
      <td>${p.pet}</td>
      <td>${p.owner}</td>
      <td>${p.service}</td>
      <td>${p.date}</td>
      <td>${statusBadge(p.status)}</td>
      <td class="text-end">
        <button class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-eye"></i></button>
        <button class="btn btn-sm btn-outline-primary me-1"><i class="bi bi-pencil"></i></button>
        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
      </td>
    </tr>
  `).join('');
}
renderTable();

// Chart —จำนวนสัตว์เลี้ยงวันนี้ (เส้น 7 วัน)
const ctx = document.getElementById('todayChart');
if (ctx){
  const data = [12, 18, 22, 15, 30, 27, 38];
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: ['จ.', 'อ.', 'พ.', 'พฤ.', 'ศ.', 'ส.', 'อา.'],
      datasets: [{
        label: 'จำนวน (เคส)',
        data,
        tension: .35,
        fill: true,
        borderColor: '#198754',
        backgroundColor: 'rgba(25,135,84,.12)',
        pointRadius: 3
      }]
    },
    options: {
      plugins: { legend: { display:false } },
      scales: {
        y: { beginAtZero:true, ticks:{ stepSize:10 } }
      }
    }
  });
}

// ตัวอย่างโค้ดเชื่อมต่อ API/ฐานข้อมูล (pseudo)
/*
fetch('/api/patients')
  .then(r => r.json())
  .then(rows => { patients.splice(0, patients.length, ...rows); renderTable(); });
*/
