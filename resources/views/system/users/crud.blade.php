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
        <style>
            .profile-picture-container {
                display: flex;
                justify-content: center;
                align-items: center;
                /* height: 100vh; Adjust as needed based on your layout */
            }

            .profile-picture {
                width: 300px;
                height: 300px;
                object-fit: cover;
                border-radius: 50%;
                border: 2px solid #555;
            }
        </style>

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
                            <div class="col-lg-8">

                                <!-- Custom Text Color Utilities -->
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">User Profile</h6>
                                    </div>

                                    <div class="card-body">
                                        <form action="{{ isset($user) && $mode == 'edit' ? route('users.update',['id' => $user['id']]) : route('users.insert') }}" method="post" onsubmit="return validateForm()" enctype="multipart/form-data">
                                            @csrf
                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="profile-picture-container">
                                                        <img id="profile_picture" class="profile-picture" name="profile_picture" src="{{ isset($user) ? $user['profile_picture'] : asset('images/default-user.png') }}" alt="Profile Picture" onclick="openImagePicker()">
                                                    </div>
                                                    <br><br>
                                                    <input type="file" id="imageInput" class="d-none" name="profile_picture" onchange="displaySelectedImage(this)">
                                                    <button class="form-control btn btn-outline-primary" onclick="openImagePicker()">Select Image</button>
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="form-row">
                                                        <div class="col-md-6">
                                                            <label for="user_type">Type</label>
                                                            <select class="form-control" id="user_type" name="user_type">
                                                                <option value="">Select user Type</option>
                                                                @foreach($user_types as $user_type)
                                                                    <option {{ isset($user) && $user['user_type'] == $user_type['id'] ? 'selected' : '' }} value="{{ $user_type['id'] }}">{{ $user_type['name'] }}</option>
                                                                @endforeach
                                                            </select>
                                                            <small id="user_typeError" class="text-danger"></small>
                                                        </div>
                                                        <div class="col-md-6"></div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="name">First Name</label>
                                                                <input type="text" class="form-control" oninput="allowLimitedCharacters(event,90)" id="name" name="name" value="{{ isset($user) && $user['name'] != '' ? $user['name'] : '' }}">
                                                                <small id="nameError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="surname">Surname</label>
                                                                <input type="text" class="form-control" oninput="allowLimitedCharacters(event,90)" id="surname" name="surname" value="{{ isset($user) && $user['surname'] != '' ? $user['surname'] : '' }}">
                                                                <small id="surnameError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="mobile_number">Mobile Number</label>
                                                                <input type="text" value="{{ isset($user) && $user['mobile_number'] != '' ? $user['mobile_number'] : '' }}" class="form-control" id="mobile_number" name="mobile_number" oninput="allowOnlyTenDigits(event)">
                                                                <small id="mobile_numberError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="email">Email Address</label>
                                                                <input type="text" value="{{ isset($user) && $user['email'] != '' ? $user['email'] : '' }}" class="form-control" id="email" name="email" oninput="allowLimitedCharacters(event,45)">
                                                                <small id="emailError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="address">Address</label>
                                                                <textarea class="form-control" id="address" name="address" rows="3" oninput="allowLimitedCharacters(event,150)">
                                                                    {{ isset($user) && $user['address'] != '' ? $user['address'] : '' }}
                                                                </textarea>
                                                                <small id="addressError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="user_name">User Name</label>
                                                                <input type="text" value="{{ isset($user) && !empty($user['user_name']) ? $user['user_name'] : '' }}" class="form-control" id="user_name" name="user_name" oninput="checkUserName(this.value,{{ isset($user['id']) && $user['id'] != '' ? $user['id'] : 0 }},event,'user_name')">
                                                                <small id="user_nameError" class="text-danger"></small>
                                                            </div>
                                                        </div>
                                                        @if($mode == 'add')
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="password">Password</label>
                                                                    <div class="input-group">
                                                                        <input type="password" value="{{ isset($module) && $module['password'] != '' ? $module['password'] : '' }}" class="form-control" id="password" name="password" oninput="allowAlphaNumericAfterEight(event)">
                                                                        <div class="input-group-append">
                                                                            <button class="password-toggle-btn btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')"><i class="fas fa-eye-slash"></i></button>
                                                                        </div>
                                                                    </div>
                                                                    <small id="passwordError" class="text-danger">maximum 8 characters</small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <!-- Add more form fields as needed -->
                                                </div>
                                            </div>
                                            <div class="form-group form-check">
                                                <div class="d-flex justify-content-end">
                                                    <a href="{{ route('users') }}" class="btn btn-warning mr-2"><i class="fas fa-undo"></i> Back</a>
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

            var isImageSeleted = false;

            function openImagePicker() {
                // Trigger the file input click
                document.getElementById('imageInput').click();
            }

            function displaySelectedImage(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        // Display the selected image
                        document.getElementById('profile_picture').src = e.target.result;
                    };
                    reader.readAsDataURL(input.files[0]);
                    isImageSeleted = true;
                }
            }

            $('#user_type').select2();

            function togglePasswordVisibility(passwordFieldId) {
                const passwordField = document.getElementById(passwordFieldId);
                const fieldType = passwordField.type;

                passwordField.type = fieldType === 'password' ? 'text' : 'password';
                // Toggle eye icon between open and closed
                const eyeIcon = document.querySelector('.password-toggle-btn i');
                if (fieldType === 'password') {
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
                }
            }

            function allowAlphaNumericAfterEight(event) {
                const input = event.target;
                let inputValue = input.value;

                if (inputValue.length >= 8) {

                    event.preventDefault();
                }


                inputValue = inputValue.replace(/[^a-zA-Z0-9!@#$%^&*()_+{}\[\]:;<>,.?~\\-]/g, '');


                inputValue = inputValue.slice(0, 8);

                input.value = inputValue;
            }

            function validateForm() {
                var mode = '{{ $mode }}';
                const imageInput = document.getElementById('imageInput');
                const user_type = document.getElementById('user_type').value.trim();
                const name = document.getElementById('name').value.trim();
                const surname = document.getElementById('surname').value.trim();
                const mobile_number = document.getElementById('mobile_number').value.trim();
                const email = document.getElementById('email').value.trim();
                const address = document.getElementById('address').value.trim();
                const userName = document.getElementById('user_name').value.trim();

                document.getElementById('user_typeError').innerText = '';
                document.getElementById('nameError').innerText = '';
                document.getElementById('surnameError').innerText = '';
                document.getElementById('mobile_numberError').innerText = '';
                document.getElementById('emailError').innerText = '';
                document.getElementById('addressError').innerText = '';
                document.getElementById('user_nameError').innerText = '';

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                const allowedExtensions = ['jpeg', 'jpg', 'png'];
                let fileName = '';
                let fileExtension = '';
                var old_img_path = '{{ isset($user) && $user["profile_picture"] != app("default_image") ? $user["profile_picture"] : "" }}';

                if (imageInput.files.length > 0 && isImageSeleted === true) {
                    fileName = imageInput.files[0].name.toLowerCase();
                    fileExtension = fileName.split('.').pop();
                }

                if (!imageInput.files || imageInput.files.length === 0 && old_img_path == '') {
                    toastr.error('Image is required');
                    return false;
                } else if (!allowedExtensions.includes(fileExtension) && isImageSeleted === true) {
                    toastr.error('Please upload a valid image file (JPEG, JPG, or PNG).');
                    return false;
                }

                if (user_type === '') {
                    document.getElementById('user_typeError').innerText = 'Member Type is required';
                    return false;
                }

                if (name === '') {
                    document.getElementById('nameError').innerText = 'First Name is required';
                    return false;
                }

                if (surname === '') {
                    document.getElementById('surnameError').innerText = 'Surname is required';
                    return false;
                }

                if (mobile_number === '') {
                    document.getElementById('mobile_numberError').innerText = 'Mobile Number is required';
                    return false;
                }

                if (email === '') {
                    document.getElementById('emailError').innerText = 'Email is required';
                    return false;
                }

                if (!emailRegex.test(email)) {
                    document.getElementById('emailError').innerText = 'Please enter a valid email address.';
                    return false;
                }

                if (address === '') {
                    document.getElementById('addressError').innerText = 'Address is required';
                    return false;
                }

                if (userName === '') {
                    document.getElementById('user_nameError').innerText = 'User Name is required';
                    return false;
                }

                if (mode === 'add') {
                    const password = document.getElementById('password').value.trim();

                    if (password === '') {
                        document.getElementById('passwordError').innerText = 'Password is required';
                        return false;
                    } else if (password.length > 8) {
                        document.getElementById('passwordError').innerText = 'Password must be maximum 8 characters';
                        return false;
                    }
                }

                toastr.success('Form submitted successfully!');
                return true;
            }


        </script>

        @include("system.includes.toastr")
    </body>

</html>
