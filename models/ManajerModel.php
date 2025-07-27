<?php
class ManajerModel
{
    private $conn;

    public function __construct()
    {
        global $conn;
        $this->conn = $conn;
    }

    public function createManajer($data)
    {
        $this->conn->begin_transaction();

        try {
            $stmt = $this->conn->prepare("INSERT INTO users (username, password, nama_lengkap, email, role, store_id, status) VALUES (?, ?, ?, ?, ?, ?, 'aktif')");
            $stmt->bind_param(
                "sssssi",
                $data['username'],
                $data['password'],
                $data['nama_lengkap'],
                $data['email'],
                $data['role'],
                $data['store_id']
            );

            if (!$stmt->execute()) {
                throw new Exception("Gagal menambahkan user: " . $stmt->error);
            }

            $user_id = $this->conn->insert_id;

            $updateStore = $this->conn->prepare("UPDATE store SET manajer_store = ? WHERE id_store = ?");
            $updateStore->bind_param("si", $data['nama_lengkap'], $data['store_id']);

            if (!$updateStore->execute()) {
                throw new Exception("Gagal mengupdate manajer store: " . $updateStore->error);
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Manajer berhasil didaftarkan dan ditugaskan ke store',
                'user_id' => $user_id
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getAllManagers($search = '', $store_filter = '')
    {
        $query = "SELECT u.*, s.nama_store, s.alamat_store 
              FROM users u 
              LEFT JOIN store s ON u.store_id = s.id_store 
              WHERE u.role = 'manajer'";  // hanya role manajer

        $params = [];
        $types = '';

        if (!empty($search)) {
            $query .= " AND (u.username LIKE ? OR u.nama_lengkap LIKE ? OR u.email LIKE ?)";
            $searchParam = "%$search%";
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $types .= 'sss';
        }

        if (!empty($store_filter)) {
            $query .= " AND u.store_id = ?";
            $params[] = $store_filter;
            $types .= 'i';
        }

        $query .= " ORDER BY u.user_id DESC";

        if (!empty($params)) {
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
        } else {
            $result = $this->conn->query($query);
        }

        $managers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $managers[] = $row;
            }
        }

        return $managers;
    }


    public function getManagerById($id)
    {
        $stmt = $this->conn->prepare("SELECT u.*, s.nama_store FROM users u LEFT JOIN store s ON u.store_id = s.id_store WHERE u.user_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result ? $result->fetch_assoc() : null;
    }

    public function updateManajer($id, $data)
    {
        $this->conn->begin_transaction();

        try {
            $oldManager = $this->getManagerById($id);

            if (!empty($data['password'])) {
                $stmt = $this->conn->prepare("UPDATE users SET username = ?, password = ?, nama_lengkap = ?, email = ?, role = ?, store_id = ?, status = ? WHERE user_id = ?");
                $stmt->bind_param(
                    "sssssissi",
                    $data['username'],
                    $data['password'],
                    $data['nama_lengkap'],
                    $data['email'],
                    $data['role'],
                    $data['store_id'],
                    $data['status'],
                    $id
                );
            } else {
                $stmt = $this->conn->prepare("UPDATE users SET username = ?, nama_lengkap = ?, email = ?, role = ?, store_id = ?, status = ? WHERE user_id = ?");
                $stmt->bind_param(
                    "ssssssi",
                    $data['username'],
                    $data['nama_lengkap'],
                    $data['email'],
                    $data['role'],
                    $data['store_id'],
                    $data['status'],
                    $id
                );
            }

            if (!$stmt->execute()) {
                throw new Exception("Gagal mengupdate user: " . $stmt->error);
            }

            if ($oldManager['store_id'] != $data['store_id']) {
                if ($oldManager['store_id']) {
                    $clearOldStore = $this->conn->prepare("UPDATE store SET manajer_store = NULL WHERE id_store = ?");
                    $clearOldStore->bind_param("i", $oldManager['store_id']);
                    $clearOldStore->execute();
                }

                if ($data['store_id']) {
                    $updateNewStore = $this->conn->prepare("UPDATE store SET manajer_store = ? WHERE id_store = ?");
                    $updateNewStore->bind_param("si", $data['nama_lengkap'], $data['store_id']);
                    if (!$updateNewStore->execute()) {
                        throw new Exception("Gagal mengupdate manajer store: " . $updateNewStore->error);
                    }
                }
            } else if ($oldManager['nama_lengkap'] != $data['nama_lengkap'] && $data['store_id']) {
                $updateStoreName = $this->conn->prepare("UPDATE store SET manajer_store = ? WHERE id_store = ?");
                $updateStoreName->bind_param("si", $data['nama_lengkap'], $data['store_id']);
                $updateStoreName->execute();
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Data manajer berhasil diupdate'
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteManajer($id)
    {
        $this->conn->begin_transaction();

        try {
            $manager = $this->getManagerById($id);
            if (!$manager) {
                throw new Exception("Manajer tidak ditemukan");
            }

            $checkCustomer = $this->conn->prepare("SELECT COUNT(*) as count FROM customers WHERE user_id = ?");
            $checkCustomer->bind_param("i", $id);
            $checkCustomer->execute();
            $customerResult = $checkCustomer->get_result()->fetch_assoc();

            if ($customerResult['count'] > 0) {
                throw new Exception("Manajer tidak dapat dihapus karena terkait dengan data customer");
            }

            if ($manager['store_id']) {
                $clearStore = $this->conn->prepare("UPDATE store SET manajer_store = NULL WHERE id_store = ?");
                $clearStore->bind_param("i", $manager['store_id']);
                $clearStore->execute();
            }

            $stmt = $this->conn->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $id);

            if (!$stmt->execute()) {
                throw new Exception("Gagal menghapus manajer: " . $stmt->error);
            }

            $this->conn->commit();

            return [
                'success' => true,
                'message' => 'Manajer berhasil dihapus'
            ];
        } catch (Exception $e) {
            $this->conn->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function toggleStatus($id)
    {
        $manager = $this->getManagerById($id);
        if (!$manager) {
            return [
                'success' => false,
                'message' => 'Manajer tidak ditemukan'
            ];
        }

        $newStatus = $manager['status'] == 'aktif' ? 'tidak_aktif' : 'aktif';

        $stmt = $this->conn->prepare("UPDATE users SET status = ? WHERE user_id = ?");
        $stmt->bind_param("si", $newStatus, $id);

        if ($stmt->execute()) {
            return [
                'success' => true,
                'message' => 'Status manajer berhasil diubah menjadi ' . $newStatus
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Gagal mengubah status: ' . $stmt->error
            ];
        }
    }

    public function validateRegistrasi($data)
    {
        $errors = [];

        if (empty($data['store_id'])) {
            $errors[] = "Store harus dipilih";
        }

        if (empty($data['username'])) {
            $errors[] = "Username tidak boleh kosong";
        } else {
            if ($this->isUsernameExists($data['username'])) {
                $errors[] = "Username sudah digunakan";
            }
        }

        if (empty($data['password'])) {
            $errors[] = "Password tidak boleh kosong";
        } else if (strlen($data['password']) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = "Konfirmasi password tidak sama";
        }

        if (empty($data['nama_lengkap'])) {
            $errors[] = "Nama lengkap tidak boleh kosong";
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }

        return $errors;
    }

    public function validateEdit($data, $id)
    {
        $errors = [];

        if (empty($data['store_id'])) {
            $errors[] = "Store harus dipilih";
        }

        if (empty($data['username'])) {
            $errors[] = "Username tidak boleh kosong";
        } else {
            if ($this->isUsernameExists($data['username'], $id)) {
                $errors[] = "Username sudah digunakan";
            }
        }

        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors[] = "Password minimal 6 karakter";
        }

        if (empty($data['nama_lengkap'])) {
            $errors[] = "Nama lengkap tidak boleh kosong";
        }

        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Format email tidak valid";
        }

        if (!empty($data['role']) && !in_array($data['role'], ['admin', 'member', 'manajer'])) {
            $errors[] = "Role tidak valid";
        }

        if (!in_array($data['status'], ['aktif', 'tidak_aktif'])) {
            $errors[] = "Status tidak valid";
        }

        return $errors;
    }

    public function isUsernameExists($username, $exclude_id = null)
    {
        if ($exclude_id) {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = ? AND user_id != ?");
            $stmt->bind_param("si", $username, $exclude_id);
        } else {
            $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        return $result['count'] > 0;
    }

    public function getTotalManagers()
    {
        $query = "SELECT COUNT(*) as total FROM users WHERE role IN ('admin', 'member', 'manajer')";
        $result = $this->conn->query($query);
        return $result ? $result->fetch_assoc()['total'] : 0;
    }

    public function getManagersByStore($store_id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE store_id = ? AND role IN ('admin', 'member', 'manajer') ORDER BY nama_lengkap");
        $stmt->bind_param("i", $store_id);
        $stmt->execute();
        $result = $stmt->get_result();

        $managers = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $managers[] = $row;
            }
        }

        return $managers;
    }
}
