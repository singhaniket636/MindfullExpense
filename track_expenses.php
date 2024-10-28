<?php
include('header.php');
checkUser();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Track Expenses">
    <meta name="author" content="Your Name">
    <meta name="keywords" content="Expense Tracking, Financial Management">
    <title>Track Your Expenses</title>
    <link href="css/theme.css" rel="stylesheet" media="all">
    <link href="css/custom.css" rel="stylesheet" media="all">
</head>
<body>
<div class="container">
    <h1>Track Your Expenses</h1>
    <form method="post" action="save_expense.php">
        <div class="form-group">
            <label for="category_id">Category:</label>
            <?php echo getCategory(); // Function to get categories ?>
        </div>
        <div class="form-group">
            <label for="item">Item:</label>
            <input type="text" name="item" id="item" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="price">Price:</label>
            <input type="number" name="price" id="price" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="details">Details:</label>
            <textarea name="details" id="details" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label for="expense_date">Date:</label>
            <input type="date" name="expense_date" id="expense_date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>

    <!-- Pie Chart -->
    <div class="chart-container">
        <canvas id="expenseChart"></canvas>
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
                    backgroundColor: [
                        'rgba(74, 144, 226, 0.7)',
                        'rgba(80, 227, 194, 0.7)',
                        'rgba(245, 166, 35, 0.7)',
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                        'rgba(255, 159, 64, 0.7)'
                    ],
                    borderColor: [
                        'rgba(74, 144, 226, 1)',
                        'rgba(80, 227, 194, 1)',
                        'rgba(245, 166, 35, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
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
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += '₹' + context.parsed.toFixed(2);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    </script>
</div>
</body>
</html>
<?php include('footer.php'); ?>
