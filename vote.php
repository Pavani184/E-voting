<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['voter_id'])) {
    header("Location: index.php");
    exit();
}

$voter_id = $_SESSION['voter_id'];

// Check if already voted
$check_vote = mysqli_query($conn, "SELECT * FROM votes WHERE voter_id = '$voter_id'");
if (mysqli_num_rows($check_vote) > 0) {
    header("Location: thank_you.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidate_id = mysqli_real_escape_string($conn, $_POST['candidate']);
    
    mysqli_query($conn, "START TRANSACTION");
    
    $insert_vote = mysqli_query($conn, "INSERT INTO votes (voter_id, candidate_id) VALUES ('$voter_id', '$candidate_id')");
    $update_voter = mysqli_query($conn, "UPDATE voters SET has_voted = 1 WHERE voter_id = '$voter_id'");
    
    if ($insert_vote && $update_voter) {
        mysqli_query($conn, "COMMIT");
        header("Location: thank_you.php");
        exit();
    } else {
        mysqli_query($conn, "ROLLBACK");
        $error = "Error casting vote. Please try again.";
    }
}

$candidates = mysqli_query($conn, "SELECT * FROM candidates");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cast Your Vote</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="voting-box">
            <h2>Cast Your Vote</h2>
            <p>Welcome, <?php echo htmlspecialchars($_SESSION['voter_name']); ?></p>
            
            <form method="POST" action="">
                <div class="candidates-list">
                    <?php while($candidate = mysqli_fetch_assoc($candidates)) { ?>
                        <div class="candidate-card">
                            <img src="<?php echo htmlspecialchars($candidate['image_url']); ?>" alt="<?php echo htmlspecialchars($candidate['name']); ?>">
                            <h3><?php echo htmlspecialchars($candidate['name']); ?></h3>
                            <p><?php echo htmlspecialchars($candidate['party']); ?></p>
                            <input type="radio" name="candidate" value="<?php echo $candidate['id']; ?>" required>
                        </div>
                    <?php } ?>
                </div>
                <button type="submit">Submit Vote</button>
            </form>
            
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </div>
</body>
</html>