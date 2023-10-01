function showpass() {
    const icon = document.getElementById('showPassIcon');
    const pass = document.getElementById("pass");
    
    if (pass.type === "password") {
        pass.type = "text";
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        pass.type = "password";
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}