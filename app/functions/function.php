<?php
function view($view, $data = [])
{
    extract($data);

    $base = __DIR__ . "/../../resources/views/" . $view;

    $phpPath = $base . ".php";
    $htmlPath = $base . ".html";

    if (file_exists($phpPath)) {
        require $phpPath;
        return;
    }

    if (file_exists($htmlPath)) {
        readfile($htmlPath);
        return;
    }

    die("View {$view} not found");
}