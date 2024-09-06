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

        <!-- Page Wrapper -->
        <div id="wrapper">

            @include("system.includes.sidebar")

            <!-- Content Wrapper -->
            <div id="content-wrapper" class="d-flex flex-column">

                <!-- Main Content -->
                <div id="content">

                    @include("system.includes.navbar")

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <h1 class="h3 mb-1 text-gray-800">Color Utilities</h1>
                        <p class="mb-4">Bootstrap's default utility classes can be found on the official <a href="https://getbootstrap.com/docs">Bootstrap Documentation</a> page. below were created to extend this theme past the default utility classes built into Bootstrap's framework.</p>


                        <!-- Content Row -->
                        <div class="row">
                            <!-- First Column -->
                            <div class="col-lg-6">

                                <!-- Custom Text Color Utilities -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Custom Text Color Utilities</h6>
                                    </div>

                                    <div class="card-body">
                                        <form action="{{ isset($module) && $module['id'] && $mode == 'edit' ? route('modules.update', ['id' => $module['id']]) : route('modules.insert') }}" method="post" onsubmit="return validateForm()">
                                            @csrf
                                            <div class="form-group">
                                                <label for="type">Type</label>
                                                <select class="form-control" id="type" name="type">
                                                    <option value="">Select Module Type</option>
                                                    @foreach($project_module_type as $type)
                                                        <option {{ isset($module) && $module['type'] == $type['id'] ? 'selected' : '' }} value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                                    @endforeach
                                                </select>
                                                <small id="typeError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" value="{{ isset($module) && $module['name'] != '' ? $module['name'] : '' }}" class="form-control" id="name" name="name" oninput="allowLimitedCharacters(event,45)">
                                                <small id="nameError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="icon_class">Icon Class</label>
                                                <input type="text" value="{{ isset($module) && $module['icon_class'] != '' ? $module['icon_class'] : '' }}" class="form-control" id="icon_class" name="icon_class" oninput="allowLimitedCharacters(event,45)">
                                                <small id="icon_classError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="slug">Slug</label>
                                                <input type="text" onkeydown="blockSpace(event)" value="{{ isset($module) && $module['slug'] != '' ? $module['slug'] : '' }}" class="form-control" id="slug" name="slug" oninput="allowLimitedCharacters(event,45)">
                                                <small id="slugError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="url">URL</label>
                                                <input type="text" value="{{ isset($module) && $module['url'] != '' ? $module['url'] : '' }}" class="form-control" id="url" name="url" oninput="allowLimitedCharacters(event,190)">
                                                <small id="urlError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" id="description" name="description" rows="3" oninput="allowLimitedCharacters(event,240)">
                                                    {{ isset($module) && $module['description'] != '' ? $module['description'] : '' }}
                                                </textarea>
                                                <small id="descriptionError" class="text-danger"></small>
                                            </div>
                                            <div class="form-group form-check">
                                                <input type="checkbox" {{ isset($module) && $module['isShow'] == 1 ? 'checked' : '' }} class="form-check-input" id="isShow" name="isShow">
                                                <label class="form-check-label" for="isShow">Show as Module</label>
                                                <small id="isShowError" class="text-danger"></small>
                                                <div class="align-items-right d-flex justify-content-end mb-4">
                                                    <a href="{{ route('modules') }}" class="btn btn-warning mr-2"><i class="fas fa-undo"></i> Back</a>
                                                    <button type="submit" class="btn btn-info"><i class="fas fa-check"></i> Submit</button>
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

                <!-- Footer -->
                <footer class="sticky-footer bg-white">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright &copy; Your Website 2020</span>
                        </div>
                    </div>
                </footer>
                <!-- End of Footer -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        @include("system.includes.include_js")

        <script>

            $('#type').select2();

            function allowLimitedCharacters(event,limit) {
                const textarea = event.target;
                const maxLength = limit;

                if (textarea.value.length > maxLength) {
                    textarea.value = textarea.value.slice(0, maxLength);
                }
            }

            function blockSpace(event) {
                if (event.keyCode === 32) {
                    event.preventDefault();
                    document.getElementById("slugError").innerText = "Spaces are not allowed.";
                } else {
                    document.getElementById("slugError").innerText = "";
                }
            }

            function validateForm() {

                const type = document.getElementById('type').value.trim();
                const name = document.getElementById('name').value.trim();
                const slug = document.getElementById('slug').value.trim();
                const url = document.getElementById('url').value.trim();
                const icon_class = document.getElementById('icon_class').value.trim();

                document.getElementById('typeError').innerText = '';
                document.getElementById('nameError').innerText = '';
                document.getElementById('slugError').innerText = '';
                document.getElementById('urlError').innerText = '';
                document.getElementById('icon_classError').innerText = '';

                var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (type === '') {
                    document.getElementById('typeError').innerText = 'Module Type required';
                    return false;
                }

                if (name === '') {
                    document.getElementById('nameError').innerText = 'First Name is required';
                    return false;
                }

                if (icon_class === '') {
                    document.getElementById('icon_classError').innerText = 'Icon Class is required';
                    return false;
                }

                if (slug === '') {
                    document.getElementById('slugError').innerText = 'slug is required';
                    return false;
                }

                if (url === '') {
                    document.getElementById('urlError').innerText = 'url is required';
                    return false;
                }

                toastr.success('Form submitted successfully!');
                return true;
            }

        </script>
         @include("system.includes.toastr")
    </body>

</html>
