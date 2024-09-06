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
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Manage Users</h1>
                            @if(session("authentication.rights.add_".$module_details['moduleId']) == 1 || session("authentication.user_type_id") == 1)
                                <a href="{{ route('users.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Users</a>
                            @endif
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width='5%'>No.</th>
                                                <th width='10%'>User Type</th>
                                                <th width='25%'>Name</th>
                                                <th width='10%'>email</th>
                                                <th width='10%'>mobile umber</th>
                                                <th width='10%'>user name</th>
                                                @if(session('authentication.rights.edit_'.$module_details['moduleId']) == 1 || session('authentication.rights.delete_'.$module_details['moduleId']) == 1 || session('authentication.user_type_id') == 1 || session('authentication.rights.view_3') == 1)
                                                    <th width='30%'>Action</th>
                                                @endif
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
        @include("system.includes.include_js")
        <script>

                function deleteRecord(id) {
                    $.ajax({
                        url: 'users/' + id,
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

                function activeDeactivate(id, mode) {
                    $.ajax({
                        url: 'users/activeDeactivate',
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

                function loadTable() {
                    $.ajax({
                        url: '{{ route("users.loadTable") }}',
                        method: 'POST',
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            var users_types = response.users_types;
                            var dataTable = $('#dataTable').DataTable();
                            var editRights = "{{ session('authentication.rights.edit_'.$module_details['moduleId']) }}";
                            var deleteRights = "{{ session('authentication.rights.delete_'.$module_details['moduleId']) }}";
                            var user_type_id = "{{ session('authentication.user_type_id') }}";
                            var doedit = false;
                            var dodelete = false;
                            var rightButton = false;

                            if(editRights == 1 || user_type_id == 1){
                                doedit = true;
                            }

                            if(deleteRights == 1 || user_type_id == 1){
                                dodelete = true;
                            }

                            if("{{ session('authentication.rights.view_3') }}" == 1 || user_type_id == 1){
                                rightButton = true;
                            }

                            // Clear existing rows before adding new ones
                            dataTable.clear().draw();
                            if (response.data_available) {
                                var count = 1; // Initialize count
                                $.each(response.users, function(index, item) {
                                    var buttonClass = item.isActive == 1 ? 'btn btn-danger' : 'btn btn-success';
                                    var iconClass = item.isActive == 1 ? '<i class="fas fa-power-off"></i> Deactivate' : '<i class="fas fa-toggle-on"></i> Activate';
                                    var mode = item.isActive == 1 ? 'deactivate' : 'active';
                                    var editButton = doedit == true ? "<a class='btn btn-success' href='/users/edit/" + item.id + "'><i class='fas fa-pencil-alt'></i></a> <a class='" + buttonClass + "' onclick='activeDeactivate(" + item.id + ", \"" + mode + "\")'>" + iconClass + "</a>" : " ";
                                    var deleteButton = dodelete == true ? " <a class='btn btn-danger' onclick='deleteRecord(" + item.id + ")'><i class='fas fa-trash-alt'></i></a> " : " ";
                                    var manageRights = rightButton == true ? " <a class='btn btn-info' href='userRights/manageRights/" + item.id + "' ><i class='fas fa-key'></i> Manage Rights</a>" : "";
                                    var newRow = [
                                        count++, // Increment count for each row
                                        users_types[item.user_type],
                                        item.name,
                                        item.email,
                                        item.mobile_number,
                                        item.user_name,
                                        editButton + deleteButton + manageRights
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

                $(document).ready(function() {
                    loadTable();
                });
        </script>
        @include("system.includes.toastr")
    </body>
</html>
