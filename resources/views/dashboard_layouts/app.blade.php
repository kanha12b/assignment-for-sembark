<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dashboard - @yield('title')</title>

    <!-- Add Bootstrap CSS or your CSS here -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
    <div class="container my-4">
        @yield('content')
    </div>

    
    <!-- Bootstrap JS bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Get all modals on the page
    var modals = document.querySelectorAll('.modal');

    modals.forEach(function(modal) {
        var form = modal.querySelector('form');

        if (!form) return; // skip modals without forms

        // 1️⃣ Reset form before modal opens
        modal.addEventListener('show.bs.modal', function () {
            form.reset();
        });

        // 2️⃣ Reset form when modal closes
        modal.addEventListener('hidden.bs.modal', function () {
            form.reset();
        });
    });
});
</script>

    
</body>
</html>
