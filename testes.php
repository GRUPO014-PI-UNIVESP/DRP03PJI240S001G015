<script>
        function openLightBox() {
            var btn = document.querySelector("a.btn");
            btn.click();
         }
         window.addEventListener('beforeunload', function (e) {
            e.preventDefault();
            openLightBox();
            e.returnValue = 'Are you sure you want to leave?';
         });
</script>