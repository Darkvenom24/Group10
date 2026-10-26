<?php
/**
 * php/db_explorer.php - Backward compatibility wrapper forwarding to phpmyadmin_api.php
 * Group 10 — Government MCA College, Maninagar (GMCA)
 */

// If legacy action names are used, translate them:
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'list_tables') {
        $_GET['action'] = 'db_structure';
    } elseif ($_GET['action'] === 'table_details') {
        $_GET['action'] = 'table_browse';
    } elseif ($_GET['action'] === 'run_query') {
        $_GET['action'] = 'run_sql';
    }
}

require_once __DIR__ . '/phpmyadmin_api.php';
