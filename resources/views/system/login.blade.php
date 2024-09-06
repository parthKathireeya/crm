<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Login</title>

    @include("system.includes.include_css")

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                    </div>
                                    <form action="{{ route('do_login') }}" method="post" onsubmit="return validateForm()">
                                        @csrf
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user" id="user_name" name="user_name" placeholder="Enter User Name...">
                                        </div>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="password" value="{{ isset($module) && $module['password'] != '' ? $module['password'] : '' }}" class="form-control form-control-user" id="password" name="password" oninput="allowAlphaNumericAfterEight(event)" placeholder="password">
                                                <div class="input-group-append">
                                                    <button class="password-toggle-btn btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('password')"><i class="fas fa-eye-slash"></i></button>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    @include("system.includes.include_js")
    @include("system.includes.toastr")
    <script>
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
    </script>
</body>

</html>
