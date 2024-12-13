<?php

require_once "app/function.php";
$App = new Controller();
$page = $_GET['page'] ?? $_GET['page'] = 1;

if (isset($_GET['query']) && $_GET['query']) {
    $query = $_GET['query'];
    $data = $App->SearchMovies(urlencode($query), $page);
    $movie = $data['data'];
    $total_pages = $data['total_pages'];
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="<?php echo $config['author'] ?>">
    <meta name="description" content="this is tools graph movie details with API tmdb">
    <meta name="keywords" content="tmdb api, movie, streaming">
    <title>Tools Graph</title>
</head>

<style>
    /* Reset CSS */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        background: linear-gradient(to bottom, #1e3c72, #2a5298);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color: #fff;
        line-height: 1.6;
        padding: 20px;
    }

    a {
        text-decoration: none !important;
    }

    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.37);
        text-align: center;
    }

    .container img {
        max-width: 150px;
        margin-bottom: 20px;
        animation: fadeIn 1s ease-in-out;
    }

    .container h2 {
        margin-bottom: 20px;
        font-size: 2rem;
        color: #f9f9f9;
        text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
    }

    .container form {
        display: flex;
        gap: 10px;
        width: 100%;
        max-width: 600px;
        margin-top: 20px;
    }

    .container form input[type="text"] {
        flex: 1;
        padding: 15px;
        border-radius: 30px;
        border: 1px solid #ccc;
        outline: none;
        transition: border-color 0.3s ease;
    }

    .container form input[type="text"]:focus {
        border-color: #2a5298;
    }

    .container form button {
        padding: 15px 25px;
        border: none;
        border-radius: 30px;
        background: linear-gradient(45deg, #ff6a00, #ee0979);
        color: #fff;
        font-weight: bold;
        cursor: pointer;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .container form button:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(255, 105, 135, 0.5);
    }

    .result {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); /* Menyesuaikan jumlah kolom secara otomatis */
        gap: 20px;
        margin-top: 30px;
        justify-items: center; /* Agar card berada di tengah */
    }

    .card {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 15px;
        backdrop-filter: blur(8px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        width: 280px;
        color: #fff;
        gap: 10px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    }

    .card img {
        width: 100%;
        height: 300px;
        /* Ukuran gambar */
        object-fit: cover;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .card h4 {
        font-size: 1.25rem;
        /* Ukuran font lebih kecil */
        margin: 10px;
        text-align: center;
    }

    .card p {
        font-size: 0.9rem;
        margin: 0 15px 15px;
        text-align: justify;
        color: #dcdcdc;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsif untuk layar kecil, termasuk iPhone 11 Pro Max */
    @media (max-width: 768px) {
        .result {

            grid-template-columns: repeat(2, 1fr); /* 2 kolom pada layar lebih kecil */
        }
    }

    @media (max-width: 480px) {
        .result {
            grid-template-columns: repeat(2, 1fr); /* 1 kolom pada layar sangat kecil */
        }
        .result .card img {
            width: 100%;
            height: 200px; /* Ukuran gambar pada layar sangat kecil */
            object-fit: cover;
        }

        .result .card {
            width: 100%;
            height: 250px; /* Ukuran card pada layar sangat kecil */
        }

        .pagination {
            position: fixed;
            padding: 10px;
            backdrop-filter: blur(8px);
        }

        .pagination .prev, .pagination .next {
            width: 100% !important;
            padding: 20px 50px;
        }
    }

    /* Pagination */
    .pagination {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        z-index: 1000;
    }

    .pagination a {
        text-decoration: none;
        color: #fff;
        padding: 8px 12px;
        font-size: 0.9rem;
        border-radius: 5px;
        background: linear-gradient(45deg, #ee0979, #ff6a00);
        transition: background 0.3s ease, transform 0.3s ease;
    }

    .pagination a:hover {
        background: linear-gradient(45deg, #ff6a00, #ee0979);
        transform: scale(1.1);
    }

    .pagination a.active {
        background: #fff;
        color: #2a5298;
        font-weight: bold;
        pointer-events: none;
    }

    .pagination .prev,
    .pagination .next {
        font-weight: bold;
        background: linear-gradient(45deg, #ff6a00, #ee0979);
        padding: 8px 16px;
        font-size: 0.9rem;
        border-radius: 5px;
    }

    .pagination .prev:hover,
    .pagination .next:hover {
        background: linear-gradient(45deg, #ff6a00, #ee0979);
        transform: scale(1.1);
    }
</style>

<body>
    <div class="container">
        <img src="img/Youtube_logo.png" alt="Logo tools disini ya ngab" />
        <h2>Graph Tools TMDB API</h2>
        <form action="" method="get">
            <input type="text" name="query" placeholder="Search movie...">
            <button type="submit">Search</button>
        </form>
    </div>

    <div class="result">
        <?php if (isset($query) && $query): ?>
            <?php foreach ($movie as $item): ?>
                <a href="movie.php?id=<?php echo $item['id']; ?>" target="_blank" rel="noopener noreferrer"><div class="card">
                    <img src="https://image.tmdb.org/t/p/w500<?php echo $item['poster_path'] ?>" alt="<?php echo $item['title'] ?>">
                    <h4><?php echo $item['title'] ?></h4>
                </div></a>
            <?php endforeach; ?>
            <div class="pagination">
                <?php
                // Tentukan jumlah tombol yang ingin ditampilkan
                $pagination_limit = 5;
                $half_limit = floor($pagination_limit / 2);

                // Tentukan halaman pertama dan terakhir yang ditampilkan
                $start_page = max(1, $page - $half_limit);
                $end_page = min($total_pages, $page + $half_limit);

                // Sesuaikan agar jumlah tombol pagination tetap 5
                if ($end_page - $start_page < $pagination_limit - 1) {
                    if ($start_page > 1) {
                        $start_page = max(1, $end_page - $pagination_limit + 1);
                    } else {
                        $end_page = min($total_pages, $start_page + $pagination_limit - 1);
                    }
                }

                // Tombol Previous
                if ($page > 1) {
                    echo '<a href="?page=' . ($page - 1) . '&query=' . $query . '" class="prev">&#8592; Previous</a>';
                }
                ?>

                <?php
                // Tombol halaman
                for ($i = $start_page; $i <= $end_page; $i++) {
                    $active = ($i == $page) ? 'active' : '';
                    echo '<a href="?page=' . $i . '&query=' . $query . '" class="' . $active . '">' . $i . '</a>';
                }
                ?>

                <?php
                // Tombol Next
                if ($page < $total_pages) {
                    echo '<a href="?page=' . ($page + 1) . '&query=' . $query . '" class="next">Next &#8594;</a>';
                }
                ?>
            </div>
        <?php endif; ?>
    </div>
</body>

</html>
