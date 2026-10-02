<?php
require_once "../config.php";
require_once "../helpers/response.php";

// GET ID

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $q = "SELECT 
        m.id, 
        m.nama, 
        m.nim, 
        j.nama_jurusan AS jurusan 
        FROM mahasiswa m 
        LEFT JOIN jurusan j ON m.jurusan_id = j.id 
        WHERE m.id = '$id'" ;

        $r = mysqli_query($koneksi, $q);

        if (!$r) {
            sendResponse(
                false,
                "query gagal: " . mysqli_error($koneksi),
                null,
                500
            );
        }

    $data = mysqli_fetch_assoc($r);

    if (!$data) {
        sendResponse(false, "mahasisiswa tidak ditemukan",
        null, 404);
    }

    sendResponse(true, "berhasil", $data, 200);
    }

    // SEARCH

if (isset($_GET['search'])) {
    $search = $_GET['search'];

    $q = "SELECT 
        m.id, 
        m.nama, 
        m.nim, 
        j.nama_jurusan AS jurusan 
        FROM mahasiswa m 
        LEFT JOIN jurusan j ON m.jurusan_id = j.id 
        WHERE m.nama LIKE '%$search%'
            OR m.nim LIKE '%$search%'
            ORDER BY m.id DESC";

        $r = mysqli_query($koneksi, $q);

        if (!$r) {
            sendResponse(
                false,
                "query gagal: " . mysqli_error($koneksi),
                null,
                500
            );
        }

    $data = [];

    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }

    sendResponse(
        true, 
        "berhasil", 
        $data, 
        200);
    }

    //PAGINATION

    if (isset($_GET['page']) || isset($_GET['limit'])) {

    $page = isset($_GET['page'])
    ? (int) $_GET['page']
    : 1;

    $limit = isset($_GET['limit'])
    ? (int) $_GET['limit']
    : 10;

    if ($page < 1) {
        $page = 1;
    }

    if ($limit < 1) {
        $limit = 10;
    }
    $offset = ($page - 1) * $limit;

    $q = "SELECT 
        m.id, 
        m.nama, 
        m.nim, 
        j.nama_jurusan AS jurusan 
        FROM mahasiswa m 
        LEFT JOIN jurusan j 
            ON m.jurusan_id = j.id 
            ORDER BY m.id DESC
            LIMIT $limit OFFSET $offset";

        $r = mysqli_query($koneksi, $q);

        if (!$r) {
            sendResponse(
                false,
                "query gagal: " . mysqli_error($koneksi),
                null,
                500
            );
        }

    $data = [];

    while ($row = mysqli_fetch_assoc($r)) {
        $data[] = $row;
    }

    sendResponse(
        true, 
        "berhasil", 
        $data, 
        200);
    }


