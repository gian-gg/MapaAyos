<?php
// This is the top right section, please use it, it's much more cleaner this way!!
if (!isset($user)) {
    $userID = $_SESSION['userID'];
    $user = findUserByID($userID);
}
$profileImg = !empty($user['profile_image']) ? '/MapaAyos/public/images/profiles/' . $user['profile_image'] : '/MapaAyos/public/img/default-profile.png';
?>

<div class="header">
    <h1><?= $pageTitle ?? 'Dashboard' ?></h1>
    <div class="user-info">
        <?php if ($user): ?>
            <p>Welcome, <?= htmlspecialchars($user['firstName']) . ' ' . htmlspecialchars($user['lastName']) ?></p>
            <img src="<?= htmlspecialchars($profileImg) ?>" alt="Profile" class="profile-img">
        <?php else: ?>
            <p>User not found.</p>
        <?php endif; ?>
    </div>
</div>