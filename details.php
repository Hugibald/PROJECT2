<?php
require_once "components/restriction_user.php";
require_once "components/db_connect.php";
require_once "components/cleaninputs.php";
require_once "components/navbar.php";
require_once "components/footer.php";
require_once "components/links.php";

$product_id = $_GET['id'];
// echo $productId;
// exit;

$sql = "SELECT * FROM `products` WHERE product_id = $product_id";
$result = mysqli_query($connect, $sql);

$userId = $_SESSION["user"];

$layout = "";
$message = "";
$ratingError = "";


if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // So Add to Cart only visible when user
    $cartButton = "
        <form action='cart/add_to_cart.php' method='post'>
            <input type='hidden' name='product_id' value='{$row["product_id"]}'>
            <button type='submit' class='btn btn-success'>Add to Cart</button>
        </form>";
        if (!isset($_SESSION["user"])) {
            $cartButton = "";
        }
    // So Discount only shows if there is a Discount:
    $discountText = "";
    if (!is_null($row["discount"]) && $row["discount"] != "") {
        $discountText = "
        <p class='card-text my-0'>
            Discount: <b class='text-warning'>{$row['discount']}%</b>
        </p>
    ";
    }
    // QUESTION-Button and Function
    $questionButton = "
                <button class='btn btn-warning my-2' onclick='openQuestion()'>Questions?</button>
                <div id='questionModal' style='display:none; position:fixed; top:10%; left:10%; background:#fff; padding:20px; border:1px solid #ccc; width: 500px;'>
                    <div style='text-align: right;'><button type='button' onclick='closeQuestion()' class='btn btn-danger'><b>X</b></button></div>
                    <form  class='row g-3' method='POST' enctype='multipart/form-data'>
                        <label>Enter your Question:</label><br>
                        <textarea name='question' style='width:450px; height: 100px;' required></textarea>
                        <input type='hidden' name='product_id' value='$product_id'>
                        <input type='hidden' name='user_id' value='$userId'>
                        <br><br>
                        <div style='text-align: right;'>
                            <button type='submit' name='send_question' class='btn btn-success'>Send</button>
                        </div>
                    </form>
                </div>";
    // Insert QUESTION in DB
    // $question = "";
    if (isset($_POST['send_question'])) {

        $error=false;

        $question = cleanInput($_POST["question"]);

        if (empty($question)){
            $error = true;
            $questionError = "Please enter a question or press the X-Button.";
        }else {
            $question_sql = "'" . mysqli_real_escape_string($connect, $question) . "'";
        }

        if(!$error){
            $sql_qu = "INSERT INTO `questions`(`question_date`, `question`, `user_id`, `product_id`, `answer`) VALUES (NOW(), $question_sql,'$userId','$product_id', NULL)";
            mysqli_query($connect, $sql_qu);

            header("Location: details.php?id=$product_id");
            exit;
        }
    }

    // QUESTION-SQL
    $sql_q = "
    SELECT u.first_name, q.question_date, q.question, q.answer
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
            if($answer == NULL){
                $answerText = "";
            }else{
                $answerText = "<div> {$row_q['answer']}</div>";
            }
            $questions .=
            "<span class='card-text my-0'><b>{$row_q["first_name"]}</b></span>
            <div> {$row_q['question']}</div>
            <p class='small text-muted text-end'>{$row_q['question_date']}</p>
            <span>Answer: </span>
            $answerText
            <hr>";}
    }


    // REVIEW SECTION
    // REVIEW-SQL
    $sql_o = "
    SELECT u.first_name, u.last_name, r.rating, r.rating_text, r.review_date, r.review_ok, r.order_item_id
    FROM users u
    JOIN reviews r ON u.user_id = r.user_id
    JOIN order_items i ON r.order_item_id = i.order_item_id
    JOIN orders o ON i.order_id = o.order_id
    WHERE i.product_id = $product_id
    ";
    $result_r = mysqli_query($connect, $sql_o);
    // Show Rating-Function
    function renderBeans ($review) {
        $review = (int)$review;
        $output = '';
            for ($i = 1; $i <= 5; $i++) {
            $output .= $i <= $review ? "<img src='pictures/rating/full_bean.png'>" : "<img src='pictures/rating/empty_bean.png'>";
            }
        return $output;
    };
    // Show Reviews
    $reviews = "";
    if(mysqli_num_rows($result_r) > 0) {
        while ($row_r = mysqli_fetch_assoc($result_r)){
            $beans = renderBeans($row_r['rating']) ;
            $reviews .=
            "<span class='card-text my-0'><b>{$row_r["first_name"]}: </b></span>
            <span>$beans</span>
            <div> {$row_r['rating_text']}</div>";}
    }


    // RATING-SQL
    $sql_u = "
    SELECT i.order_item_id
    FROM order_items i
    JOIN orders o ON i.order_id = o.order_id
    WHERE o.user_id = $userId
    AND i.product_id = $product_id
    LIMIT 1";
    $result_u = mysqli_query($connect, $sql_u);

    // SHOW RATING OPTION
    $orderItemId = '';
    $rating = '';
    if (mysqli_num_rows($result_u) > 0 ){
        $row_item = mysqli_fetch_assoc($result_u);
        $orderItemId = $row_item['order_item_id'];
        $rating = "
        <form  class='row g-3' id='review' method='POST' enctype='multipart/form-data'>
            <div class='col mx-auto'>
                <div class='mb-3'>
                    <label for='rating'>Rating</label>
                    <select class='form-control' name='rating' id='rating'>
                        <option value=''>-- Please select your rating --</option>
                        <option value='1'>1</option>
                        <option value='2'>2</option>
                        <option value='3'>3</option>
                        <option value='4'>4</option>
                        <option value='5'>5</option>
                    </select>
                    <p class='text-danger'><?= $ratingError ?? '' ?></p>
                </div>
                <div class='mb-3'>
                        <label for='message'>Message</label>
                        <br>
                        <textarea rows=6 id='message' class='form-control' name='message'></textarea>
                </div>
                <input type='submit' class='btn btn-success' name='send_review' value='Send Review'>
            </div>
        </form>
        ";
    }

    // RATING-SECTION
    if (isset($_POST['send_review'])) {

        $error=false;

        $rating = cleanInput($_POST['rating']);
        $message = cleanInput($_POST['message']);

        if ($message === '') {
            $message_sql = "NULL";
        } else {
            $message_sql = "'" . mysqli_real_escape_string($connect, $message) . "'";
        }

        if (empty($rating)){
            $error = true;
            $ratingError = "Please rate our Product.";
        }

        if(!$error){
            $sql_s = "INSERT INTO `reviews`
            ( `rating`, `rating_text`, `user_id`, `order_item_id`, `review_date`, `review_ok`)
            VALUES
            ('$rating',$message_sql,'$userId','$orderItemId',NOW(),'0')";
            mysqli_query($connect, $sql_s);

            header("Location: details.php?id=$product_id");
            exit;
        }
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
                    $cartButton
                    $questionButton
                    <br>
                    $questions
                    $rating
                    <br>
                    $reviews
                    <br>
                    <a href='userprofile.php' class='btn btn-secondary my-2'>Back</a>
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

    <title>Beanternet</title>
</head>

<body>
    <?= $navbar ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <?= $layout ?>
        </div>
    </div>
    <?= $footer ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <script>
    function openQuestion() {
        document.getElementById("questionModal").style.display = "block";
    }
    function closeQuestion() {
        document.getElementById("questionModal").style.display = "none";
    }
</script>
</body>

</html>
