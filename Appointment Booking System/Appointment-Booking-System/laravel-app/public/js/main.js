document.addEventListener('DOMContentLoaded', function () {
    // Block past dates
    var dateInput = document.getElementById('appointment_date');
    if (dateInput) {
        var today = new Date();
        var minDate = today.getFullYear() + '-' +
            String(today.getMonth() + 1).padStart(2, '0') + '-' +
            String(today.getDate()).padStart(2, '0');

        dateInput.setAttribute('min', minDate);

        dateInput.addEventListener('change', function () {
            if (this.value && this.value < minDate) {
                alert('Past dates cannot be selected.');
                this.value = minDate;
            }
        });

        var form = dateInput.closest('form');
        if (form) {
            form.addEventListener('submit', function (event) {
                if (dateInput.value && dateInput.value < minDate) {
                    event.preventDefault();
                    alert('Please select today or a future date.');
                    dateInput.focus();
                }
            });
        }
    }

    // Confirm before cancelling
    document.querySelectorAll('.js-confirm-cancel').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Cancel this appointment?')) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('.js-confirm-delete').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!confirm('Delete this record?')) {
                event.preventDefault();
            }
        });
    });
});
