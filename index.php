<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- <script src="https://code.jquery.com/jquery-3.5.0.js"></script> -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <title>CRUD - Server Side DataTables</title>
</head>

<body>
    <h1 class="text-center">DATATABLES CRUD</h1>
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahUserModal">
                            + User
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <table id="datatable" class="table">
                            <thead>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Mobile</th>
                                <th>City</th>
                                <th>Aksi</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- jquery cdn -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <!-- data tables -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/datatables.min.css" />
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/datatables.min.js"></script>

    <script type="text/javascript">
        $('#datatable').DataTable({
            'serverSide': true,
            'processing': true,
            'paging': true,
            'order': [],
            'ajax': {
                'url': 'fetchData.php',
                'type': 'post'
            },
            'fnCreatedRow': function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'columnDefs': [{
                'target': [0, 5],
                'orderable': false
            }]

        });
    </script>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- javascript tambah user -->
    <script type="text/javascript">
        $(document).on('submit', '#formtuser', function(event) {
            event.preventDefault();
            var username = $('#username').val();
            var email = $('#email').val();
            var mobile = $('#mobile').val();
            var city = $('#city').val();

            if (username != '' && email != '' && mobile != '' && city != '') {
                $.ajax({
                    url: 'add_user.php',
                    data: {
                        username: username,
                        email: email,
                        mobile: mobile,
                        city: city
                    },
                    type: 'post',
                    success: function(data) {
                        var json = JSON.parse(data);
                        status = json.status;
                        if (status == "success") {
                            table = $('#datatable').DataTable();
                            table.draw();
                            alert('Berhasil Tambah User');
                        }
                    }
                });
            } else {
                alert("input tidak boleh kosong");
            }
        });

        $(document).on('click', '.editBtn', function(event) {
            var trid = $(this).closest('tr').attr('id');
            var id = $(this).data('id');
            $.ajax({
                url: "get_single_user.php",
                data: {
                    id: id
                },
                type: "post",
                success: function(data) {
                    var json = JSON.parse(data);
                    $('#id').val(json.id);
                    $('#trid').val(trid);
                    $('#_username').val(json.username);
                    $('#_email').val(json.email);
                    $('#_mobile').val(json.mobile);
                    $('#_city').val(json.city);
                    $('#editUserModal').modal('show');
                }
            });
        });

        $(document).on('submit', '#updateUser', function(e) {
            e.preventDefault();
            var id = $('#id').val();
            // var trid = $('#trid').val();
            var username = $('#_username').val();
            var email = $('#_email').val();
            var mobile = $('#_mobile').val();
            var city = $('#_city').val();
            $.ajax({
                url: "update_user.php",
                data: {
                    id: id,
                    username: username,
                    email: email,
                    mobile: mobile,
                    city: city
                },
                type: 'post',
                success: function(data) {
                    var json = JSON.parse(data);
                    console.log(json);
                    var status = json.status;
                    if (status == 'success') {
                        var table = $('#datatable').DataTable();
                        var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-info btn-sm editbtn">Edit</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Delete</a></td>';
                        var row = table.row("[id='" + trid + "']");
                        row.row("[id='" + trid + "']").data([id, username, email, mobile, city, button]);
                        console.log(row);
                        $('#editUserModal').modal('hide');
                    } else {
                        alert('failed');
                    }
                }
            });
        });
    </script>

    <!-- Modal Tambah User -->
    <div class="modal fade" id="tambahUserModal" tabindex="-1" aria-labelledby="tambahUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahUserModalLabel">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void();" method="post" id="formtuser">
                    <div class="modal-body">
                        <div class="mb-3 row">
                            <label for="username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="username" class="form-control" id="username" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" name="email" class="form-control" id="email" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="mobile" class="col-sm-2 col-form-label">mobile</label>
                            <div class="col-sm-10">
                                <input type="text" name="mobile" class="form-control" id="mobile" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" name="city" class="form-control" id="city" value="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Tambah User -->

    <!-- Modal Update User -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Update User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="javascript:void();" method="post" id="updateUser">
                    <div class="modal-body">
                        <input type="hidden" id="id" name="id" value="">
                        <input type="hidden" id="trid" name="trid" value="">
                        <div class="mb-3 row">
                            <label for="_username" class="col-sm-2 col-form-label">Username</label>
                            <div class="col-sm-10">
                                <input type="text" name="_username" class="form-control" id="_username" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_email" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input type="text" name="_email" class="form-control" id="_email" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_mobile" class="col-sm-2 col-form-label">mobile</label>
                            <div class="col-sm-10">
                                <input type="text" name="_mobile" class="form-control" id="_mobile" value="">
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="_city" class="col-sm-2 col-form-label">City</label>
                            <div class="col-sm-10">
                                <input type="text" name="_city" class="form-control" id="_city" value="">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- End Modal Update User -->

</body>

</html>