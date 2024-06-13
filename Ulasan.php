<?php
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$dbname = "vapelab";

// Buat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Tangani data dari form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit_review'])) {
        // Tangani data dari form
        $product_title = isset($_POST['product_title']) ? $_POST['product_title'] : '';
        $rating = $_POST['rating'];
        $review = $_POST['review'];
        $description = isset($_POST['description']) ? $_POST['description'] : '';
        $media_path = ''; // Definisikan variabel untuk menyimpan path media (akan diisi oleh handleFileUpload())

        // Handle file upload
        if (isset($_FILES['media']) && $_FILES['media']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['media']['tmp_name'])) {
            $media_path = handleFileUpload($_FILES['media']); // Panggil fungsi handleFileUpload() untuk mengelola unggahan file
        }

        // SQL untuk memasukkan review baru ke dalam database
        $sql = "INSERT INTO reviews (product_title, rating, review, description, media_path) VALUES (?, ?, ?, ?, ?)";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sisss", $product_title, $rating, $review, $description, $media_path);
            $stmt->execute();
            $stmt->close();
            echo "<div class='alert alert-success' role='alert'>Review submitted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['delete_review'])) {
        // Tangani delete review
        $review_id = $_POST['review_id'];
        $sql = "DELETE FROM reviews WHERE id=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $review_id);
            $stmt->execute();
            $stmt->close();
            echo "<div class='alert alert-success' role='alert'>Review deleted successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['like_review'])) {
        // Tangani like review
        $review_id = $_POST['review_id'];
        $sql = "UPDATE reviews SET likes = likes + 1 WHERE id=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $review_id);
            $stmt->execute();
            $stmt->close();
            echo "<div class='alert alert-success' role='alert'>Review liked successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
        }
    } elseif (isset($_POST['reject_review'])) {
        // Tangani reject review
        $review_id = $_POST['review_id'];
        $sql = "UPDATE reviews SET rejects = rejects + 1 WHERE id=?";

        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $review_id);
            $stmt->execute();
            $stmt->close();
            echo "<div class='alert alert-success' role='alert'>Review rejected successfully!</div>";
        } else {
            echo "<div class='alert alert-danger' role='alert'>Error: " . $conn->error . "</div>";
        }
    }
}

// Fungsi untuk mengelola unggahan file
function handleFileUpload($file) {
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($file["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Cek ukuran file
    if ($file["size"] > 5000000) {
        echo "<div class='alert alert-danger' role='alert'>Sorry, your file is too large.</div>";
        $uploadOk = 0;
    }

    // Izinkan format file tertentu
    $allowedFormats = ["jpg", "png", "jpeg", "gif", "mp4", "avi"];
    if (!in_array($fileType, $allowedFormats)) {
        echo "<div class='alert alert-danger' role='alert'>Sorry, only JPG, JPEG, PNG, GIF, MP4, and AVI files are allowed.</div>";
        $uploadOk = 0;
    }

    // Periksa jika $uploadOk disetel ke 0 karena ada kesalahan
    if ($uploadOk == 0) {
        return null;
    } else {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return $target_file; // Kembalikan path file yang diunggah
        } else {
            echo "<div class='alert alert-danger' role='alert'>Sorry, there was an error uploading your file.</div>";
            return null;
        }
    }
}

// Fetch reviews from database
$sql = "SELECT * FROM reviews ORDER BY review_date DESC";
$result = $conn->query($sql);
$reviews = [];
$totalRating = 0;
$totalReviews = 0;
$starCounts = array_fill(1, 5, 0); // Array untuk menyimpan jumlah peringkat bintang

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $reviews[] = $row;
        $totalRating += $row['rating'];
        $totalReviews++;
        $starCounts[$row['rating']]++; // Tambahkan ke jumlah peringkat bintang yang sesuai
    }
}

$averageRating = $totalReviews ? $totalRating / $totalReviews : 0;

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>VapeLab Reviews</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .star-rating {
            font-size: 2em;
            cursor: pointer;
            display: inline-block;
        }
        .star-rating .fa-star {
            color: #d3d3d3;
            transition: color 0.3s;
        }
        .star-rating .fa-star.checked {
            color: #ffdd57;
        }
        .review-card {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.3s ease-out forwards;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .review-header {
            display: flex;
            align-items: center;
        }
        .reviewer-photo {
            border-radius: 50%;
            width: 50px;
            height: 50px;
            margin-right: 15px;
        }
        .reviewer-name {
            font-weight: bold;
        }
        .review-date {
            color: gray;
        }
        .review-rating {
            margin-bottom: 10px;
        }
        .btn-action {
            margin-right: 10px;
        }
        .rating-breakdown {
            margin-top: 20px;
        }
        .fa-star.checked {
    color: #ffdd57;
}

    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">VapeLab Reviews</h1>
        <h3 class="text-center">Average Rating: <?= round($averageRating, 1) ?> <span class="fa fa-star checked"></span></h3>

        <!-- Form to submit reviews -->
        <div class="card mt-4">
            <div class="card-body">
                <h5 class="card-title">Submit your review</h5>
                <form id="review-form" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="product_title">Product Title</label>
                        <input type="text" class="form-control" id="product_title" name="product_title" required>
                    </div>
                    <div class="form-group">
                        <label for="rating">Rating</label>
                        <div class="star-rating">
                            <span class="fa fa-star" data-rating="1"></span>
                            <span class="fa fa-star" data-rating="2"></span>
                            <span class="fa fa-star" data-rating="3"></span>
                            <span class="fa fa-star" data-rating="4"></span>
                            <span class="fa fa-star" data-rating="5"></span>
                            <input type="hidden" name="rating" class="rating-value" value="0">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="review">Review</label>
                        <textarea class="form-control" id="review" name="review" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <input type="text" class="form-control" id="description" name="description">
                    </div>
                    <div class="form-group">
                        <label for="media">Media (optional)</label>
                        <input type="file" class="form-control-file" id="media" name="media">
                    </div>
                    <button type="submit" name="submit_review" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>

        <!-- Display reviews -->
        <div class="mt-5">
            <h3>Reviews</h3>
            <?php foreach ($reviews as $review): ?>
                <div class="review-card">
                    <div class="review-header">
                        <img src="path/to/default/photo.jpg" alt="Reviewer Photo" class="reviewer-photo">
                        <div>
                            <div class="reviewer-name"><?= htmlspecialchars($review['product_title']) ?></div>
                            <div class="review-date"><?= htmlspecialchars($review['review_date']) ?></div>
                        </div>
                    </div>
                    <div class="review-rating">
    <?php for ($i = 1; $i <= 5; $i++): ?>
        <?php if ($i <= $review['rating']): ?>
            <span class="fa fa-star checked"></span>
        <?php else: ?>
            <span class="fa fa-star"></span>
        <?php endif; ?>
    <?php endfor; ?>
</div>
                    <p><?= nl2br(htmlspecialchars($review['review'])) ?></p>
                    <p><?= nl2br(htmlspecialchars($review['description'])) ?></p>
                    <?php if (!empty($review['media_path'])): ?>
                        <div class="review-media">
                            <?php if (in_array(strtolower(pathinfo($review['media_path'], PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                                <img src="<?= htmlspecialchars($review['media_path']) ?>" alt="Review Media" class="img-fluid">
                            <?php else: ?>
                                <video controls class="img-fluid">
                                    <source src="<?= htmlspecialchars($review['media_path']) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="mt-3">
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <button type="submit" name="like_review" class="btn btn-success btn-sm btn-action">Like (<?= $review['likes'] ?>)</button>
                        </form>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <button type="submit" name="reject_review" class="btn btn-danger btn-sm btn-action">Reject (<?= $review['rejects'] ?>)</button>
                        </form>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="review_id" value="<?= $review['id'] ?>">
                            <button type="submit" name="delete_review" class="btn btn-danger btn-sm btn-action">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- Rating Breakdown -->
    <div class="rating-breakdown">
        <h3>Rating Breakdown</h3>
        <?php for ($i = 5; $i >= 1; $i--): ?>
            <p><?= $i ?> star: <?= $starCounts[$i] ?> reviews</p>
        <?php endfor; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            var $star_rating = $('.star-rating .fa');

            var SetRatingStar = function() {
                return $star_rating.each(function() {
                    if (parseInt($star_rating.siblings('input.rating-value').val()) >= parseInt($(this).data('rating'))) {
                        return $(this).addClass('checked');
                    } else {
                        return $(this).removeClass('checked');
                    }
                });
            };

            $star_rating.on('click', function() {
                $star_rating.siblings('input.rating-value').val($(this).data('rating'));
                return SetRatingStar();
            });

            SetRatingStar();
            $(document).ready(function() {

            });
        });
    </script>
</body>
</html>
