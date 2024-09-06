<!-- Bootstrap core JavaScript-->
<script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<!-- Core plugin JavaScript-->
<script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

<!-- Custom scripts for all pages-->
<script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

<!-- Page level plugins -->
<script src="{{ asset('assets/vendor/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>

<!-- Page level custom scripts -->
<script src="{{ asset('assets/js/demo/datatables-demo.js') }}"></script>

<!-- select2 -->
<script src="{{ asset('assets/select2/select2.min.js') }}"></script>

<!-- toastr -->
<script src="{{ asset('assets/toastr/toastr.min.js') }}"></script>

<script>

    function allowLimitedCharacters(event,limit) {
        const textarea = event.target;
        const maxLength = limit;

        if (textarea.value.length > maxLength) {
            textarea.value = textarea.value.slice(0, maxLength);
        }
    }

    function allowOnlyTenDigits(event) {
        const input = event.target;
        const inputValue = input.value.replace(/\D/g, '');
        const newValue = inputValue.slice(0, 10);

        input.value = newValue;


        if (inputValue.length >= 10) {
            event.preventDefault();
        }
    }

    function editProfile(userId) {
        $.ajax({
            url: '/users/edit-profile/'+userId,
            type: 'POST',
            data: {
                id: userId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                $('#eup_type').val(response.user.user_type);
                $('#eup_user_name').val(response.user.user_name);
                $('#eup_name').val(response.user.name);
                $('#eup_surname').val(response.user.surname);
                $('#eup_mobile_number').val(response.user.mobile_number);
                $('#eup_email').val(response.user.email);
                $('#eup_address').val(response.user.address);
                $('#eup_profile_picture').attr('src', response.user.profile_picture);
                $('#userProfileModel').modal('show');
            }
        });
    }

    function checkUserName(userName, id, event, fildId){

        allowLimitedCharacters(event,25);
        var defaultValue = "{{ isset($user) && !empty($user['user_name']) ? $user['user_name'] : '' }}";

        $.ajax({
            url: '{{ route("users.checkuserName") }}',
            method: 'POST',
            dataType: 'json',
            data: {
                user_name: userName,
                id: id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if(response.ack == 1){
                    $('#'+fildId).val(defaultValue);
                    toastr.error('Username not available!!!');
                }
            }
        });
    }

    function open_image_picker() {
        // Trigger the file input click
        document.getElementById('eup_imageInput').click();
    }

    function display_selected_image(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                // Display the selected image
                document.getElementById('eup_profile_picture').src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById("update_profile").addEventListener("click", function() {

            let eup_user_name = document.getElementById("eup_user_name").value;
            let eup_name = document.getElementById("eup_name").value;
            let eup_surname = document.getElementById("eup_surname").value;
            let eup_mobile_number = document.getElementById("eup_mobile_number").value;
            let eup_email = document.getElementById("eup_email").value;
            let eup_address = document.getElementById("eup_address").value;
            let eup_emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            document.getElementById('eup_user_nameError').innerText = '';
            document.getElementById('eup_nameError').innerText = '';
            document.getElementById('eup_surnameError').innerText = '';
            document.getElementById('eup_mobile_numberError').innerText = '';
            document.getElementById('eup_emailError').innerText = '';
            document.getElementById('eup_addressError').innerText = '';

            if (eup_user_name === '') {
                document.getElementById('eup_user_nameError').innerText = 'User Name is required';
                return false;
            }else if (eup_name === '') {
                document.getElementById('eup_nameError').innerText = 'Name is required';
                return false;
            }else if (eup_surname === '') {
                document.getElementById('eup_surnameError').innerText = 'Surname is required';
                return false;
            }else if (eup_mobile_number === '') {
                document.getElementById('eup_mobile_numberError').innerText = 'Mobile Number is required';
                return false;
            }else if (eup_email === '') {
                document.getElementById('eup_emailError').innerText = 'Email is required';
                return false;
            }else if (!eup_emailPattern.test(eup_email)) {
                document.getElementById('eup_emailError').innerText = 'Please enter a valid email address.';
                return false;
            }else if (eup_address === '') {
                document.getElementById('eup_addressError').innerText = 'Address is required';
                return false;
            }


            // Gather form data
            let formData = new FormData();
            formData.append("_token", "{{ csrf_token() }}"); // Include CSRF token
            formData.append("user_name", document.getElementById("eup_user_name").value);
            formData.append("name", document.getElementById("eup_name").value);
            formData.append("surname", document.getElementById("eup_surname").value);
            formData.append("mobile_number", document.getElementById("eup_mobile_number").value);
            formData.append("email", document.getElementById("eup_email").value);
            formData.append("address", document.getElementById("eup_address").value);

            // Check if image file is selected
            let fileInput = document.getElementById("eup_imageInput");
            if (fileInput.files.length > 0) {
                let file = fileInput.files[0];
                let fileExtension = file.name.split('.').pop().toLowerCase();

                // Check file extension
                if (['jpeg', 'jpg', 'png'].includes(fileExtension)) {
                    formData.append("profile_picture", file);
                } else {
                    alert("Please select a valid image file with extension jpeg, jpg, or png.");
                    return; // Exit function if file extension is invalid
                }
            }

            // Get the user ID from the session and construct the URL
            let userId = "{{ session('authentication.user_id') }}";
            let url = `/users/update-profile/${userId}`;
            // Send data via AJAX
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token in headers
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if(data.ack == 1){
                    toastr.success(data.Message);
                }else{
                    toastr.error(data.Message);
                }
                location.reload();
                $('#userProfileModel').modal('hide');
            })
            .catch(error => {
                console.error('Error:', error);
                // Handle error
            });
        });
    });


</script>
