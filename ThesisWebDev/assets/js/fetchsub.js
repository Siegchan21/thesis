$(document).ready(function() {
    // Event listener for dropdown change
    $('#courses').change(function() {
        var selectedCourse = $(this).val();
        if (selectedCourse === 'CAFA') {
            // Send AJAX request to fetch data
            $.ajax({
                url: 'fetchData.php', // URL of the server-side script
                method: 'POST', // HTTP method
                data: { course: selectedCourse }, // Data to send to the server
                dataType: 'json', // Expected data type of the response
                success: function(response) {
                    // Clear previous data
                    $('#dataDisplay').empty();
                    // Display fetched data
                    $('#dataDisplay').html('<p>' + response.message + '</p>');
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });
        } else {
            // Clear the data display if a course other than "CAFA" is selected
            $('#dataDisplay').empty();
        }
    });
});
