// document.addEventListener('DOMContentLoaded', function () {
//     var saveBtn = document.getElementById('ApplicationSaveChanges');
//     var hiddenInput = document.getElementById('ApplicationSubmitType');

//     if (saveBtn && hiddenInput) {
//         saveBtn.addEventListener('click', function () {
//             hiddenInput.value = 'saveChanges';
//         });
//     }
// });

$(document).ready(function () {
    $('#ApplicationSaveChanges').on('click', function () {
        console.log("Clicked the save changes button")
        $('#ApplicationSubmitType').val('saveChanges');
        return true;
    });
    

    $('#ApplicationSubmitReport').on('click', function (e) {
        // Show confirmation dialog
        var confirmSubmit = confirm("Are you sure you wish to submit the form to PPB? You will not be able to edit it later.");

        if (confirmSubmit) {
            // If confirmed, set the hidden input and allow form to submit

        console.log("Clicked the submit button")
            $('#ApplicationSubmitType').val('submitReport');
            return true;
        } else {
            // If cancelled, prevent form submission
            e.preventDefault();
        }
    });
});

