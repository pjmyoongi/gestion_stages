<?php
function set_flash($type, $message) {
    $_SESSION['flash'][$type][] = $message;
}

function show_flash() {
    if (!empty($_SESSION['flash'])) {
        foreach ($_SESSION['flash'] as $type => $messages) {
            foreach ($messages as $msg) {
                $class = $type === 'success' ? 'alert success' : 'alert error';
                echo "<div class='$class'>" . htmlspecialchars($msg) . "</div>";
            }
        }
        unset($_SESSION['flash']);
    }
}
