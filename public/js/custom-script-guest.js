document.addEventListener('DOMContentLoaded', () => {
    const accessKey = 'W_A5i7O9MjRE54Q4l9KA4onU-zZjNbNYowSd8UccBLY';

    // Loop through each card and fetch background image based on course name
    document.querySelectorAll('.course-card').forEach((card) => {
        const courseName = card.getAttribute('data-course-name');

        // Fetch image from Unsplash API for each course
        fetch(`https://api.unsplash.com/search/photos?query=${courseName}&client_id=${accessKey}`)
            .then(response => response.json())
            .then(data => {
                if (data.results && data.results.length > 0) {
                    const imageUrl = data.results[0].urls.regular;
                    // Set background image
                    card.querySelector('.course-bg').setAttribute('src', imageUrl);
                } else {
                    console.log('No images found for:', courseName);
                }
            })
            .catch(error => console.error('Error fetching image:', error));
    });
});