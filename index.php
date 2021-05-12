<?php
require_once './db.inc.php';
/**
 * 執行 SQL 語法,取得 items 資料表總筆數,並回傳,建立 PDOstatment 物件
 * 查詢結果,取得第一筆資料(索引為 0),資料表總筆數
 */
$total =  $pdo->query("SELECT COUNT(eventId) AS `count` FROM `event`")->fetchAll()[0]['count'];

// 每頁幾筆
$numPerPage = 4;

// 總頁數,ceil()為無條件進位
$totalPages = ceil($total / $numPerPage);

// 目前第幾頁
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// 若 page 小於 1,則回傳 1
$page = $page < 1 ? 1 : $page;
$page = $page > $totalPages ? $totalPages : $page;



?>

<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>ARTDDICT</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="all,follow">
  <?php
  require_once './template/linkTemplate.php';
  ?>
</head>

<body>
  <div class="page-holder">
    <!-- navbar-->
    <header class="header bg-white">
      <div class="container px-0 px-lg-3">
        <nav class="navbar navbar-expand-lg navbar-light py-3 px-lg-0"><a class="navbar-brand" href="index.php"><span class="font-weight-bold text-uppercase text-dark">ARTDDICT</span></a>

          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
              <li class="nav-item">
                <!-- Link--><a class="nav-link active" href="index.html"></a>
              </li>
              <li class="nav-item">
                <!-- Link--><a class="nav-link" href="shop.html"></a>
              </li>
              <div class="dropdown-menu mt-3" aria-labelledby="pagesDropdown"><a class="dropdown-item border-0 transition-link" href="index.html"></a><a class="dropdown-item border-0 transition-link" href="shop.html"></a><a class="dropdown-item border-0 transition-link" href="detail.html"></a></div>
              </li>
            </ul>
            <ul class="navbar-nav ml-auto">
              <li class="nav-item"><span class="nav-link" href="cart.html"> <i class="fas fa-dolly-flatbed mr-1 text-white"></i></span></li>
              <li class="nav-item"><a class="nav-link" href="./loginPage.php"> <i class="fas fa-user-alt mr-1 text-gray"></i>Login</a></li>
            </ul>
          </div>
        </nav>
      </div>
    </header>
    <?php require_once './template/modal.php'; ?>
    <!-- HERO SECTION-->
    <div class="container">
      <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <img src="./images/006.jpg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./images/003.jpg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./images/104.jpg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./images/101.jpg" class="d-block w-100" alt="...">
          </div>
          <div class="carousel-item">
            <img src="./images/002.jpg" class="d-block w-100" alt="...">
          </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>
    </div>
    <span id="EventList"></span>
    <!-- CATEGORIES SECTION-->
    <section class="pt-5">
      <header class="text-center">
        <p class="small text-muted small text-uppercase mb-1">一同與藝術，共襄盛舉</p>
        <h2 class="h5 text-uppercase mb-4">活動清單</h2>
      </header>




      <!-- 測試區 -->
      <div class="container">
        <div class="row">
          <?php
          // SQL 敘述
          $sql = "SELECT `id`, `eventName`, `eventDescription`, `eventPrice`, `eventImg`,`eventId`
                  FROM `event`
                  ORDER BY `id` ASC
                  LIMIT ?, ? ";
          // 設定繫結值
          $arrParam = [($page - 1) * $numPerPage, $numPerPage];
          // 查詢分頁後的商品資料
          $stmt = $pdo->prepare($sql);
          $stmt->execute($arrParam);
          if ($stmt->rowCount() > 0) {
            $arr = $stmt->fetchAll();
            for ($i = 0; $i < count($arr); $i++) {
          ?>
              <div class="col-md-6 mb-4 mb-md-0 py-3"><span class="category-item" href="./eventDetail.php?itemId=<?php echo $arr[$i]['eventId'] ?>"><img class="img-fluid" src="./images/<?php echo $arr[$i]['eventImg'] ?>" alt=""><strong><?php echo $arr[$i]['eventName'] ?></strong></span></div>
          <?php
            }
          }
          ?>
        </div>
      </div>
      <!-- 測試區 -->

      <!-- 分頁切換 -->

      <nav class="mb-5" aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
          <li class="page-item">
            <a class="page-link" href="?page=1#EventList">第一頁</a>
          </li>

          <?php for ($i = 1; $i <= $totalPages; $i++) { ?>
            <li class="page-item">
              <a class="page-link" href="?page=<?php echo $i ?> #EventList">
                <?php echo $i ?>
              </a>
            </li>
          <?php } ?>

          <li class="page-item">
            <a class="page-link" href="?page=<?php echo (int)$totalPages ?> #EventList">最底頁</a>
          </li>

        </ul>
      </nav>

      <!-- 分頁切換 -->


      <?php require_once './template/footer.php'; ?>
      <!-- JavaScript files-->
      <script src="vendor/jquery/jquery.min.js"></script>
      <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
      <script src="vendor/lightbox2/js/lightbox.min.js"></script>
      <script src="vendor/nouislider/nouislider.min.js"></script>
      <script src="vendor/bootstrap-select/js/bootstrap-select.min.js"></script>
      <script src="vendor/owl.carousel2/owl.carousel.min.js"></script>
      <script src="vendor/owl.carousel2.thumbs/owl.carousel2.thumbs.min.js"></script>
      <script src="js/front.js"></script>
      <script>
        // ------------------------------------------------------- //
        //   Inject SVG Sprite - 
        //   see more here 
        //   https://css-tricks.com/ajaxing-svg-sprite/
        // ------------------------------------------------------ //
        function injectSvgSprite(path) {

          var ajax = new XMLHttpRequest();
          ajax.open("GET", path, true);
          ajax.send();
          ajax.onload = function(e) {
            var div = document.createElement("div");
            div.className = 'd-none';
            div.innerHTML = ajax.responseText;
            document.body.insertBefore(div, document.body.childNodes[0]);
          }
        }
        // this is set to BootstrapTemple website as you cannot 
        // inject local SVG sprite (using only 'icons/orion-svg-sprite.svg' path)
        // while using file:// protocol
        // pls don't forget to change to your domain :)
        injectSvgSprite('https://bootstraptemple.com/files/icons/orion-svg-sprite.svg');
      </script>
      <!-- FontAwesome CSS - loading as last, so it doesn't block rendering-->
      <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    </section>
  </div>
</body>

</html>