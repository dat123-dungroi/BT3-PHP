document.addEventListener('DOMContentLoaded', function () {
  const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
  deleteButtons.forEach(function (btn) {
    btn.addEventListener('click', function (event) {
      if (!confirm('Bạn có chắc muốn thực hiện hành động này?')) {
        event.preventDefault();
      }
    });
  });
});
