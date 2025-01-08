// Optional JavaScript for additional validation or functionality
document.getElementById('registrationForm').addEventListener('submit', function(event) {
    let name = document.getElementById('name').value;
    let email = document.getElementById('email').value;
    let contact = document.getElementById('contact').value;
    let date = document.getElementById('date').value;

    if (!name || !email || !contact || !date) {
        alert("All fields are required.");
        event.preventDefault(); // Prevent form submission if validation fails
    }
});
