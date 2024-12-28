function previewNewPhoto() {
   const photoUpload = document.getElementById('newEventPhotoUpload'); 
   if (photoUpload.files && photoUpload.files[0]) {
       const preview = document.getElementById('newPhotoPreview');
       preview.src = URL.createObjectURL(photoUpload.files[0]);
       preview.style.display = "block";
   }
}
 
function addNewMember() {
   const name = document.getElementById('memberName').value;
   const bio = document.getElementById('memberBio').value;
   const photoUpload = document.getElementById('newEventPhotoUpload');

   if (!name || !bio || !photoUpload.files[0]) {
       alert("Please fill out all fields and upload a photo.");
       return;
   }

   const formData = new FormData();
   formData.append('name', name);
   formData.append('bio', bio);
   formData.append('photo', photoUpload.files[0]);

   fetch('addMemberHandler.php', {
       method: 'POST',
       body: formData,
   })
   .then(response => response.text())
   .then(data => {
       alert(data);
       document.getElementById('event-form').reset();
       document.getElementById('newPhotoPreview').style.display = "none";
   })
   .catch(error => console.error('Error:', error));
}

 