<?php
session_start();
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../controllers/AuthController.php';
require_once __DIR__ . '/../../models/UserSettingsManager.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);
$role = isset($user['role']) ? $user['role'] : 'user';
redirectIfNotAllowed($role, "admin");

// Handle profile image upload
$uploadError = '';
if (isset($_POST['upload_image'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileSize = $_FILES['profile_image']['size'];
        $fileType = $_FILES['profile_image']['type'];
        $fileNameCmps = explode('.', $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = 'admin_' . $userID . '_' . time() . '.' . $fileExtension;
            $uploadFileDir = __DIR__ . '/../../public/images/profiles/';
            if (!is_dir($uploadFileDir)) mkdir($uploadFileDir, 0777, true);
            $dest_path = $uploadFileDir . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                // Save to DB
                global $pdo;
                $stmt = $pdo->prepare("UPDATE user_preferences SET profile_image = ? WHERE user_id = ?");
                $stmt->execute([$newFileName, $userID]);
                $user['profile_image'] = $newFileName;
            } else {
                $uploadError = 'Error moving the uploaded file.';
            }
        } else {
            $uploadError = 'Invalid file type. Only JPG, PNG, GIF allowed.';
        }
    } else {
        $uploadError = 'No file uploaded or upload error.';
    }
}

// Handle password change
$pwChangeMsg = '';
if (isset($_POST['change_password'])) {
    $old = trim($_POST['old_password'] ?? '');
    $new = trim($_POST['new_password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');

    // Validate password lengths
    if (strlen($new) < 8) {
        $pwChangeMsg = 'New password must be at least 8 characters.';
    } elseif (strlen($new) > 72) {
        $pwChangeMsg = 'New password must be less than 72 characters.';
    } elseif (!password_verify($old, $user['password'])) {
        $pwChangeMsg = 'Old password is incorrect.';
    } elseif ($new !== $confirm) {
        $pwChangeMsg = 'New passwords do not match.';
    } else {
        try {
            global $pdo;
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([password_hash($new, PASSWORD_DEFAULT), $userID]);
            $pwChangeMsg = 'Password updated successfully!';
        } catch (PDOException $e) {
            error_log("Error updating password: " . $e->getMessage());
            $pwChangeMsg = 'An error occurred while updating your password.';
        }
    }
}

// Handle info update (name/email)
$infoUpdateMsg = '';
if (isset($_POST['update_info'])) {
    $firstName = trim($_POST['update_firstName'] ?? '');
    $lastName = trim($_POST['update_lastName'] ?? '');
    $email = trim($_POST['update_email'] ?? '');
    $settingsManager = new UserSettingsManager($pdo);
    $infoUpdateMsg = $settingsManager->updateNameAndEmail($userID, $firstName, $lastName, $email);
    // Refresh user data if successful
    if ($infoUpdateMsg === 'Profile updated successfully!') {
        $user = findUserByID($userID);
    }
}

// Handle cropped profile image upload via base64 data from hidden input
if (!empty($_POST['cropped_image_data'])) {
    $data = $_POST['cropped_image_data'];
    if (preg_match('/^data:image\/(png|jpeg);base64,/', $data, $matches)) {
        $type = $matches[1];
        $data = substr($data, strpos($data, ',') + 1);
        $data = base64_decode($data);
        $fileName = 'profile_' . time() . '.png';
        $userFolder = $userID;
        $uploadFileDir = dirname(dirname(dirname(__DIR__))) . '/public/images/profiles/' . $userFolder . '/';

        // Create directory if it doesn't exist
        if (!file_exists($uploadFileDir)) {
            // 0777 = default permissions for directories
            if (!mkdir($uploadFileDir, 0777, true)) {
                $uploadError = 'Error creating directory for profile image.';
                return;
            }
        }

        $dest_path = $uploadFileDir . $fileName;
        if (file_put_contents($dest_path, $data)) {
            global $pdo;
            $dbPath = $userFolder . '/' . $fileName;
            $stmt = $pdo->prepare("UPDATE user_preferences SET profile_image = ? WHERE user_id = ?");
            $stmt->execute([$dbPath, $userID]);
            $user['profile_image'] = $dbPath;
        } else {
            $uploadError = 'Error saving the profile image.';
        }
    }
}

if (isset($_POST['logout'])) {
    handleSignOut();
}

$profileImg = !empty($user['profile_image']) ? '/MapaAyos/public/images/profiles/' . $user['profile_image'] : '/MapaAyos/public/img/default-profile.png';

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MapaAyos - Admin Settings</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/settings.css">
</head>

<body>
    <div class="dashboard">
        <aside class="sidebar">
            <a href="/MapaAyos/index.php">
                <div class="branding">
                    <img src="/MapaAyos/public/img/brand-logo.png" alt="MapaAyos" width="34" height="34" style="border-radius: 5px;">
                    <div class="brand-title">
                        <h1>MapaAyos</h1>
                        <p>nisi commodo laborum</p>
                    </div>
                </div>
            </a>

            <nav>
                <div>
                    <p class="nav-text">General</p>
                    <a href="/MapaAyos/admin/dashboard" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/admin/dashboard') echo ' active'; ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                            <path d="M5 1H1.66667C1.29848 1 1 1.29848 1 1.66667V6.33333C1 6.70152 1.29848 7 1.66667 7H5C5.36819 7 5.66667 6.70152 5.66667 6.33333V1.66667C5.66667 1.29848 5.36819 1 5 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 1H9C8.63181 1 8.33333 1.29848 8.33333 1.66667V3.66667C8.33333 4.03486 8.63181 4.33333 9 4.33333H12.3333C12.7015 4.33333 13 4.03486 13 3.66667V1.66667C13 1.29848 12.7015 1 12.3333 1Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12.3333 7H9C8.63181 7 8.33333 7.29848 8.33333 7.66667V12.3333C8.33333 12.7015 8.63181 13 9 13H12.3333C12.7015 13 13 12.7015 13 12.3333V7.66667C13 7.29848 12.7015 7 12.3333 7Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M5 9.66667H1.66667C1.29848 9.66667 1 9.96514 1 10.3333V12.3333C1 12.7015 1.29848 13 1.66667 13H5C5.36819 13 5.66667 12.7015 5.66667 12.3333V10.3333C5.66667 9.96514 5.36819 9.66667 5 9.66667Z" stroke="#4F5051" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="nav-text">Dashboard</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/admin/dashboard') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                </div>
                <div class="nav-bottom">
                    <a href="/MapaAyos/admin/settings" class="nav-item<?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/admin/settings') echo ' active'; ?>" style="font-size: 1.1rem;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"></path>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path>
                        </svg>
                        <span class="nav-text">Settings</span>
                        <?php if ($_SERVER['REQUEST_URI'] === '/MapaAyos/admin/settings') : ?>
                            <i class="bi bi-chevron-right"></i>
                        <?php endif; ?>
                    </a>
                    <div style="margin-top: 1rem;">
                        <form method="POST">
                            <button type="submit" name="logout" class="ma-btn">Log Out</button>
                        </form>
                    </div>
                </div>
            </nav>
        </aside>
        <main class="main-content">
            <?php
            $pageTitle = 'Admin Settings';
            require_once __DIR__ . '/../partials/_header.php';
            ?>
            <div class="settings-container">
                <div class="account-settings-label">Account Settings</div>
                <div class="settings-section">
                    <div class="settings-subtitle">Personal Information</div>
                    <hr class="section-divider">
                    <div class="profile-row">
                        <form method="POST" enctype="multipart/form-data" id="accountSettingsForm">
                            <div style="display:flex;align-items:center;gap:1.2rem;">
                                <img src="<?= htmlspecialchars($profileImg) ?>" class="profile-img" id="profileImgPreview" alt="Profile Image">
                                <button type="button" id="triggerProfileImage" class="btn-settings">Change</button>
                            </div>
                            <input type="file" id="profile_image_input" accept="image/*" style="display:none;">
                            <!-- Cropper Modal -->
                            <div class="modal fade" id="cropperModal" tabindex="-1" aria-labelledby="cropperModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="cropperModalLabel">Crop Profile Image</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div style="max-width:100%;max-height:350px;">
                                                <img id="cropperImage" style="max-width:100%;max-height:350px;">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="button" class="btn btn-primary" id="uploadCroppedImage">Crop</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="settings-form-row">
                                <div>
                                    <div class="settings-label">First Name</div>
                                    <input type="text" class="settings-input" name="update_firstName" id="update_firstName" value="<?= htmlspecialchars($user['firstName']) ?>" required>
                                </div>
                                <div>
                                    <div class="settings-label">Last Name</div>
                                    <input type="text" class="settings-input" name="update_lastName" id="update_lastName" value="<?= htmlspecialchars($user['lastName']) ?>" required>
                                </div>
                            </div>
                            <div class="settings-form-row">
                                <div>
                                    <div class="settings-label">Email</div>
                                    <input type="email" class="settings-input" name="update_email" id="update_email" value="<?= htmlspecialchars($user['email']) ?>" required>
                                </div>
                            </div>
                            <input type="hidden" name="cropped_image_data" id="cropped_image_data">
                            <div>
                                <button type="submit" name="update_info" class="btn-settings mt-2">Save</button>
                                <?php if (!empty($infoUpdateMsg)): ?><div class="text-success mt-2"><?= htmlspecialchars($infoUpdateMsg) ?></div><?php endif; ?>
                                <?php if ($uploadError): ?><div class="text-danger small mt-1"><?= htmlspecialchars($uploadError) ?></div><?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="settings-section">
                    <div class="settings-subtitle">Change Password</div>
                    <hr class="section-divider">
                    <form method="POST">
                        <div class="settings-form-row">
                            <div>
                                <div class="settings-label">Old Password</div>
                                <input type="password" name="old_password" class="settings-input" required>
                            </div>
                            <div>
                                <div class="settings-label">New Password</div>
                                <input type="password" name="new_password" class="settings-input" required>
                            </div>
                            <div>
                                <div class="settings-label">Confirm Password</div>
                                <input type="password" name="confirm_password" class="settings-input" required>
                            </div>
                        </div>
                        <div>
                            <button type="submit" name="change_password" class="btn-settings mt-2">Change Password</button>
                            <?php if ($pwChangeMsg): ?><span class="ms-3 text-<?= strpos($pwChangeMsg, 'success') !== false ? 'success' : 'danger' ?>"><?= htmlspecialchars($pwChangeMsg) ?></span><?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/MapaAyos/public/js/sidebar.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
    <script src="/MapaAyos/public/js/settings.js"></script>
</body>

</html>