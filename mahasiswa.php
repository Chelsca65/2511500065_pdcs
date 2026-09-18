<?php
header('Content-Type: application/json');

$file = __DIR__ . '/data_mahasiswa.json';

if (!file_exists($file)) {
    file_put_contents($file, '[]');
}

$data = json_decode(file_get_contents($file), true) ?: [];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(['status' => 'success', 'data' => array_values($data)]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true) ?: [];

    if (empty($input['nim']) || empty($input['nama']) || empty($input['jurusan'])) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'nim, nama, jurusan wajib diisi']);
        exit;
    }

    $id = 1;
    foreach ($data as $m) {
        if ($m['id'] >= $id) $id = $m['id'] + 1;
    }

    $baru = ['id' => $id, 'nim' => $input['nim'], 'nama' => $input['nama'], 'jurusan' => $input['jurusan']];
    $data[] = $baru;
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT));

    http_response_code(201);
    echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambah', 'data' => $baru]);
    exit;
}

http_response_code(405);
echo json_encode(['status' => 'error', 'message' => 'Method tidak didukung']);