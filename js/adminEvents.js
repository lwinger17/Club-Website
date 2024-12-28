let currentEventId = null;

function editEvent(title, shortDesc, detailedDesc, time, type, imgPath, date, eventId) {
    // Set form fields to the input from the PHP files
    document.getElementById('eventTitle').value = title;
    document.getElementById('eventDesc').value = shortDesc;
    document.getElementById('eventDetailedDesc').value = detailedDesc;
    document.getElementById('eventTime').value = time;
    document.getElementById('eventType').value = type;
    document.getElementById('eventDate').value = date;
    document.getElementById('event_id').value = eventId; // Set the hidden event ID field
    currentEventId = eventId;

    // Set the initial image path (crucial for image deletion handling)
    document.getElementById('initial_img_path').value = imgPath;


    // Show/Hide photo preview section based on imgPath
    const photoPreviewSection = document.querySelector('.photo-preview');
    const deletePhotoButton = document.getElementById('deletePhotoButton');

    if (imgPath) {
        photoPreviewSection.style.display = 'block';
        document.getElementById('photoPreview').src = imgPath;
        deletePhotoButton.style.display = 'block'; // Show delete button if image exists
    } else {
        photoPreviewSection.style.display = 'none';
        deletePhotoButton.style.display = 'none'; // Hide delete button if no image
    }
}

// Function to handle saving changes
async function saveEvent() {
    // Get form data
    const title = document.getElementById('eventTitle').value;
    const shortDesc = document.getElementById('eventDesc').value;
    const detailedDesc = document.getElementById('eventDetailedDesc').value;
    const time = document.getElementById('eventTime').value;
    const type = document.getElementById('eventType').value;
    const date = document.getElementById('eventDate').value;
    const eventId = document.getElementById('event_id').value;
    const photoUpload = document.getElementById('eventPhotoUpload');
    const initialImgPath = document.getElementById('initial_img_path').value; // Get initial image path
    const currentImgPath = document.getElementById('photoPreview').src; // Get current image path

    // Create a FormData object to send data to the server
    let formData = new FormData();
    formData.append('event_id', eventId);
    formData.append('event_title', title);
    formData.append('event_desc', shortDesc);
    formData.append('event_detailed_desc', detailedDesc);
    formData.append('event_time', time);
    formData.append('event_type', type);
    formData.append('event_date', date);

    try {
        // Handle image scenarios
        if (photoUpload.files && photoUpload.files[0]) {
            // 2. New image uploaded (replaces existing)
            formData.append('event_img', photoUpload.files[0]);
        } else if (initialImgPath !== '' && currentImgPath === '') {
            // 1. Image deleted (initial image existed, now removed)
            formData.append('event_img', null); // Signal deletion to server
        } else if (initialImgPath === '' && currentImgPath !== '') {
            // 3. Image added (event initially had no image)
            formData.append('event_img', currentImgPath); // Send the new image path
        }
        // 4. No image change (initial state maintained) - nothing to do

        // Send the data to the server using fetch
        const response = await fetch('editEvent.php', { method: 'POST', body: formData });

        // Check for HTTP errors
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        // Parse JSON response
        const data = await response.json();

        // Handle JSON response
        if (data.status === 'success') {
            alert(data.message);
            // Optionally reset form fields or update the page dynamically
            document.getElementById('eventForm').reset();
            location.reload(); //Reload the page to reflect changes
        } else {
            alert('Error: ' + data.message);
        }
    } catch (error) {
        alert('An error occurred: ' + error.message);
        console.error('Error:', error);
    }
}

document.getElementById('deletePhotoButton').addEventListener('click', deletePhoto);

function deletePhoto() {
    if (confirm("Are you sure you want to delete this image?")) {
        if (currentEventId === null) {
            console.error("Error: currentEventId is null. No event selected.");
            alert("Error: No event selected.");
            return;
        }

        // Set the image source to null
        document.getElementById('photoPreview').src = '';

        // Hide the preview area and delete button
        const photoPreviewSection = document.querySelector('.photo-preview');
        photoPreviewSection.style.display = 'none';
        document.getElementById('deletePhotoButton').style.display = 'none';

    }
}

// Function to preview the selected photo
function previewPhoto() {
    const photoUpload = document.getElementById('eventPhotoUpload');
    if (photoUpload.files && photoUpload.files[0]) {
        const preview = document.getElementById('photoPreview');
        preview.src = URL.createObjectURL(photoUpload.files[0]);
    }
}