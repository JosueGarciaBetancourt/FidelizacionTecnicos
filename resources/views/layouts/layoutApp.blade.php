<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Club de Técnicos | @yield('title')</title>
    <link rel="icon" href="{{ asset('images/mainIcon.ico') }}" type="image/ico">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Source+Sans+3:wght@300;400;600;700&display=swap">
    <link rel="stylesheet" href="{{ asset('css/appStyles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" 
    crossorigin="anonymous" 
    referrerpolicy="no-referrer"/>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    @stack('styles')
</head>
<body>
    <header>
        @yield('header')
    </header>

    <main>
		@yield('content')
    </main>

    <footer>
        @yield('footer')
    </footer>

    @stack('scripts')
    <script src="{{ asset('js/tooltip.js') }}"> </script>
    <script>
        // Aplicar configuración al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            if (localStorage.getItem('darkMode') === 'true') {
                document.body.classList.add('dark-mode');
            }
            document.documentElement.classList.remove('font-small', 'font-medium', 'font-large');
            document.documentElement.classList.add(`font-${localStorage.getItem('fontSize') || 'medium'}`);
            const activeLink = document.querySelector('.sidebar a.active');
            if (activeLink) {
                activeLink.style.backgroundColor = localStorage.getItem('sidebarColor') || '#007bff';
            }
        });
    </script>
</body>
</html>