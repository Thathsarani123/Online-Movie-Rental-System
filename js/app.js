// Function to load and parse the XML file for movies

// Function to log the user out
function logoutUser() {
    sessionStorage.removeItem("loggedIn");
    sessionStorage.removeItem("username");
    alert("You have successfully logged out.");
    window.location.href = "index.html"; // Redirect to the home page
}

// Function to display the username if logged in
function displayUsername() {
    const username = sessionStorage.getItem("username");
    if (username) {
        document.getElementById("welcomeMessage").innerText = `Welcome, ${username}`;
        document.getElementById("logoutButton").style.display = "block"; // Show logout button
    }
}

// Initialize the application when the page loads
window.onload = () => {
    if (document.getElementById("movieList")) {
        loadMovies(); // Load the list of movies from XML
    }
    if (document.getElementById("welcomeMessage")) {
        displayUsername(); // Display username if logged in
    }
};

