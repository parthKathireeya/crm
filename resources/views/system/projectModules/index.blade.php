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
                        <!-- <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <a href="{{ route('modules.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Modules</a>
                        </div> -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Manage Project Modules</h1>
                            @if(session("authentication.rights.add_".$module_details['moduleId']) == 1 || session("authentication.user_type_id") == 1)
                                <a href="{{ route('modules.add') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Add Module</a>
                            @endif
                        </div>
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width='5%'>No.</th>
                                                <th>Module Name</th>
                                                <th width='15%'>Slug</th>
                                                <th width='10%'>Show No</th>
                                                <th width='15%'>Action</th>
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
        @include("system.includes.toastr")
        <script>

            function deleteRecord(id) {
                $.ajax({
                    url: 'modules/' + id,
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

            function changeShowNo(id, valus) {
                $.ajax({
                    url: '{{ route("modules.change_showNo") }}',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        show_no: valus,
                        id: id
                    },
                    success: function(response) {
                        if(response.ack == 1){
                            toastr.success(response.Message);
                        }else{
                            toastr.error(response.Message);
                        }
                    }
                });
            };

            function loadTable() {
                $.ajax({
                    url: '{{ route("modules.loadTable") }}',
                    method: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success && response.data_available) {
                            var dataTable = $('#dataTable').DataTable();
                            var users_types = response.users_types;
                            var editRights = "{{ session('authentication.rights.edit_'.$module_details['moduleId']) }}";
                            var deleteRights = "{{ session('authentication.rights.delete_'.$module_details['moduleId']) }}";
                            var user_type_id = "{{ session('authentication.user_type_id') }}";
                            var doedit = false;
                            var dodelete = false;

                            if(editRights == 1 || user_type_id == 1){
                                doedit = true;
                            }

                            if(deleteRights == 1 || user_type_id == 1){
                                dodelete = true;
                            }

                            // Clear existing rows before adding new ones
                            dataTable.clear().draw();

                            var count = 1; // Initialize count
                            $.each(response.data, function(index, item) {
                                var show_no = item.show_no != null ? item.show_no : '';
                                var editButton = doedit == true ? "<a class='btn btn-success' href='/modules/edit/" + item.id + "'><i class='fas fa-pencil-alt'></i></a> " : " ";
                                var deleteButton = dodelete == true ? "<a class='btn btn-danger' onclick='deleteRecord(" + item.id + ")' ><i class='fas fa-trash-alt'></i></a> " : " ";
                                var newRow = [
                                    count++, // Increment count for each row
                                    '<a href = "{{url('/')}}/' + item.url + '">' + item.name + '</a>',
                                    item.slug,
                                    '<input type="text" value="' + show_no + '" class="form-control" onchange="changeShowNo(' + item.id + ',this.value)">',
                                    editButton + deleteButton + "<a class='btn btn-warning' onclick='copyModuleData(" + JSON.stringify(item) + ")'><i class='fas fa-copy'></i></a>"
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

            function copyModuleData(data) {
                var phpArray = "array('moduleId' => " + data.id + ", 'moduleName' => '" + data.name + "', 'moduleSlug' => '" + data.slug + "', 'moduleUrl' => '{{url('/')}}/" + data.url + "')";
                navigator.clipboard.writeText(phpArray);

                toastr.success('Module data copied successfully!');
            };

            $(document).ready(function() {
                loadTable();
            });
        </script>
        @include("system.includes.toastr")
    </body>
</html>
