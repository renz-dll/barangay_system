

const users = [
  { username: "admin", password: "admin123", role: "Barangay Official" },
];

function loginUser(event) {
  event.preventDefault(); 

  const username = document.getElementById("username").value.trim();
  const password = document.getElementById("password").value.trim();
  const errorMessage = document.getElementById("error-message");

  // test
  const user = users.find(u => u.username === username && u.password === password);

  if (user) {
    // Save user in localStorage (temporary session)
    localStorage.setItem("loggedInUser", JSON.stringify(user));
    // Redirect to portal
    window.location.href = "index.html";
  } else {
    errorMessage.textContent = "❌ Invalid username or password!";
  }
}

// Check if user is logged in
function checkLogin() {
  const user = JSON.parse(localStorage.getItem("loggedInUser"));
  if (!user) {
    window.location.href = "login.html"; // redirect if not logged in
  } else {
    // Show logged-in user in header
    document.getElementById("page-title").innerText = `Dashboard - Welcome, ${user.role}`;
  }
}

// Logout
function logout() {
  localStorage.removeItem("loggedInUser");
  window.location.href = "login.html";
}


function loadPage(page) {
  const title = document.getElementById('page-title');
  const content = document.getElementById('content-area');

  if (page === 'dashboard') {
    title.innerText = "Dashboard";
    content.innerHTML = "<p>Quick overview of barangay activities.</p>";
  } else if (page === 'residents') {
    title.innerText = "Residents";
    content.innerHTML = `
      <h3>Residents List</h3>
      <button class="btn" onclick="addResident()">+ Add Resident</button>
      <table>
        <tr><th>Name</th><th>Address</th><th>Age</th></tr>
        <tr><td>Juan Dela Cruz</td><td>Purok 1</td><td>45</td></tr>
        <tr><td>Maria Santos</td><td>Purok 2</td><td>30</td></tr>
      </table>
    `;
  } else if (page === 'certificates') {
    title.innerText = "Certificates";
    content.innerHTML = `
      <h3>Certificate Issuance</h3>
      <p>Issue barangay clearance, residency, and other certificates.</p>
      <button class="btn">+ Issue Certificate</button>
    `;
  } else if (page === 'reports') {
    title.innerText = "Reports";
    content.innerHTML = "<h3>Barangay Reports</h3><p>Generate and view reports here.</p>";
  } else if (page === 'settings') {
    title.innerText = "Settings";
    content.innerHTML = "<h3>System Settings</h3><p>Manage system preferences and accounts.</p>";
  }
}

function addResident() {
  alert("Add Resident form will appear here.");
}

