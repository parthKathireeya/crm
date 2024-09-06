<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>SB Admin 2 - Blank</title>

        @include("system.includes.include_css")
    </head>
    <body id="page-top">
        <div id="wrapper">
            @include("system.includes.sidebar")
            <div id="content-wrapper" class="d-flex flex-column">
                <div id="content">
                    @include("system.includes.navbar")
                    <div class="container-fluid">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">All User Types</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width='5%'>No.</th>
                                                <th>User Type</th>
                                                <th width='27%'></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2020</span>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <a class="scroll-to-top rounded" href="#page-top"><i class="fas fa-angle-up"></i></a>
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-primary" href="login.html">Logout</a>
                    </div>
                </div>
            </div>
        </div>
        @include("system.includes.include_js")
        @include("system.includes.toastr")
        <script>

            function deleteRecord(id) {
                $.ajax({
                    url: 'userTypeRights/delete/' + id,
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.ack == 1) {
                            toastr.success(response.Message)
                        }else{
                            toastr.error(response.Message)
                        }
                        loadTable();
                    }
                });
            };

            function loadTable() {
                $.ajax({
                    url: '{{ route("userTypeRights.loadTable") }}',
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.data_available && response.UsersTypes) {
                            var dataTable = $('#dataTable').DataTable();
                            var addRights = "{{ session('authentication.rights.add_'.$module_details['moduleId']) }}";
                            var editRights = "{{ session('authentication.rights.edit_'.$module_details['moduleId']) }}";
                            var deleteRights = "{{ session('authentication.rights.delete_'.$module_details['moduleId']) }}";
                            var user_type_id = "{{ session('authentication.user_type_id') }}";
                            var doedit = false;
                            var dodelete = false;

                            if(editRights == 1 || user_type_id == 1 || addRights == 1){
                                doedit = true;
                            }

                            if(deleteRights == 1 || user_type_id == 1){
                                dodelete = true;
                            }

                            // Clear existing rows before adding new ones
                            dataTable.clear().draw();

                            var count = 1; // Initialize count
                            $.each(response.UsersTypes, function(index, item) {
                                var action = "<a class='btn btn-info' href='/userTypeRights/manageRights/" + item.id + "'><i class='fas fa-user-cog'></i> Manage Rights</a> ";
                                if(item.right_id > 0){
                                    var buttonClass = item.isActive == 1 ? 'btn btn-danger' : 'btn btn-success';
                                    var iconClass = item.isActive == 1 ? 'Deactivate' : 'Activate';
                                    var mode = item.isActive == 1 ? 'deactivate' : 'active';
                                    var icon = item.isActive == 1 ? '<i class="fas fa-power-off"></i>' : '<i class="fas fa-toggle-on"></i>';
                                    action += doedit == true ? " <a class='" + buttonClass + "' onclick='activeDeactivate(" + item.id + ", \"" + mode + "\")'>" + icon + " " + iconClass + "</a>" : " ";
                                    action += dodelete == true ? " <a class='btn btn-danger' onclick='deleteRecord(" + item.id + ")'><i class='fas fa-trash-alt'></i> Delete</a>" : " ";
                                }
                                var newRow = [
                                    count++,
                                    item.name,
                                    action
                                ];
                                dataTable.row.add(newRow).draw();
                            });
                        }
                    },
                    error: function(error) {
                        console.error('Ajax request failed:', error);
                    }
                });
            };

            function activeDeactivate(id, mode) {
                $.ajax({
                    url: 'userTypeRights/activeDeactivate',
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        mode: mode,
                        id: id
                    },
                    success: function(response) {
                        if (response.ack == 1) {
                            toastr.success(response.Message);
                        } else {
                            toastr.error(response.Message);
                        }
                        loadTable();
                    },
                    error: function(xhr, status, error) {
                        toastr.error("Error occurred while processing the request.");
                    }
                });
            };

            $(document).ready(function() {
                loadTable();
            });
        </script>
        @include("system.includes.toastr")
    </body>
</html>
