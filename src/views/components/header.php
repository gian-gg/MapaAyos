<?php

function renderHeader($user)
{
    echo "<div class='header'>";
    echo "<p>Welcome, " . ($user ? "{$user['firstName']} {$user['lastName']}" : "Guest") . "!</p>";
    if ($user) {
        echo "<img src='/MapaAyos/public/uploads/pfp/" . ($user['hasProfilePic'] ? $user['id'] : 'default') . ".png' alt='Profile' class='header-profile-img'>";
    } else {
        echo "<div class='btn-group'><a style='display: flex; width: 99px; height: 32px; padding: 8px 16px; justify-content: center; align-items: center; gap: 5px; border-radius: 6px; border: 1px solid transparent; background: none; text-decoration: none; color: var(--text-primary); font-size: 14px; font-weight: 400; transition: all 0.2s ease;' onmouseover='this.style.textDecoration=\"underline\"' onmouseout='this.style.textDecoration=\"none\"' href='/MapaAyos/signin'>Sign In</a>";
        echo "<a style='display: flex; width: 99px; height: 32px; padding: 8px 16px; justify-content: center; align-items: center; gap: 5px; border-radius: 6px; border: 1px solid var(--outline); background: url(\"/MapaAyos/public/img/button.png\") lightgray 50% / cover no-repeat; text-decoration: none; color: var(--text-secondary); font-size: 14px; font-weight: 800;' href='/MapaAyos/signup'>Sign Up</a></div>";
    }
    echo "</div>";
}
