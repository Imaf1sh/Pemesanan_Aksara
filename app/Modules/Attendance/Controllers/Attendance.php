<?php

namespace App\Modules\Attendance\Controllers;

use App\Controllers\BaseController;
use App\Modules\Attendance\Models\AttendanceModel;
use App\Modules\Auth\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Attendance extends BaseController
{
    use ResponseTrait;

    public function submit()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->failUnauthorized('Silakan login terlebih dahulu.');
        }

        $userId = $session->get('userId');
        $workerName = $this->request->getPost('worker_name');
        
        if (!empty($workerName)) {
            $userModel = new UserModel();
            $workerName = trim($workerName);
            $user = $userModel->where('name', $workerName)->first();
            if (!$user) {
                // Generate a unique username from name
                $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $workerName));
                if (empty($baseUsername)) {
                    $baseUsername = 'user';
                }
                $username = $baseUsername;
                $counter = 1;
                while ($userModel->where('username', $username)->first()) {
                    $username = $baseUsername . $counter;
                    $counter++;
                }
                
                $userId = $userModel->insert([
                    'username' => $username,
                    'name'     => $workerName,
                    'password' => password_hash('1234', PASSWORD_DEFAULT),
                    'role'     => 'kasir'
                ]);
            } else {
                $userId = $user['id'];
            }
        }

        $type = $this->request->getPost('type'); // 'Masuk' or 'Keluar'
        $photoBase64 = $this->request->getPost('photo'); // base64 string
        
        if (empty($type) || empty($photoBase64)) {
            return $this->fail('Tipe absensi dan foto wajib diisi.');
        }

        // Validate type
        if (!in_array($type, ['Masuk', 'Keluar'])) {
            return $this->fail('Tipe absensi tidak valid.');
        }

        // Process base64 photo
        // Format should be like: data:image/jpeg;base64,..... or data:image/png;base64,.....
        if (preg_match('/^data:image\/(\w+);base64,/', $photoBase64, $typeMatch)) {
            $imageType = strtolower($typeMatch[1]); // e.g., jpg, jpeg, png
            if (!in_array($imageType, ['jpg', 'jpeg', 'png'])) {
                return $this->fail('Format gambar tidak valid. Hanya JPG/PNG.');
            }

            $photoBase64 = substr($photoBase64, strpos($photoBase64, ',') + 1);
            $decodedImage = base64_decode($photoBase64);
            if ($decodedImage === false) {
                return $this->fail('Gagal mendekode gambar.');
            }

            // Create target directory if it doesn't exist
            $uploadDir = FCPATH . 'uploads/attendance/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $filename = 'att_' . $userId . '_' . time() . '.' . $imageType;
            $filePath = $uploadDir . $filename;

            if (file_put_contents($filePath, $decodedImage) === false) {
                return $this->failServerError('Gagal menyimpan gambar di server.');
            }

            $dbPath = 'uploads/attendance/' . $filename;
        } else {
            return $this->fail('Format data foto tidak valid.');
        }

        // Insert into database
        $attendanceModel = new AttendanceModel();
        
        // Double check: Has the user already clocked in/out for this type today?
        $today = date('Y-m-d');
        $existing = $attendanceModel->where([
            'user_id' => $userId,
            'type'    => $type,
            'date'    => $today
        ])->first();

        if ($existing) {
            return $this->failResourceExists('Anda sudah melakukan absensi ' . $type . ' hari ini.');
        }

        $attendanceData = [
            'user_id' => $userId,
            'type'    => $type,
            'date'    => $today,
            'time'    => date('H:i:s'),
            'photo'   => $dbPath
        ];

        $attendanceModel->insert($attendanceData);

        return $this->respond([
            'success' => true,
            'message' => 'Absensi ' . $type . ' berhasil disimpan!',
            'data'    => $attendanceData
        ]);
    }

    public function history()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->failUnauthorized('Silakan login terlebih dahulu.');
        }

        $attendanceModel = new AttendanceModel();
        
        // Get history for today with user name
        $today = date('Y-m-d');
        $history = $attendanceModel->select('attendance.*, users.name as user_name')
                                   ->join('users', 'users.id = attendance.user_id')
                                   ->where('attendance.date', $today)
                                   ->orderBy('attendance.time', 'DESC')
                                   ->findAll();

        return $this->respond([
            'success' => true,
            'history' => $history
        ]);
    }
}
