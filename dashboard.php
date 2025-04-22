<?php
session_start();
require_once '../config.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Get voting statistics
$total_voters = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM voters"))['count'];
$voted_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as count FROM voters WHERE has_voted = 1"))['count'];
$candidates_results = mysqli_query($conn, "
    SELECT c.name, c.party, COUNT(v.id) as votes
    FROM candidates c
    LEFT JOIN votes v ON c.id = v.candidate_id
    GROUP BY c.id
    ORDER BY votes DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Online Voting System</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h2>Admin Dashboard</h2>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
        
        <div class="stats-container">
            <div class="stat-box">
                <h3>Total Voters</h3>
                <p><?php echo $total_voters; ?></p>
            </div>
            <div class="stat-box">
                <h3>Votes Cast</h3>
                <p><?php echo $voted_count; ?></p>
            </div>
            <div class="stat-box">
                <h3>Turnout</h3>
                <p><?php echo round(($voted_count / $total_voters) * 100, 1); ?>%</p>
            </div>
        </div>
        
        <div class="results-container">
            <h3>Election Results</h3>
            <canvas id="resultsChart"></canvas>
            
            <table class="results-table">
                <thead>
                    <tr>
                        <th>Candidate</th>
                        <th>Party</th>
                        <th>Votes</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $labels = [];
                    $data = [];
                    while($row = mysqli_fetch_assoc($candidates_results)) {
                        $percentage = $voted_count > 0 ? round(($row['votes'] / $voted_count) * 100, 1) : 0;
                        $labels[] = $row['name'];
                        $data[] = $row['votes'];
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['party']); ?></td>
                            <td><?php echo $row['votes']; ?></td>
                            <td><?php echo $percentage; ?>%</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <script>
    const ctx = document.getElementById('resultsChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($labels); ?>,
            datasets: [{
                label: 'Votes',
                data: <?php echo json_encode($data); ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.8)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
    </script>
</body>
</html>