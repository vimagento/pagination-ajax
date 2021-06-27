<?php

global $conn;

// Hàm kết nối database
function connect(){
    global $conn;
    $conn = mysqli_connect('localhost', 'vimagento', '123456', 'ajax') or die ('{error:"bad_request"}');
}

// Hàm đóng kết nối
function disconnect(){
    global $conn;
    if ($conn){
        mysqli_close($conn);
    }
}

// Lấy tổng số bài viết
function count_posts()
{
    global $conn;
    $query = mysqli_query($conn, 'select count(*) as total from posts');
    if ($query){
        $row = mysqli_fetch_assoc($query);
        return $row['total'];
    }
    return 0;
}

// Lấy bài viết theo số trang
function get_all_post($limit, $start)
{
    global $conn;
    $sql = "select * from posts limit {$limit}, {$start}";
    $query = mysqli_query($conn, $sql);

    $result = array();

    if ($query) {
        while ($row = mysqli_fetch_assoc($query)) {
            $result[] = $row;
        }
    }

    return $result;
}