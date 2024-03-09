@vite('resources/admin/js/app.js')
@yield('js')
<script src="https://kit.fontawesome.com/1e21adaaa9.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        var scrollpos = sessionStorage.getItem('scrollpos');
        if (scrollpos) {
            document.getElementById('app_content').scrollTo(0, scrollpos);
            sessionStorage.removeItem('scrollpos');
        }
    });

    window.addEventListener("beforeunload", function(e) {
        sessionStorage.setItem('scrollpos', document.getElementById('app_content').scrollTop);
    });
</script>
