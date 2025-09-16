<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.head')
</head>
<body class="min-h-screen flex items-center justify-center">
    {{ $slot }}


@livewireScripts
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    // Initialize AOS normally
    document.addEventListener('DOMContentLoaded', () => {
        AOS.init({
            duration: 800,
            once: true,
        });
    });
    
</script>
</body>
</html>

</html>
