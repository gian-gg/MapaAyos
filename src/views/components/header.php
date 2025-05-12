<?php

function renderHeader($isAuthenticated, $hasProfilePic, $userID)
{
    echo "<div class='header'>";
    echo "<p>User ID: " . ($userID ? $userID : "Guest") . "</p>";
    echo "<img src='/MapaAyos/public/uploads/pfp/" . ($hasProfilePic ? $userID : 'default') . ".png' alt='Profile' class='profile-img'>";
    echo "</div>";
}
