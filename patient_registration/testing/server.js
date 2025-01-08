const express = require('express');
const mysql = require('mysql2');
const bodyParser = require('body-parser');

const app = express();
const port = 3000;

// Middleware to parse JSON
app.use(bodyParser.json());

// MySQL connection setup
const db = mysql.createConnection({
    host: 'localhost',
    user: 'root',  // Replace with your MySQL username
    password: '',  // Replace with your MySQL password
    database: 'patientdb'  // Your database name
});

db.connect((err) => {
    if (err) {
        console.error('Error connecting to the database: ' + err.stack);
        return;
    }
    console.log('Connected to MySQL database');
});

// Serve static files (HTML, CSS, JS)
app.use(express.static('public'));

// Route for handling form submission
app.post('/submit-form', (req, res) => {
    const { name, email, contact, date } = req.body;

    // SQL query to insert form data into the database
    const query = 'INSERT INTO patients (fullName, email, contact, consultationDate) VALUES (?, ?, ?, ?)';
    
    db.query(query, [name, email, contact, date], (err, result) => {
        if (err) {
            console.error('Error inserting data into database:', err);
            res.status(500).json({ message: 'Error saving data' });
            return;
        }
        res.status(200).json({ message: 'Patient information submitted successfully!' });
    });
});

// Start the server
app.listen(port, () => {
    console.log(`Server is running on http://localhost:${port}`);
});
