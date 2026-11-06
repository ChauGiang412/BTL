<?php
// Chạy 1 lần sau khi import sql/schema.sql:  php seed.php
declare(strict_types=1);
require __DIR__ . '/app/bootstrap.php';

use App\Models\Clinic;
use App\Models\User;

$users = new User();
$clinics = new Clinic();

if ($users->findByEmail('admin@demo.vn')) {
    exit("Đã seed trước đó.\n");
}
$users->create(['role' => 'admin', 'email' => 'admin@demo.vn', 'password' => password_hash('admin123', PASSWORD_DEFAULT), 'full_name' => 'Quản trị viên']);
$users->create(['role' => 'client', 'email' => 'benhnhan@demo.vn', 'password' => password_hash('client123', PASSWORD_DEFAULT),
    'full_name' => 'Nguyễn Văn A', 'phone' => '0900000001', 'id_card' => '048123456789']);

$demo = [
    ['bs.minh@demo.vn', 'BS. Trần Minh', 'Phòng khám Tai Mũi Họng Minh Tâm', 'Hải Châu', '12 Nguyễn Văn Linh', [1, 2]],
    ['bs.lan@demo.vn',  'BS. Lê Thu Lan', 'Phòng khám Nhi Lan Anh', 'Thanh Khê', '45 Hà Huy Tập', [4, 8]],
];
foreach ($demo as [$email, $doctor, $name, $district, $addr, $specs]) {
    $id = $clinics->createWithDoctor(
        ['name' => $name, 'description' => 'Phòng khám uy tín, trang thiết bị hiện đại.', 'doctor_bio' => $doctor . ' – hơn 10 năm kinh nghiệm.',
         'address' => $addr, 'district' => $district, 'phone' => '0236 123 456', 'email' => $email],
        ['email' => $email, 'password' => password_hash('doctor123', PASSWORD_DEFAULT), 'full_name' => $doctor]
    );
    $clinics->syncSpecialties($id, $specs);
    $rows = [];
    for ($d = 1; $d <= 6; $d++) {
        $rows[$d] = ['open' => '08:00', 'close' => '17:00'];
    }
    $clinics->saveHours($id, $rows, 30, 3);
}
echo "Xong. Tài khoản demo:\n admin@demo.vn / admin123\n bs.minh@demo.vn / doctor123\n benhnhan@demo.vn / client123\n";