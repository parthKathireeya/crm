<style>
    .toast {
        position: fixed;
        top: 60px; /* Adjusted top position */
        right: 10px;
    }
</style>
<script>

    toastr.options = {
        "closeButton": true,
        "progressBar": true
    };

</script>
@if(session('toastr_success'))
    <script>
        toastr.success("{{ session('toastr_success') }}");
    </script>
@endif

@if(session('toastr_error'))
    <script>
        toastr.error("{{ session('toastr_error') }}");
    </script>
@endif
