<script>
    function togglePassword() {
        let fields = [
            document.getElementById("current_password"),
            document.getElementById("new_password"),
            document.getElementById("confirm_password")
        ];

        fields.forEach(function (field) {
            if (field.type === "password") {
                field.type = "text";
            } else {
                field.type = "password";
            }
        });
    }
</script>

</body>

</html>