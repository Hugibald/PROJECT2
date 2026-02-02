document.addEventListener('DOMContentLoaded', () => {
  const checkboxes = document.querySelectorAll('.review-ok-checkbox');

  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener('change', () => {
      const reviewId = checkbox.dataset.id;
      const xhr = new XMLHttpRequest();
      xhr.open('POST', window.location.href, true); // sendet an die gleiche URL
      xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
      xhr.onload = function () {
        if (xhr.status === 200) {
          console.log(xhr.responseText);
          checkbox.disabled = true; // optional: Checkbox nach Update deaktivieren
        } else {
          alert('Update Error');
        }
      };
      xhr.send('review_id=' + reviewId);
    });
  });
});
