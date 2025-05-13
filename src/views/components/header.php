<?php

function renderHeader($user)
{
    echo "<div class='header'>";
    echo "<p>Welcome, " . ($user ? "{$user['firstName']} {$user['lastName']}" : "Guest") . "!</p>";
    if ($user) {
        echo "<img src='/MapaAyos/public/uploads/pfp/" . ($user['hasProfilePic'] ? $user['id'] : 'default') . ".png' alt='Profile' class='profile-img'>";
    } else {
        echo "<div class=''><a class='signin-btn' href='/MapaAyos/signin'>Sign In</a>";
        echo "<a class='signup-btn' href='/MapaAyos/signup'>Sign Up</a></div>";
    }
    echo "</div>";
}
