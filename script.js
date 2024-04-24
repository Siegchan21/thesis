$(document).ready(function() {
    // Add class button click event
    $('#addClassBtn').click(function() {
        // Create a new form group with class inputs
        var classForm = `
            <div class="class-form">
                <div class="form-group">
                    <label>Class Code</label>
                    <input type="text" class="form-control" name="classCode[]" required>
                </div>
                <div class="form-group">
                    <label>Class Name</label>
                    <input type="text" class="form-control" name="className[]" required>
                </div>
                <div class="form-group">
                    <label>Time</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="time[]" id="am" value="AM" checked>
                        <label class="form-check-label" for="am">AM</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="time[]" id="pm" value="PM">
                        <label class="form-check-label" for="pm">PM</label>
                    </div>
                </div>
                <button type="button" class="btn btn-danger remove-btn">Remove</button>
            </div>
        `;
        // Append the new form group to the container
        $('#classFormsContainer').append(classForm);
    });

    // Remove class button click event (delegated event)
    $('#classFormsContainer').on('click', '.remove-btn', function() {
        // Remove the parent form group
        $(this).closest('.class-form').remove();
    });

    // Submit form
    $('#classForm').submit(function(event) {
        event.preventDefault(); // Prevent default form submission
        // You can perform AJAX submission here
        console.log('Form submitted!');
        // Example: AJAX submission
        /*
        $.ajax({
            url: 'submit.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        */
    });
});
