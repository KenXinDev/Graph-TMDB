<?php

require_once "app/function.php";

$App = new Controller();
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $movie = $App->GetMovieByID($id);
} else {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $movie['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        /* Global Styles */
        body {
            background: linear-gradient(to bottom, #1e3c72, #2a5298);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333 !important;
            padding: 0;
            margin: 0;
        }
        .container {
            width: 100%;
            max-width: 960px;
            margin: 50px auto;
            padding: 20px;
        }

        /* Movie Details Section */
        .movie-card {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .movie-card img {
            max-width: 100%;
            border-radius: 15px;
            transition: transform 0.3s ease-in-out;
        }
        .movie-card img:hover {
            transform: scale(1.05);
        }
        .movie-card h1 {
            font-size: 2.5rem;
            margin-top: 20px;
            color: #007bff;
        }
        .movie-card p {
            font-size: 1rem;
            line-height: 1.6;
            margin: 15px 0;
        }
        .user-score {
            font-weight: bold;
            color: #28a745;
        }

        /* Configuration Section */
        .setup-configuration {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-top: 40px;
        }
        .setup-configuration h3 {
            margin-bottom: 20px;
            font-size: 1.8rem;
            color: #007bff;
        }
        .setup-configuration .form-control {
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 1rem;
        }
        .setup-configuration .form-label {
            font-weight: bold;
        }

        /* Button Styles */
        .btn-back {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
        .btn-back:hover {
            color: white !important;
            background-color: #0056b3;
        }
        .btn-primary {
            background-color: #28a745;
            border: none;
            color: white;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: #218838;
        }

        /* Result Configuration Section */
        .result-configuration {
            margin-top: 30px;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .result-configuration textarea {
            width: 100%;
            height: 150px;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            resize: none;
        }
        .result-configuration button {
            margin-top: 15px;
            background-color: #007bff;
            border: none;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
        }
        .result-configuration button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="movie-card">
            <img src="<?php echo $config['img_url'] . $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>">
            <h1><?php echo $movie['title']; ?></h1>
            <p><strong>Synopsis:</strong> <?php echo $movie['overview']; ?></p>
            <p><strong>Release Date:</strong> <?php echo DateNow($movie['release_date']); ?></p>
            <p><strong>User Score:</strong> <span class="user-score"><?php echo $movie['vote_average'] * 10; ?>%</span></p>
            <a href="index.php" class="btn-back">Back to Home</a>
        </div>

        <div class="setup-configuration">
            <h3>Configuration</h3>
            <form action="" method="post">
                <div class="mb-3">
                    <label for="url_landingPage" class="form-label">Landing Page URL:</label>
                    <input type="url" name="url_landingPage" id="url_landingPage" class="form-control" placeholder="https://domain.com/movie{id}" required>
                </div>
                <div class="mb-3">
                    <p class="requirements">
                        {url} untuk link landing page <br>
                        {id} : untuk id dari movie <br>
                        {title} : untuk judul film <br>
                        {overview} : untuk deskripsi film <br>
                        {release_date} : untuk tanggal rilis film <br>
                        {vote_average} : untuk skor user <br>
                    </p>
                    <label for="comments" class="form-label">Comments:</label>
                    <textarea name="comments" id="comments" class="form-control" rows="4" placeholder="Write your comments here..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Save Config</button>
            </form>
        </div>

        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $link = htmlspecialchars($_POST['url_landingPage']);
            $comments = htmlspecialchars($_POST['comments']);

            // Proses mengganti placeholder
            $replacements = [
                '{url}' => $link, // Contoh pengganti untuk url
                '{id}' => $movie['id'],
                '{title}' => $movie['title'],
                '{overview}' => $movie['overview'],
                '{release_date}' => DateNow($movie['release_date']),
                '{vote_average}' => $movie['vote_average'] * 10 . '%',
            ];

            // Ganti placeholder pada URL dan komentar
            $finalComments = str_replace(array_keys($replacements), array_values($replacements), $comments);

            echo '<div class="result-configuration">
                <textarea id="resultsText" name="results">' . $finalComments . '</textarea>
                <button type="button" id="copyButton">Copy</button>
            </div>';
        } ?>
    </div>

    <script>
        // Event listener untuk tombol copy
        document.getElementById('copyButton').addEventListener('click', function() {
            // Ambil teks dari textarea
            var textArea = document.getElementById('resultsText');
            textArea.select();
            document.execCommand('copy'); // Menyalin teks ke clipboard

            // Tampilkan SweetAlert
            Swal.fire({
                icon: 'success',
                title: 'Copied!',
                text: 'The configuration has been copied to the clipboard.',
                confirmButtonText: 'OK'
            });
        });
    </script>
</body>
</html>
