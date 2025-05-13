<?php
session_start();
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../controllers/AuthController.php';
require_once __DIR__ . '/../utils/UserSettingsManager.php';

require_once __DIR__ . '/components/sidebar.php';
require_once __DIR__ . '/components/header.php';
require_once __DIR__ . '/components/toasts.php';

requireSignIn();

$userID = $_SESSION['userID'];
$user = findUserByID($userID);
$role = isset($user['role']) ? $user['role'] : 'user';

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
            $newFileName = 'profile_' . time() . '.' . $fileExtension;
            $userFolder = $userID;

            // Create user-specific directory
            $uploadFileDir = __DIR__ . '/../../public/images/profiles/' . $userFolder;
            if (!file_exists($uploadFileDir)) {
                if (!mkdir($uploadFileDir, 0777, true)) {
                    error_log("Failed to create directory: " . $uploadFileDir);
                    $uploadError = 'Error creating directory for profile image.';
                    return;
                }
            }

            $dest_path = $uploadFileDir . '/' . $newFileName;
            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                global $pdo;
                $dbPath = $userFolder . '/' . $newFileName;
                $stmt = $pdo->prepare("UPDATE user_preferences SET profile_image = ? WHERE user_id = ?");
                $stmt->execute([$dbPath, $userID]);
                $user['profile_image'] = $dbPath;
            } else {
                error_log("Failed to move uploaded file to: " . $dest_path);
                $uploadError = 'Error saving the profile image.';
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
    // basically the same as the upload_image function but for cropped images   
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
    <title>MapaAyos - Settings</title>
    <link rel="shortcut icon" href="/MapaAyos/public/img/favicon.png" type="image/png">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" />

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <!-- Project CSS -->
    <link rel="stylesheet" href="/MapaAyos/public/css/root.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/main.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/dashboard.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/mapa-init.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/header.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/sidebar.css">
    <link rel="stylesheet" href="/MapaAyos/public/css/settings.css">
</head>

<body>
    <div class="dashboard">
        <?php
        renderSideBar(
            $user ? $user["role"] : null,
            "settings",
            isAuthenticated()
        )
        ?>
        </aside>
        <main class="main-content">
            <?php
            renderHeader(
                $user ?? null
            );
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