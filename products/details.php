<?php
require_once "../components/restriction_admin.php";
require_once '../components/db_connect.php';
require_once '../components/cleaninputs.php';
// Link-component for subfolder:
require_once "../components/subfolder_links.php";

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";

$product_id = $_GET['id'];


$sql = "SELECT * FROM `products` WHERE product_id = $product_id";
$result = mysqli_query($connect, $sql);

// REVIEW-SQL
$sql_o = "
    SELECT u.first_name, u.last_name, r.review_id, r.rating, r.rating_text, r.review_date, r.review_ok, r.order_item_id
    FROM users u
    JOIN reviews r ON u.user_id = r.user_id
    JOIN order_items i ON r.order_item_id = i.order_item_id
    JOIN orders o ON i.order_id = o.order_id
    WHERE i.product_id = $product_id
";
$result_r = mysqli_query($connect, $sql_o);

// REVIEW SECTION
function renderBeans ($review) {
    $review = (int)$review;
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        $output .= $i <= $review ? "<img src='../pictures/rating/full_bean.png'>" : "<img src='../pictures/rating/empty_bean.png'>";
    }
    return $output;
};

// REVIEW Admin-Input
$admin_ok = '';
$reviews = "";
if(mysqli_num_rows($result_r) > 0) {
    while ($row_r = mysqli_fetch_assoc($result_r)){
        $review_id = $row_r['review_id'];
        $input = $row_r['review_ok'];
        if($input == 1){
            $admin_ok = '';
        }else{
            // Checkbox and Delete
            $admin_ok = "
            <div class='row g-3'>
                <div class='col mx-auto'>
                    <span>Approve:<span>
                    <input type='checkbox' class='review-ok-checkbox ms-1 me-5' data-id='{$row_r['review_id']}'>
                    <form method='POST' style='display:inline-block;'>
                        <input type='submit' class='btn btn-danger' name='del_rev' value='Delete'>
                    </form>
                </div>
            </div>
            ";
        }
        $beans = renderBeans($row_r['rating']) ;
        $reviews .=
            "<span id='review-{$row_r['review_id']}' class='card-text my-0'><b>{$row_r["first_name"]}: </b></span>
            <span>$beans</span>
            <div> {$row_r['rating_text']}</div>
            $admin_ok
            <br>";
    }
    if(isset($_POST['del_rev'])){
        $sql_d = "DELETE FROM `reviews` WHERE review_id = $review_id";
        mysqli_query($connect, $sql_d);

        header("Location: details.php?id=$product_id");
        exit;
    }
    if(isset($_POST['review_id'])){
        $review_id_post = (int)$_POST['review_id'];
        $sql_update = "UPDATE reviews SET review_ok = 1 WHERE review_id = $review_id_post";
        if(mysqli_query($connect, $sql_update)){
            echo "Review approved!";
        } else {
            echo "Error: " . mysqli_error($connect);
        }
        exit;
    }
}

// QUESTION-SQL
$sql_q = "
    SELECT u.first_name, q.question_id, q.question_date, q.question, q.answer
    FROM users u
    JOIN questions q ON u.user_id = q.user_id
    WHERE q.product_id = $product_id
";
$result_q = mysqli_query($connect, $sql_q);

// Show QUESTIONS

$questions = "";
if(mysqli_num_rows($result_q) > 0) {
     while ($row_q = mysqli_fetch_assoc($result_q)){
        $answer=$row_q['answer'];
        $question_id = $row_q['question_id'];
        if($answer == NULL){
            $answerText = "
            <div class='row g-3'>
                <div class='col mx-auto'>
                    <form method='POST' class='text-end'>
                        <input type='hidden' name='question_id' value='$question_id'>
                        <input type='submit' class='btn btn-danger' name='delete_qu' value='Delete'>
                    </form>
                    <form method='POST' class='row g-3'>
                        <input type='hidden' name='question_id' value='$question_id'>
                        <div class='col mx-auto'>
                            <label for='ad_answer'>Answer:</label>
                            <textarea rows='2' class='form-control' name='ad_answer'></textarea>
                            <div class='text-end'>
                                <input type='submit' class='btn btn-success my-2' name='send_answer' value='Send Answer'>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            ";
        }else{
            $answerText = "<div> {$row_q['answer']}</div>";
        }
        $questions .=
            "<span id='question-{$row_q['question_id']}' class='card-text my-0'><b>{$row_q["first_name"]}</b></span>
            <div> {$row_q['question']}</div>
            <p class='small text-muted text-end'>{$row_q['question_date']}</p>
            $answerText
            <hr>";
    }
}
if(isset($_POST['delete_qu'])){
        $sql_delQ = "DELETE FROM `questions` WHERE question_id = $question_id";
        mysqli_query($connect, $sql_delQ);

        header("Location: details.php?id=$product_id");
        exit;
    }
if (isset($_POST['send_answer'])) {

        $error=false;
        $question_id = (int)$_POST['question_id'];
        $ad_answer = cleanInput($_POST['ad_answer']);

        if (empty($ad_answer)) {
            $error = true;
            $ratingError = "Please answer the Question.";
        } else {
            $ad_answer_sql = "'" . mysqli_real_escape_string($connect, $ad_answer) . "'";
        }

        if(!$error){
            $sql_qu = "UPDATE `questions`SET `answer` = $ad_answer_sql WHERE question_id = $question_id";
            mysqli_query($connect, $sql_qu);

            header("Location: details.php?id=$product_id");
            exit;
        }
    }


$layout = "";

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $sqlSupplier = "SELECT * FROM supplier where supplier_id = {$row['supplier_id']}";
    $resultSupplier = mysqli_query($connect, $sqlSupplier);
    $rowSupplier = mysqli_fetch_assoc($resultSupplier);
    $supplier = $rowSupplier['name'];
    // echo "<pre>";
    // print_r($row);
    // echo "</pre>";
        // So Discount only shows if there is a Discount:
    $discountText = "";
    if (!is_null($row["discount"]) && $row["discount"] != "") {
        $discountText = "
        <p class='card-text my-0'>
            Discount: <b class='text-warning'>{$row['discount']}%</b>
        </p>
    ";
    }
    $layout = "
            <div class='card'>
                <div class='card-body'>
                    <img src='/pictures/products/{$row["product_picture"]}' class='card-img-top' alt='{$row["name"]}' style='max-width: 600px; height: auto; border-radius: 8px;'>
                    <h5 class='card-title text-success'>{$row["name"]}</h5>
                    <p class='card-text my-0'>Strength: {$row["strength"]}</p>
                    <p class='card-text my-0'>Aroma: {$row["aroma"]}</p>
                    <p class='card-text my-0'>Price: {$row["price"]}</p>
                    $discountText
                    <p class='card-text my-0'>{$row["description"]}</p>
                    <br>
                    <p class='card-text my-0'>Supplier: {$supplier}</p>
                    <br>
                    $questions
                    $reviews
                    <a href='../dashboard.php' class='btn btn-secondary my-2'>Back</a>
                </div>
            </div>
    ";
} else {
    $layout = "<h3>No product found with ID $product_id </h3>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?= $links ?>
    <title>Details</title>
</head>
<body>
    <?php
    include __DIR__ . '/../components/navbar.php';
    echo $navbar;
    ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <?php echo $layout ?>
        </div>
    </div>
    <?php
    include __DIR__ . '/../components/footer.php';
    echo $footer;
    ?>
    <script src="reviews.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>
</html>
