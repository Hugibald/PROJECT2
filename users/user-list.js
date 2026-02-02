// So to update the db by changing status and role in user-list.php in dropdown

function updateUserField(select, userId, field) {
  fetch('users/user-list.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    // URL-encode field & value, no errors for special characters
    body: `user_id=${userId}&field=${encodeURIComponent(
      field,
    )}&value=${encodeURIComponent(select.value)}`,
  })
    .then((res) => res.text())
    .then((data) => {
      if (data !== 'success') {
        alert('Update failed: ' + data);
        // Shows concrete error
        // Resets dropdown
        select.value = select.dataset.original || select.value;
      } else {
        select.dataset.original = select.value;
      }
    })
    .catch((err) => {
      alert('Update failed: ' + err);
      select.value = select.dataset.original || select.value;
    });
}
