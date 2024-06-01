const express = require('express');
const bodyParser = require('body-parser');
const mongoose = require('mongoose');

const app = express();
const PORT = 3000;

// Connect to MongoDB (make sure MongoDB is running)
mongoose.connect('mongodb://localhost:27017/studentDashboard', { useNewUrlParser: true, useUnifiedTopology: true });
const db = mongoose.connection;

// Define MongoDB schema and model
const studentSchema = new mongoose.Schema({
    name: String,
    regNo: String,
    academicYear: String,
    region: String,
    district: String,
    organization: String,
    supervisorName: String,
    supervisorNo: String,
});

const logbookSchema = new mongoose.Schema({
    date: Date,
    workspace: String,
});

const reportSchema = new mongoose.Schema({
    weekNo: String,
    work: String,
    problems: String,
});

const Student = mongoose.model('Student', studentSchema);
const Logbook = mongoose.model('Logbook', logbookSchema);
const Report = mongoose.model('Report', reportSchema);

app.use(bodyParser.urlencoded({ extended: true }));
app.use(bodyParser.json());

// Serve static files (CSS and client-side scripts)
app.use(express.static('public'));

// Handle student form submission
app.post('/submitStudentForm', async (req, res) => {
    const studentData = req.body;
    const student = new Student(studentData);
    await student.save();
    res.send('Student form submitted successfully.');
});

// Handle logbook form submission
app.post('/submitLogbookForm', async (req, res) => {
    const logbookData = req.body;
    const logbook = new Logbook(logbookData);
    await logbook.save();
    res.send('Logbook entry submitted successfully.');
});

// Handle report form submission
app.post('/submitReportForm', async (req, res) => {
    const reportData = req.body;
    const report = new Report(reportData);
    await report.save();
    res.send('Report submitted successfully.');
});

// Start the server
app.listen(PORT, () => {
    console.log(`Server is running on http://localhost:${PORT}`);
});
