<?php
include 'includes/db.php';

$sql = "SELECT * FROM Programmes";
$result = mysqli_query($conn, $sql);
$programs = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Course Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>

    <nav class="navbar navbar-expand-lg  bg-transparent">
        <div class="container-fluid">
            <a class="navbar-brand text-light" href="index.php">Student Course Hub</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto">
            
                        <a class="nav-link text-light " href="login2.php">Staff Login</a>
                        <a class="nav-link text-light " href="login.php">Admin Login</a>
                        <a class="nav-link text-light " href="withdraw-interest.php">Withdraw Interest</a>
                                    
                </div>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center mb-4 text-light" > DMU Available Programs</h1>

        <div class="row mb-4">
            <div class="col-md-6">
                <input type="text" id="search" class="form-control" placeholder="Search programs...">
            </div>
            <div class="col-md-6">
                <select id="filter" class="form-select">
                    <option value="all">All Levels</option>
                    <option value="undergraduate">Undergraduate</option>
                    <option value="postgraduate">Postgraduate</option>
                </select>
            </div>
        </div>

        <div class="row" id="program-list">
            <?php foreach ($programs as $program): ?>
                <div class="col-md-4 mb-4 program-card" data-level="<?php echo strtolower($program['LevelID'] == 1 ? 'undergraduate' : 'postgraduate'); ?>">
                    <div class="card h-100">
                        
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $program['ProgrammeName']; ?></h5>
                            <p class="card-text"><?php echo $program['Description']; ?></p>
                            <a href="program-details.php?id=<?php echo $program['ProgrammeID']; ?>" class="btn btn-success">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/scripts.js"></script>
    <script>

        document.getElementById('search').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const programCards = document.querySelectorAll('.program-card');

            programCards.forEach(card => {
                const programName = card.querySelector('.card-title').textContent.toLowerCase();
                if (programName.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        document.getElementById('filter').addEventListener('change', function() {
            const filterValue = this.value;
            const programCards = document.querySelectorAll('.program-card');

            programCards.forEach(card => {
                const programLevel = card.getAttribute('data-level');
                if (filterValue === 'all' || programLevel === filterValue) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>