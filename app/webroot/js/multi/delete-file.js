// webroot/js/delete-attachment.js

function remove_row() {
    const attachmentId = $(this).val();
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
  console.log('Attachment ID:', attachmentId);
    if (!attachmentId) {
      alert('No attachment ID found.');
      return;
    }
  
    if (confirm('Are you sure you want to delete this attachment?')) {
      $.ajax({
        url: '/attachments/auto_delete/' + attachmentId+'.json',
        dataType: 'json',
        data: {
          id: attachmentId
        },
        type: 'POST',
        // headers: {
        //   'X-CSRF-Token': csrfToken
        // },
        success: function () {
          // Remove the closest table row
          $(this).closest('tr').remove();
        }.bind(this), // bind 'this' to keep context inside success callback
        error: function () {
          alert('Failed to delete attachment.');
        }
      });
    }
  }
  
  function update_description() {
    // placeholder function for updating if needed
    alert('Update feature not implemented yet.');
  }
  
  $(function () {
    $(document).on('click', '.remove-row-amendment', remove_row);
    $(document).on('click', '.update-row-amendment', update_description);
  });
  