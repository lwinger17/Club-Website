function previewNewPhoto() {
   const photoUpload = document.getElementById('newEventPhotoUpload');
   const preview = document.getElementById('newPhotoPreview');

   if (photoUpload.files && photoUpload.files[0]) {
       const reader = new FileReader(); // Use FileReader for better compatibility

       reader.onload = function (e) { // Use onload instead of onloadend
           preview.src = e.target.result;
           preview.style.display = "block";
       }
       reader.readAsDataURL(photoUpload.files[0]);
   } else {
       preview.style.display = "none";
   }
}


function addNewEvent() {
   const title = document.getElementById('newEventTitle').value;
   const desc = document.getElementById('newEventDesc').value;
   const detailedDesc = document.getElementById('newEventDetailedDesc').value; // Get detailed description
   const time = document.getElementById('newEventTime').value; // Get event time
   const eventType = document.getElementById('newEventType').value; // Get event type
   const photoUpload = document.getElementById('newEventPhotoUpload');
   const date = document.getElementById('newEventDate').value;

   //Improved validation: Check for empty strings and null values
   if (!title || !desc || !date || !time || !eventType) {
       alert("Please fill out all required fields.");
       return;
   }


   const formData = new FormData();
   formData.append('title', title);
   formData.append('desc', desc);
   formData.append('detailedDesc', detailedDesc); // Add detailed description
   formData.append('time', time); // Add event time
   formData.append('eventType', eventType); // Add event type
   if (photoUpload.files && photoUpload.files[0]) {
       formData.append('photo', photoUpload.files[0]);
   }
   formData.append('date', date);

   fetch('adminEventAddHandler.php', {
       method: 'POST',
       body: formData,
   })
   .then(response => {
       if (!response.ok) {
           throw new Error(`HTTP error! status: ${response.status}`);
       }
       return response.text();
   })
   .then(data => {
       alert(data);
       document.getElementById('event-form').reset(); // Use the correct form ID
       document.getElementById('newPhotoPreview').style.display = "none";
   })
   .catch(error => {
       console.error('Error:', error);
       alert('An error occurred. Please try again later.'); //Inform the user about the error
   });
}
 