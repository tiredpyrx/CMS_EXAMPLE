@vite('resources/admin/js/app.js')
<script src="https://kit.fontawesome.com/1e21adaaa9.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        let scrollPosition = sessionStorage.getItem('app_last_scroll_position');
        if (scrollPosition) {
            document.getElementById('app_content').scrollTo(0, scrollPosition);
            sessionStorage.removeItem('app_last_scroll_position');
        }
    });

    window.addEventListener("beforeunload", function(e) {
        sessionStorage.setItem('app_last_scroll_position', document.getElementById('app_content').scrollTop);
    });
</script>
