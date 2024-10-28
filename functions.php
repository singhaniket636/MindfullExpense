<?php
// Constants for role management
define('ROLE_ADMIN', 'Admin');
define('ROLE_USER', 'User');

// Debugging function to print and halt
function prx($data){
    echo '<pre>';
    print_r($data);
    die();
}

// Securely escape input data to prevent SQL injection and XSS
function get_safe_value($data){
    global $con;
    return $data ? htmlspecialchars(mysqli_real_escape_string($con, trim($data))) : '';
}

// PHP-based redirect function
function redirect($link){
    header("Location: $link");
    exit();
}

// Check if a user is logged in; if not, redirect to login page
function checkUser(){
    if (!isset($_SESSION['UID']) || $_SESSION['UID'] == '') {
        redirect('index.php');
    }
}

// Generate a dropdown for categories with an optional preselected category
function getCategory($category_id = '', $page = '') {
    global $con;
    $res = mysqli_query($con, "SELECT * FROM category ORDER BY name ASC");
    $requiredAttribute = $page !== 'reports' ? "required" : "";
    $html = sprintf('<select %s name="category_id" id="category_id" class="form-control">', $requiredAttribute);
    $html .= '<option value="">Select Category</option>';
    
    while ($row = mysqli_fetch_assoc($res)) {
        $selected = ($category_id > 0 && $category_id == $row['id']) ? 'selected' : '';
        $html .= sprintf('<option value="%s" %s>%s</option>', $row['id'], $selected, $row['name']);
    }
    
    $html .= '</select>';
    return $html;
}

// Calculate the total expense for the dashboard based on the selected timeframe
function getDashboardExpense($type){
    global $con;
    $today = date('Y-m-d');
    $from = $to = $sub_sql = "";

    switch ($type) {
        case 'today':
            $from = $to = $today;
            $sub_sql = "AND expense_date = '$today'";
            break;
        case 'yesterday':
            $yesterday = date('Y-m-d', strtotime('yesterday'));
            $from = $to = $yesterday;
            $sub_sql = "AND expense_date = '$yesterday'";
            break;
        case 'week':
        case 'month':
        case 'year':
            $from = date('Y-m-d', strtotime("-1 $type"));
            $to = $today;
            $sub_sql = "AND expense_date BETWEEN '$from' AND '$today'";
            break;
        default:
            $sub_sql = " ";
            break;
    }

    $query = "SELECT SUM(price) AS price FROM expense WHERE added_by = '".$_SESSION['UID']."' $sub_sql";
    $res = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($res);
    $p = $row['price'] > 0 ? $row['price'] : 0;
    $link = $p > 0 ? "&nbsp;<a href='dashboard_report.php?from=$from&to=$to' target='_blank' class='detail_link'>Details</a>" : "";

    return $p . $link;
}

// Ensure that only admin users can access certain areas
function adminArea(){
    if ($_SESSION['UROLE'] !== ROLE_ADMIN) {
        redirect('dashboard.php');
    }
}

// Ensure that only regular users can access certain areas
function userArea(){
    if ($_SESSION['UROLE'] !== ROLE_USER) {
        redirect('category.php');
    }
}
?>
