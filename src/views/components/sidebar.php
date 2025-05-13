<?php

function logOutButton()
{
    return "
        <form method='POST'>
            <button type='submit' name='logout' class='logout-btn'>
                <i class='bi bi-box-arrow-right'></i>
                <span class='nav-text'>Logout</span>
            </button>
        </form>
    ";
}

function renderSideBar($role, $activePage, $isAuthenticated)
{

    echo "
        <aside class='sidebar'>
            <a href='/MapaAyos/'>
                <div class='branding'>
                    <img src='/MapaAyos/public/img/brand-logo.png' alt='MapaAyos' width='34' height='34' style='border-radius: 5px;'>
                    <div class='brand-title'>
                        <h1>MapaAyos</h1>
                        <p>nisi commodo laborum</p>
                    </div>
                </div>
            </a>

            <nav>
                <div>
                    <p class='nav-text'>General</p>";

    if ($role == "admin" || $role == "official") {
        echo "
            <a href='/MapaAyos/" . $role . "/dashboard' class='nav-item'>
                <i class='bi bi-terminal'></i>
                <span class='nav-text'>Dashboard</span>
            </a>
        ";
    }

    echo "
        <a href='/MapaAyos/mapa' class='nav-item'>
            <i class='bi bi-map'></i>
            <span class='nav-text'>Mapa</span>
        </a>
            <a href='/MapaAyos/baranggays' class='nav-item'>
            <i class='bi bi-pin-map'></i>
            <span class='nav-text'>Baranggays</span>
        </a>
    ";

    echo "</div><div class='nav-bottom'>";

    echo ($isAuthenticated ? "
            <a href='/MapaAyos/settings' class='nav-item no-style-button'>
                <i class='bi bi-gear'></i>
                <span class='nav-text'>Settings</span>
            </a>"
        : "");
    echo ($isAuthenticated ? logOutButton() : "");
    echo "</div></nav></aside>";
}
