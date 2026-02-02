function updateOrderField(select, orderId, field) {
  fetch('/order_management/orders.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    // URL-encode field & value, no errors for special characters
    body: `order_id=${orderId}&field=${encodeURIComponent(
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
