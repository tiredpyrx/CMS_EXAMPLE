<div class="hidden">
    <button id="session-alert-button" data-type="" data-message=""
        onclick='toastr[this.dataset.type](this.dataset.message)'></button>
</div>
@if (session('success'))
    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            let btn = document.getElementById('session-alert-button')
            btn.setAttribute('data-type', 'success');
            btn.setAttribute('data-message', '{{ session('success') }}');
            btn.click();
        })
    </script>
@endif
@if (session('error'))
    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            let btn = document.getElementById('session-alert-button')
            btn.setAttribute('data-type', 'error');
            btn.setAttribute('data-message', '{{ session('error') }}');
            btn.click();
        })
    </script>
@endif
@if (session('warning'))
    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            let btn = document.getElementById('session-alert-button')
            btn.setAttribute('data-type', 'warning');
            btn.setAttribute('data-message', '{{ session('warning') }}');
            btn.click();
        })
    </script>
@endif
@if (session('info'))
    <script defer>
        document.addEventListener("DOMContentLoaded", () => {
            let btn = document.getElementById('session-alert-button')
            btn.setAttribute('data-type', 'info');
            btn.setAttribute('data-message', '{{ session('info') }}');
            btn.click();
        })
    </script>
@endif