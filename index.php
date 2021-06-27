<?php
require_once "database.php";

connect(); // Kết nối đến database;
// Mặc định sẽ là trang 1.
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;
$postTotal = count_posts();// Lấy tổng số bài viết.
$postOnePage = 3; // Số bài viết hiển thị trong 1 trang.
// Khi đã có tổng số bài viết và số bài viết trong một trang ta có thể tính ra được tổng số trang
$pageTotal = ceil($postTotal / $postOnePage);
$limit = ($current_page - 1) * $postOnePage;
$data = get_all_post($limit, $postOnePage);
// Kiểm tra nếu là ajax request thì trả kết quả
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $output = '';
    $output .= '<div class="panel-body">
            <table class="table table-striped table-bordered table-list">
                <thead>
                <tr>
                    <th class="hidden-xs">ID</th>
                    <th>Name</th>
                </tr>
                </thead>
                <tbody id="content">';
    foreach ($data as $item) {
        $output .= '<tr>';
        $output .= '<td>' . $item['post_id'] . '</td>';
        $output .= '<td>' . $item['post_name'] . '</td>';
        $output .= '</tr>';
    }
    $output .= '</tbody>
                    </table>
                </div>';

    // Phân trang
    $output .= '<div class="panel-footer">
                    <div class="row">
                        <div class="col col-xs-8">
                            <ul class="pagination hidden-xs pull-right">';
    if ($current_page > 1) {
        $output .= '<li><a href="index.php?page=' . ($current_page - 1) . '">«</a></li>';
    }
    for ($i = 1; $i <= $pageTotal; $i++) {
        $class = ($current_page == $i) ? 'disabled' : '';
        $output .= '<li class="' . $class . '">';
        $output .= '<a href="index.php?page=' . $i . '">' . $i . '</a>';
        $output .= '</li>';
    }
    if ($current_page < $pageTotal) {
        $output .= '<li><a href ="index.php?page='.($current_page + 1).'">»</a></li>';
    }
    $output .= '</ul>
                </div>
                </div>
                </div>';
    die($output);
}
disconnect(); // Ngắt kết nối database.
?>
<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <h1>Phân trang với Ajax, PHP và MYSQL</h1>
            <div class="panel panel-default panel-table">
                <div class="panel-body">
                    <table class="table table-striped table-bordered table-list">
                        <thead>
                        <tr>
                            <th class="hidden-xs">ID</th>
                            <th>Name</th>
                        </tr>
                        </thead>
                        <tbody id="content">
                        <?php foreach ($data as $post): ?>
                            <tr>
                                <td class="hidden-xs"><?= $post['post_id']; ?></td>
                                <td><?= $post['post_name']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col col-xs-8">
                            <ul class="pagination hidden-xs pull-right">
                                <?php if ($current_page > 1): ?>
                                    <li><a href="index.php?page=<?= $current_page - 1; ?>">«</a></li>
                                <?php endif; ?>
                                <?php for ($i = 1; $i <= $pageTotal; $i++): ?>
                                    <li class="<?= ($current_page == $i) ? 'disabled' : ''; ?>">
                                        <a href="index.php?page=<?= $i; ?>"><?= $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <?php if ($current_page < $pageTotal): ?>
                                    <li><a href="index.php?page=<?= $current_page + 1; ?>">»</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script language="JavaScript">
    $(document).ready(function () {
        $('body').on('click', '.pagination li a', function (e) {
            e.preventDefault();// Không load lại trang khi click phân trang.
            let url = $(this).attr('href');
            $.ajax({
                url: url,
                method: 'GET',
                dataType: 'text',
                success: function (response) {
                    $('.panel-table').html(response);
                    // Thay đổi URL trên website
                    window.history.pushState({path:url},'',url);
                }
            });
        });
    });
</script>
</body>
</html>
