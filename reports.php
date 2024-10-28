<?php
include('header.php');
checkUser();
userArea();

$labels = [];
$data = [];
$cat_id = '';
$sub_sql = '';
$from = '';
$to = '';

if (isset($_GET['category_id']) && $_GET['category_id'] > 0) {
    $cat_id = get_safe_value($_GET['category_id']);
    $sub_sql = " and category.id=$cat_id ";
}

if (isset($_GET['from'])) {
    $from = get_safe_value($_GET['from']);
}
if (isset($_GET['to'])) {
    $to = get_safe_value($_GET['to']);
}

if ($from !== '' && $to != '') {
    $sub_sql .= " and expense.expense_date between '$from' and '$to' ";
}

$res = mysqli_query($con, "SELECT SUM(expense.price) as price, category.name 
                           FROM expense, category 
                           WHERE expense.category_id=category.id 
                           AND expense.added_by='" . $_SESSION['UID'] . "' 
                           $sub_sql 
                           GROUP BY expense.category_id");

while ($row = mysqli_fetch_assoc($res)) {
    $labels[] = $row['name'];
    $data[] = $row['price'];
}
?>

<!-- Link to the custom CSS -->
<link rel="stylesheet" href="custom.css">

<!-- Header with improved styling -->
<h2 style="margin-top: 76px; text-align: center; font-family: 'Arial', sans-serif; color: #333;">Expense Reports</h2>

<!-- Form Section -->
<form type="get" class="form-inline" style="text-align: center; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1); max-width: 900px; margin: auto;">
    <label style="font-weight: bold;">From:</label>
    <input type="date" name="from" value="<?php echo $from ?>" max="<?php echo date('Y-m-d') ?>" onchange="set_to_date()" id="from_date" class="form-control" style="border-radius: 5px;">
    &nbsp;&nbsp;&nbsp;
    <label style="font-weight: bold;">To:</label>
    <input type="date" name="to" value="<?php echo $to ?>" max="<?php echo date('Y-m-d') ?>" id="to_date" class="form-control" style="border-radius: 5px;">
    <?php echo getCategory($cat_id, 'reports'); ?>
    <input type="submit" name="submit" value="Submit" class="btn btn-primary" style="margin-left: 20px;">
    <a href="reports.php" class="btn btn-secondary" style="margin-left: 10px;">Reset</a>
</form>

<!-- Table Section -->
<?php if (mysqli_num_rows($res) > 0) { ?>
    <div class="table-responsive" style="margin-top: 20px; max-width: 900px; margin: auto;">
        <table class="table table-striped table-bordered" style="box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); border-radius: 8px;">
            <thead style="background-color: #007bff; color: white;">
                <tr>
                    <th>Category</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $final_price = 0;
                mysqli_data_seek($res, 0); // Reset result pointer for table
                while ($row = mysqli_fetch_assoc($res)) {
                    $final_price += $row['price'];
                    ?>
                    <tr>
                        <td><?php echo $row['name'] ?></td>
                        <td><?php echo $row['price'] ?></td>
                    </tr>
                <?php } ?>
                <tr>
                    <th>Total</th>
                    <th><?php echo $final_price ?></th>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pie Chart Section -->
    <div style="width: 100%; max-width: 600px; margin: 40px auto;">
        <canvas id="expenseChart" style="box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.2); border-radius: 8px;"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('expenseChart').getContext('2d');
        var expenseChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    data: <?php echo json_encode($data); ?>,
                    backgroundColor: ['rgba(255, 99, 132, 0.7)', 'rgba(54, 162, 235, 0.7)', 'rgba(255, 206, 86, 0.7)', 'rgba(75, 192, 192, 0.7)', 'rgba(153, 102, 255, 0.7)', 'rgba(255, 159, 64, 0.7)'],
                    borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            color: '#333',
                            font: {
                                size: 14
                            }
                        }
                    }
                }
            }
        });
    </script>

<?php } else {
    echo "<b style='text-align: center; display: block; margin-top: 20px;'>No data found</b>";
}
include('footer.php');
?>
