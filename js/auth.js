// Register new users
document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const email = document.getElementById('email').value;
    const nic = document.getElementById('nic').value;
    const password = document.getElementById('password').value;

    if (!username || !email || !nic || !password) {
        alert('Please fill in all fields');
        return;
    }

    // Load the existing users XML file
    const xml = await loadUsersXML();
    const users = xml.getElementsByTagName('user');

    // Check if the email already exists
    if (Array.from(users).some(user => user.getElementsByTagName('email')[0].textContent === email)) {
        alert('Email is already registered.');
        return;
    }

    // Create new user XML element
    const newUser = xml.createElement('user');
    
    const usernameNode = xml.createElement('username');
    usernameNode.textContent = username;
    
    const emailNode = xml.createElement('email');
    emailNode.textContent = email;

    const nicNode = xml.createElement('nic');
    nicNode.textContent = nic;

    const passwordNode = xml.createElement('password');
    passwordNode.textContent = password;

    const roleNode = xml.createElement('role');
    roleNode.textContent = 'user'; // Default role

    newUser.appendChild(usernameNode);
    newUser.appendChild(emailNode);
    newUser.appendChild(nicNode);
    newUser.appendChild(passwordNode);
    newUser.appendChild(roleNode);

    xml.documentElement.appendChild(newUser);

    // Save the updated XML data to users.xml
    const xmlString = new XMLSerializer().serializeToString(xml);
    downloadXML(xmlString, 'users.xml');

    alert('Registration successful! You can download the users.xml file.');
    window.location.href = 'login.php';  // Redirect to login page
});

// Login users
document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const username = document.getElementById('loginUsername').value;
    const password = document.getElementById('loginPassword').value;

    const xml = await loadUsersXML();
    const users = xml.getElementsByTagName('user');
    let loggedIn = false;

    for (let i = 0; i < users.length; i++) {
        const usernameXML = users[i].getElementsByTagName('username')[0].textContent;
        const passwordXML = users[i].getElementsByTagName('password')[0].textContent;
        const roleXML = users[i].getElementsByTagName('role')[0].textContent;

        if (username === usernameXML && password === passwordXML) {
            loggedIn = true;

            // Store user info and role in sessionStorage
            sessionStorage.setItem('loggedInUser', usernameXML);
            sessionStorage.setItem('userRole', roleXML); // Store role (admin or user)

            alert(`Login successful! Welcome, ${sessionStorage.getItem('loggedInUser')}`);

            // Redirect based on role
            if (roleXML === 'admin') {
                window.location.href = 'backend/Admin/index.php'; // Redirect admin to admin page
            } else {
                window.location.href = 'movies.php'; // Redirect user to movies page
            }
            break;
        }
    }

    if (!loggedIn) {
        alert('Invalid username or password');
    }
});

// Load the existing users XML file
async function loadUsersXML() {
    const response = await fetch('users.xml');
    const text = await response.text();
    const parser = new DOMParser();
    return parser.parseFromString(text, 'application/xml');
}

// Function to download XML as a file (used during registration)
function downloadXML(data, filename) {
    const blob = new Blob([data], { type: 'application/xml' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

// Logout functionality
function logout() {
    sessionStorage.clear();
    window.location.href = 'login.php';
}
