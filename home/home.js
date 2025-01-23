    document.addEventListener('DOMContentLoaded', () => {
        const searchBar = document.getElementById('searchbar');
        const searchInput = document.getElementById('search-input');
        const floatingCard = document.getElementById('floating-card');
        const resultsContainer = document.getElementById('results-container');
        const closeButton = document.getElementById('close-card');

        // Event listener for form submission
        searchBar.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission
            const query = searchInput.value.trim();
            if (!query) return; // Do nothing if input is empty
            fetchSearchResults(query);
        });

        // Event listener to close the floating card
        closeButton.addEventListener('click', closeFloatingCard);

        // Fetch search results and display them
        function fetchSearchResults(query) {
            fetch('home.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `search-input=${encodeURIComponent(query)}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        displayResults(data.results);
                        floatingCard.style.display = 'block';
                    } else {
                        showNoResults();
                    }
                })
                .catch(error => console.error('Error fetching results:', error));
        }

        // Dynamically display search results
        function displayResults(results) {
            resultsContainer.innerHTML = results.map(result => `
                <div class="chef-card">
                    <img src="../assets/uploads/chef_pic/${result.chef_pic || 'default-profile-pic.jpeg'}" alt="${result.chef_name}">
                    <h3>${result.chef_name}</h3>
                    <p>${result.city} - ${result.speciality}</p>
                </div>
            `).join('');
        }

        // Show message when no results are found
        function showNoResults() {
            resultsContainer.innerHTML = `
                <div class="no-results">
                    <p>No chefs found for your search query.</p>
                </div>
            `;
            floatingCard.style.display = 'block';
        }

        // Close the floating card
        function closeFloatingCard() {
            floatingCard.style.display = 'none';
        }

        // Fetch live suggestions as the user types
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.trim();
            if (!query) {
                floatingCard.style.display = 'none';
                return;
            }
            fetchLiveSuggestions(query);
        });

        // Fetch live suggestions for the input
        function fetchLiveSuggestions(query) {
            fetch('home.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: `search-input=${encodeURIComponent(query)}`,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.results && data.results.length > 0) {
                        displaySuggestions(data.results);
                    } else {
                        showNoSuggestions();
                    }
                })
                .catch(error => console.error('Error fetching suggestions:', error));
        }

        // Display live suggestions dynamically
        function displaySuggestions(suggestions) {
            resultsContainer.innerHTML = suggestions.map(chef => `
                <div class="suggestion" onclick="alert('You selected: ${chef.chef_name}, ${chef.city}, Cuisine: ${chef.speciality}')">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <img src="${chef.chef_pic || '../assets/uploads/chef_pic/default-profile-pic.jpeg'}" 
                             alt="${chef.chef_name}" 
                             style="width: 40px; height: 40px; border-radius: 50%;">
                        <div>
                            <strong>${chef.chef_name}</strong><br>
                            <small>${chef.city} | ${chef.speciality}</small>
                        </div>
                    </div>
                </div>
            `).join('');
            floatingCard.style.display = 'flex';
        }

        // Show a message when no suggestions are available
        function showNoSuggestions() {
            resultsContainer.innerHTML = `
                <div class="no-suggestions">
                    <p>No suggestions available.</p>
                </div>
            `;
            floatingCard.style.display = 'flex';
        }
    });
