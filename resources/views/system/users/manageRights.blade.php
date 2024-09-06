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
                        <!-- Page Heading -->
                        <div class="d-flex justify-content-end mb-4">
                            <div>
                                @if(session('authentication.rights.add_'.$module_details['moduleId']) == 1 || session('authentication.rights.edit_'.$module_details['moduleId']) == 1 || session('authentication.user_type_id') == 1)
                                    <a id="submitButton" class="btn btn-sm btn-primary ml-auto"><i class="{{ isset($rights_id) ? 'fas fa-edit' : 'fas fa-plus' }}"></i> {{ isset($rights_id) ? 'Change Rights' : 'Assign Rights' }}</a>
                                @endif
                                @if(isset($isActive) && isset($rights_id))
                                    @if(session('authentication.rights.delete_'.$module_details['moduleId']) == 1 || session('authentication.user_type_id') == 1)
                                    <a class="btn btn-sm btn-danger ml-auto" href="/userRights/delete/{{ $rights_id }}"><i class='fas fa-trash-alt'></i> Delete</a>
                                    @endif
                                    @if(session('authentication.rights.edit_'.$module_details['moduleId']) == 1 || session('authentication.user_type_id') == 1)
                                        <a onclick='activeDeactivate("{{ $rights_id }}", "{{ $isActive["mode"] }}")' class="btn-sm {{ $isActive["buttonClass"] }}"><i class="{{ $isActive['icon'] }}"></i> {{ $isActive["iconClass"] }}</a>
                                    @endif
                                @endif
                            </div>
                        </div>


                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <h6 class="m-0 font-weight-bold text-primary">{{ 'Manage rights for '. $user_name }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th width='6%'>No.</th>
                                                <th>Module Name</th>
                                                <th class='view_th' width='20%'>View Right</th>
                                                <th width='15%'>Add Right</th>
                                                <th width='15%'>Edit Right</th>
                                                <th width='15%'>Delete Right</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $count = 1; ?>
                                            @foreach($modules as $module)
                                            <tr>
                                                <td>{{ $count++ }}</td>
                                                <td>
                                                    <input type="hidden" id="module_id" name="module_id" value="{{ $module['id'] }}">
                                                    {{ $module['name'] }}
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <!-- Checkbox -->
                                                        <div class="custom-control custom-checkbox form-check-lg">
                                                            <input class="custom-control-input view" type="checkbox" onclick="handleViewCheckboxClick(this)" data-moduleid="{{ $module['id'] }}" name="view_{{ $module['id'] }}" id="view_{{ $module['id'] }}" {{ isset($rights['view_'.$module['id']]) && $rights['view_'.$module['id']] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="view_{{ $module['id'] }}">View</label>
                                                        </div>
                                                        @if(isset($rights['view_'.$module['id']]) && $rights['view_'.$module['id']] == 1)
                                                        <div class="form-group form-group-radio">
                                                            <div class="custom-control custom-radio d-inline-block mr-3">
                                                                <input type="radio" id="view_all_{{ $module['id'] }}" name="view_type_{{ $module['id'] }}" class="custom-control-input view" value="1" {{ $rights['view_type_'.$module['id']] == 1 ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="view_all_{{ $module['id'] }}">All</label>
                                                            </div>
                                                            <div class="custom-control custom-radio d-inline-block mr-3">
                                                                <input type="radio" id="view_chain_{{ $module['id'] }}" name="view_type_{{ $module['id'] }}" class="custom-control-input view" value="2" {{ $rights['view_type_'.$module['id']] == 2 ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="view_chain_{{ $module['id'] }}">Chain Wise</label>
                                                            </div>
                                                            <div class="custom-control custom-radio d-inline-block">
                                                                <input type="radio" id="view_personal_{{ $module['id'] }}" name="view_type_{{ $module['id'] }}" class="custom-control-input view" value="3" {{ !isset($rights['view_type_'.$module['id']]) || $rights['view_type_'.$module['id']] == 3 ? 'checked' : '' }}>
                                                                <label class="custom-control-label" for="view_personal_{{ $module['id'] }}">Personal</label>
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox form-check-lg">
                                                            <input class="custom-control-input view" type="checkbox" name="add_{{ $module['id'] }}" id="add_{{ $module['id'] }}" {{ isset($rights['add_'.$module['id']]) && $rights['add_'.$module['id']] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="add_{{ $module['id'] }}">Add</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox form-check-lg">
                                                            <input class="custom-control-input view" type="checkbox" name="edit_{{ $module['id'] }}" id="edit_{{ $module['id'] }}" {{ isset($rights['edit_'.$module['id']]) && $rights['edit_'.$module['id']] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="edit_{{ $module['id'] }}">Edit</label>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox form-check-lg">
                                                            <input class="custom-control-input view" type="checkbox" name="delete_{{ $module['id'] }}" id="delete_{{ $module['id'] }}" {{ isset($rights['delete_'.$module['id']]) && $rights['delete_'.$module['id']] == 1 ? 'checked' : '' }}>
                                                            <label class="custom-control-label" for="delete_{{ $module['id'] }}">Delete</label>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
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
            var table = $('#dataTable').DataTable();
            // Function to handle view checkbox click
            window.handleViewCheckboxClick = function(checkbox) {
                var moduleId = $(checkbox).data('moduleid');
                var isChecked = $(checkbox).prop('checked');
                var $viewTh = table.cell($(checkbox).closest('td, th')).node();

                // Update width of view_th based on checkbox state
                if (isChecked) {
                    // Add radio buttons in the same TD if not already added
                    if ($($viewTh).find('.form-group-radio').length === 0) {
                        $($viewTh).html(`
                            <div class="custom-control custom-checkbox form-check-lg">
                                <input class="custom-control-input view" type="checkbox" checked onclick="handleViewCheckboxClick(this)" data-moduleid="${moduleId}" name="view_${moduleId}" id="view_${moduleId}">
                                <label class="custom-control-label" for="view_${moduleId}">View</label>
                            </div>
                            <div class="form-group form-group-radio">
                                <div class="custom-control custom-radio d-inline-block mr-3">
                                    <input type="radio" id="view_all_${moduleId}" name="view_type_${moduleId}" class="custom-control-input view" value="1">
                                    <label class="custom-control-label" for="view_all_${moduleId}">All</label>
                                </div>
                                <div class="custom-control custom-radio d-inline-block mr-3">
                                    <input type="radio" id="view_chain_${moduleId}" name="view_type_${moduleId}" class="custom-control-input view" value="2">
                                    <label class="custom-control-label" for="view_chain_${moduleId}">Chain Wise</label>
                                </div>
                                <div class="custom-control custom-radio d-inline-block">
                                    <input type="radio" id="view_personal_${moduleId}" checked name="view_type_${moduleId}" class="custom-control-input view" value="3">
                                    <label class="custom-control-label" for="view_personal_${moduleId}">Personal</label>
                                </div>
                            </div>
                        `);
                    }
                } else {
                    $($viewTh).find('.form-group-radio').remove();
                }
                // manageWidth();
            };

            function getTableData() {
                var tableData = {};
                $('#dataTable tbody tr').each(function() {
                    var moduleId = $(this).find('td:nth-child(2) input[type="hidden"]').val();
                    var rowData = {};
                    rowData['view_' + moduleId] = $(this).find('td:nth-child(3) input[type="checkbox"]').is(':checked') ? 1 : 0;
                    if ($(this).find('td:nth-child(3) input[type="checkbox"]').is(':checked')) {
                        rowData['view_type_' + moduleId] = $(this).find('td:nth-child(3) input[type="radio"]:checked').val();
                    }
                    rowData['add_' + moduleId] = $(this).find('td:nth-child(4) input[type="checkbox"]').is(':checked') ? 1 : 0;
                    rowData['edit_' + moduleId] = $(this).find('td:nth-child(5) input[type="checkbox"]').is(':checked') ? 1 : 0;
                    rowData['delete_' + moduleId] = $(this).find('td:nth-child(6) input[type="checkbox"]').is(':checked') ? 1 : 0;
                    $.extend(tableData, rowData);
                });
                return tableData;
            }


            // Handle submit button click
            $('#submitButton').click(function() {
                var tableData = getTableData();
                $.ajax({
                    url: '{{ route("userRights.changeRights", ["id" => $userId]) }}',
                    type: 'POST',
                    data: { rights: JSON.stringify(tableData) },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error response
                        console.error(xhr.responseText);
                    }
                });
            });

            function activeDeactivate(id, mode) {
                $.ajax({
                    url: '/userRights/activeDeactivate',
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
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        toastr.error("Error occurred while processing the request.");
                    }
                });
            };

            // $(document).ready(function() {});
        </script>
    </body>
</html>
