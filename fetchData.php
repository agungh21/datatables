<?php
include('conn.php');

$output = array();
$sql = "SELECT
        *
        FROM
        users
        ";
$query = mysqli_query($con, $sql);
$rows = mysqli_num_rows($query);

if (isset($_POST['search']['value'])) {
    $search_value = $_POST['search']['value'];
    $sql .= " WHERE username like '%" . $search_value . "%'";
    $sql .= " OR email like '%" . $search_value . "%'";
    $sql .= " OR mobile like '%" . $search_value . "%'";
    $sql .= " OR city like '%" . $search_value . "%'";
}

if (isset($_POST['order'])) {
    $column = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY " . $column . " " . $order . "";
} else {
    $sql .= " ORDER BY id ASC";
}

if ($_POST['length'] != -1) {
    $start = $_POST['start'];
    $length = $_POST['length'];
    $sql .= " LIMIT " . $start . ", " . $length;
}

$data = array();
$i = 1;

$run_query = mysqli_query($con, $sql);
$filtered_rows = mysqli_num_rows($run_query);
while ($row = mysqli_fetch_assoc($run_query)) {
    $subarray = array();
    $subarray[] = $i++;
    $subarray[] = $row['username'];
    $subarray[] = $row['email'];
    $subarray[] = $row['mobile'];
    $subarray[] = $row['city'];
    $subarray[] = '<a href="javascript:void();" data-id="' . $row['id'] . '" class="btn btn-sm btn-info editBtn">Edit</a> <a href="javascript:void();" data-id="' . $row['id'] . '" class="btn btn-sm btn-danger">Hapus</a>';

    $data[] = $subarray;
}

$output = array(
    'data' => $data,
    'draw' => intval($_POST['draw']),
    'recordsTotal' => $filtered_rows,
    'recordsFiltered' => $rows
);

echo json_encode($output);
