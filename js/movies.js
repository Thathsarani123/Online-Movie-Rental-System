// Function to load movies from the XML file
function loadMovies() {
    fetch('./movies.xml') // Adjust the path as necessary
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            console.log("XML Data Loaded:", data); // Log the raw XML data
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(data, "application/xml");

            // Check for parsing errors
            const parserError = xmlDoc.getElementsByTagName("parsererror");
            if (parserError.length) {
                throw new Error('Error parsing XML: ' + parserError[0].textContent);
            }

            // Map XML data to an array of movie objects
            const movies = Array.from(xmlDoc.getElementsByTagName("movie")).map(movie => ({
                id: parseInt(movie.getAttribute("id")),
                title: movie.getElementsByTagName("title")[0].textContent,
                genre: movie.getElementsByTagName("genre")[0].textContent,
                release_year: parseInt(movie.getElementsByTagName("release_year")[0].textContent),
                rating: parseFloat(movie.getElementsByTagName("rating")[0].textContent),
                available: movie.getElementsByTagName("available")[0].textContent === "true",
                copies: parseInt(movie.getElementsByTagName("copies")[0].textContent),
                image: movie.getElementsByTagName("image")[0].textContent // Add image element
            }));

            console.log("Parsed Movies:", movies); // Log the parsed movie objects
            displayTopMovies(movies);
            displayTrendMovies(movies);
            displayAllMovies(movies);
        })
        .catch(error => {
            console.error("Error loading movies:", error);
        });
}
// Function to display the list of movies
function scrollLeft() {
    const moviesContainer = document.getElementById("movies-container");

    // Scroll by a fixed amount to the left
    moviesContainer.scrollBy({
        left: -300, // Adjust this value based on your card width
        behavior: 'smooth' // Smooth scrolling animation
    });
}

function scrollRight() {
    const moviesContainer = document.getElementById("movies-container");

    // Scroll by a fixed amount to the right
    moviesContainer.scrollBy({
        left: 300, // Adjust this value based on your card width
        behavior: 'smooth' // Smooth scrolling animation
    });
}


// Function to fetch movies from the XML file
function fetchMovies() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "./movies.xml", true); // Adjust the path as needed

    xhr.onload = function() {
        if (xhr.status === 200) {
            const xml = xhr.responseXML;
            const movies = Array.from(xml.getElementsByTagName("movie")).map(movie => ({
                title: movie.getElementsByTagName("title")[0].textContent,
                image: movie.getElementsByTagName("image")[0].textContent,
                rating: parseFloat(movie.getElementsByTagName("rating")[0].textContent),
            }));

            displayTopMovies(movies);
            displayTrendMovies(movies);
        } else {
            console.error("Failed to fetch movies:", xhr.status);
        }
    };

    xhr.onerror = function() {
        console.error("Request error");
    };

    xhr.send();
}

// Function to display the top-rated movies
function displayTopMovies(movies) {
    const moviesContainer = document.getElementById("trend-container");
    moviesContainer.innerHTML = ""; // Clear previous content

    // Filter movies to show only those with a rating greater than 3.5
    const filteredMovies = movies.filter(movie => movie.rating > 3.5);

    filteredMovies.forEach(movie => {
        const movieItem = document.createElement("div");
        movieItem.className = "movie-card";

        // Create an anchor tag for redirection
        movieItem.innerHTML = `
            <a href="movies.php" class="movie-card" style="background-image: url('${movie.image}');">
                <div class="movie-title">
                    <h5 class="card-title">${movie.title}</h5>
                </div>
            </a>
        `;
        moviesContainer.appendChild(movieItem);
    });
}

// Call the function to fetch movies when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", fetchMovies);



// Function to display trending movies
function displayTrendMovies(movies) {
    const moviesContainer = document.getElementById("movies-container");
    moviesContainer.innerHTML = ""; // Clear previous content

    // Sort movies by views (assuming you have views data in the XML)
    const sortedMovies = movies.sort((a, b) => b.views - a.views);

    // Limit to top 5 trending movies (or as needed)
    const topTrendingMovies = sortedMovies.slice(0, 5);

    topTrendingMovies.forEach(movie => {
        const movieItem = document.createElement("div");
        movieItem.className = "movie-card";

        // Create an anchor tag for redirection
        movieItem.innerHTML = `
            <a href="movies.php" class="movie-card" style="background-image: url('${movie.image}');">
                <div class="movie-title">
                    <h5 class="card-title">${movie.title}</h5>
                </div>
            </a>
        `;
        moviesContainer.appendChild(movieItem);
    });
}

// Call the function to fetch movies when the DOM is fully loaded
document.addEventListener("DOMContentLoaded", fetchMovies);





// Function to display all movies
function displayAllMovies(movies) {
    const moviesContainer = document.getElementById("movie-container");
    moviesContainer.innerHTML = ""; // Clear previous content

    movies.forEach(movie => {
        const movieItem = document.createElement("div");
        movieItem.className = "col-md-4 mb-4"; // Adjust class for column layout

        // Create an anchor tag for redirection
        movieItem.innerHTML = `
            <div class="blog-entry justify-content-end" data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100">
                <a href="movies.php?id=${movie.id}" class="block-20 img d-flex align-items-end" style="background-image: url('${movie.image}');"></a>
                <div class="text">
                    <p class="meta">
                        <span><i class="fa fa-money"></i></span><br>
                        <span><a href="#"><i class="fa fa-building"></i> ${movie.title}</a></span>
                    </p>
                    <h3 class="heading mb-3"><a href="#">${movie.title}</a></h3>
                    <p><b>Year: </b>${movie.release_year}</p>
                </div>
            </div>
        `;

        moviesContainer.appendChild(movieItem);
    });
}


// Function to search movies based on title, genre, or release year
function searchMovies() {
    const input = document.getElementById("search-input").value.toLowerCase(); // Get search input
    console.log(input); // Check if input is being captured

    const moviesContainer = document.getElementById("allmovies-container"); // The container for the movies
    const allMovies = Array.from(moviesContainer.getElementsByClassName("movie-card")); // Get all movie cards

    allMovies.forEach(movieCard => {
        const title = movieCard.querySelector(".card-title").textContent.toLowerCase(); // Get the title of the movie
        const genre = movieCard.querySelector(".card-genre").textContent.toLowerCase(); // Get the genre
        const releaseYear = movieCard.querySelector(".card-release-year").textContent.toLowerCase(); // Get the release year

        // Check if the search input matches the title, genre, or release year
        if (title.includes(input) || genre.includes(input) || releaseYear.includes(input)) {
            movieCard.style.display = ""; // Show movie card if any match
        } else {
            movieCard.style.display = "none"; // Hide movie card if no match
        }
    });
}

// Attach the search function to the search form submission
document.getElementById("search-form").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent form submission
    searchMovies(); // Call the search function
});

// Function to handle renting a movie and store rental details in users.xml
function rentMovie(movieId) {
    fetch('./movies.xml')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(data, "application/xml");

            const movie = Array.from(xmlDoc.getElementsByTagName("movie")).find(m => parseInt(m.getAttribute("id")) === movieId);

            if (movie && movie.getElementsByTagName("available")[0].textContent === "true") {
                // Mark the movie as unavailable
                movie.getElementsByTagName("available")[0].textContent = "false";
                
                // Capture the rent date
                const rentedOn = new Date().toISOString().slice(0, 10); // YYYY-MM-DD format

                // Send AJAX request to update users.xml
                const xhr = new XMLHttpRequest();
                xhr.open("POST", "update_user_rentals.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

                // Sending data (movieId, rentedOn, and returned status)
                xhr.send(`movieId=${movieId}&rentedOn=${rentedOn}&returned=false`);

                xhr.onload = function () {
                    if (xhr.status === 200) {
                        alert(`You have successfully rented "${movie.getElementsByTagName("title")[0].textContent}".`);
                        loadMovies(); // Refresh the movie list
                    } else {
                        alert("Error processing your request.");
                    }
                };
            } else {
                alert("This movie is not available for rent.");
            }
        })
        .catch(error => {
            console.error("Error renting movie:", error);
        });
}

// Load movies when the page is loaded
window.onload = loadMovies;

// Call the loadAllMovies function after the DOM is fully loaded
document.addEventListener("DOMContentLoaded", loadAllMovies);

// Function to load all movies into the table for admin view
function loadAllMovies() {
    fetch('./movies.xml') // Adjust the path as necessary
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.statusText);
            }
            return response.text();
        })
        .then(data => {
            const parser = new DOMParser();
            const xmlDoc = parser.parseFromString(data, "application/xml");

            const movies = Array.from(xmlDoc.getElementsByTagName("movie")).map(movie => ({
                id: parseInt(movie.getAttribute("id")),
                title: movie.getElementsByTagName("title")[0].textContent,
                genre: movie.getElementsByTagName("genre")[0].textContent,
                release_year: parseInt(movie.getElementsByTagName("release_year")[0].textContent),
                available: movie.getElementsByTagName("available")[0].textContent === "true" ? "Available" : "Not Available"
            }));

            displayMoviesInTable(movies);
        })
        .catch(error => {
            console.error("Error loading movies:", error);
        });
}

// Function to display movies in the HTML table for admin view
function displayMoviesInTable(movies) {
    const movieTableBody = document.getElementById("movie-table-body");
    movieTableBody.innerHTML = ""; // Clear previous content

    movies.forEach(movie => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${movie.id}</td>
            <td>${movie.title}</td>
            <td>${movie.genre}</td>
            <td>${movie.release_year}</td>
            <td>${movie.available}</td>
        `;
        movieTableBody.appendChild(row);
    });
}

// Load all movies into the table when the page loads
window.onload = function() {
    loadMovies();
    loadAllMovies();
};
