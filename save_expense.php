<?php
include('config.php');
include('functions.php');
checkUser();

if(isset($_POST['category_id']) && isset($_POST['item']) && isset($_POST['price']) && isset($_POST['details']) && isset($_POST['expense_date'])){
    $category_id = get_safe_value($_POST['category_id']);
    $item = get_safe_value($_POST['item']);
    $price = get_safe_value($_POST['price']);
    $details = get_safe_value($_POST['details']);
    $expense_date = get_safe_value($_POST['expense_date']);
    $added_on = date('Y-m-d h:i:s');
    $added_by = $_SESSION['UID'];

    $sql = "INSERT INTO expense (category_id, item, price, details, expense_date, added_on, added_by) VALUES ('$category_id', '$item', '$price', '$details', '$expense_date', '$added_on', '$added_by')";
    mysqli_query($con, $sql);
    redirect('track_expenses.php');
} else {
    echo "Please fill all fields.";
}
?>
