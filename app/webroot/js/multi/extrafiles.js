$(function () {
  $(document).on('click', '#tabs-14 .remove-row', remove_row);
  $(document).on('click', '#tabs-14 .update-description', update_description);

  if ($("#buildAattachmentsform tr").length == 1) { $("#attachmentsTableHeader").hide(); }
  setAttachmentUpload();
  function setAttachmentUpload() {
    $('#tabs-14 :input:file').each(function () {
      $(this).fileupload({
        dataType: 'json',
        fileInput: $(this),
        add: function (e, data) {
          data.context = $(this).closest('.control-group');
          if (!data.context.find('.progress').length) {
            data.context.append('\
                      <div class="progress progress-striped active" style="width: 85%; margin-top: 5px;"> \
                        <div class="bar" style="width: 0%;"></div> \
                      </div>');
          }
          data.context.find('.progress .bar').css('width', '0%');
          data.submit();
        },
        submit: function (e, data) {
          var fieldData = new Array();
          fieldData.push({ name: $('#ApplicationId').attr('name'), value: $('#ApplicationId').val() });
          data.context.closest('tr').find(':input').each(function () {
            fieldData.push({ name: $(this).attr('name'), value: $(this).val() });
          });
          data.formData = fieldData;
          //Don't allow saving records without application id. will result in rogue applications created
          if (!$('#ApplicationId').val()) {
            $(this).focus();
            alert('we have an unexpected problem. please logout and login again.');
            return false;
          }
        },
        progress: function (e, data) {
          var progress = parseInt(data.loaded / data.total * 100, 10);
          data.context.find('.progress').show().find('.bar').css('width', progress + '%');
        },
        done: function (e, data) {
          //bad experience of reloading on msie.
          if (/msie/.test(navigator.userAgent.toLowerCase())) {
            location.reload();
          }
          if (data.result.message == 'Success') {
            // data.context.empty();
            data.context.prepend(' \
                      <a href="/applicant/attachments/download/'+ data.result.content.Attachment.id + '" class="btn btn-info"> \
                      '+ data.result.content.Attachment.basename + '</a>');
            // find a way of updating the delete link right hooore.!!!
            // data.context.find('input[name*="id"]').val(data.result.content.Attachment.id);
            data.context.find('[name*="id"]').val(data.result.content.Attachment.id);
            data.context.find('[name*="basename"]').val(data.result.content.Attachment.basename);
            data.context.find('[name*="dirname"]').val(data.result.content.Attachment.dirname);
            data.context.find('[name*="checksum"]').val(data.result.content.Attachment.checksum);
            data.context.closest('tr').find('.remove-row').val(data.result.content.Attachment.id);
            data.context.find('input:file').remove();
            data.context.find('.progress').fadeOut('slow');
          } else {
            data.context.append('<div class="alert alert-error"> \
                      <a class="close" data-dismiss="alert" href="#">&times;</a> \
                      <p>'+ data.result.errors + '</p> </div>');
            data.context.find('.progress').fadeOut('slow');
          }
        }
      })
    });
  }
  // incremental development
  $("#addMAttachment").click(function () {
    $("#attachmentsTableHeader").show();
    var intId = $("#buildAattachmentsform tr").length - 1;
    if ($('#buildAattachmentsform :input[type="file"]').length < 4) {
      $("#buildAattachmentsform").append(constructATr(intId));
      setAttachmentUpload();
    } else {
      alert("Sorry, cant save more than Four Attachments at a time!");
    }
  });
  function constructATr(intId) {
    var intId2 = intId + 1;
    var trWrapper = '\
        <tr class="fieldwrapper" id="field{i}">\
        <td>{i2}</td>\
        <td><div class="control-group">\
            <input type="hidden" id="Attachment{i}Model" name="data[Attachment][{i}][model]" value="Application">\
            <input type="hidden" id="Attachment{i}Group" name="data[Attachment][{i}][group]" value="attachment">\
            <input type="hidden" id="Attachment{i}Dirname" name="data[Attachment][{i}][dirname]">\
            <input type="hidden" id="Attachment{i}Basename" name="data[Attachment][{i}][basename]">\
            <input type="hidden" id="Attachment{i}Checksum" name="data[Attachment][{i}][checksum]">\
            <input type="hidden" id="Attachment{i}Id" class="" \
                name="data[Attachment][{i}][id]"><input type="file" id="Attachment{i}File" class="span12 input-file" \
                name="data[Attachment][{i}][file]"  data-items="4"  autocomplete="off" >\
        </div></td>\
        <td><div class="control-group"><textarea id="Attachment{i}Description"  rows="1" \
               name="data[Attachment][{i}][description]" class="span11"></textarea></div></td>\
        <td><button  type="button" class="btn-mini btn-success update-description tiptip" data-original-title="Update Description">\
                      &nbsp;<i class="icon-cloud"></i>&nbsp;</button><button  type="button" class="btn-mini remove-row tiptip" data-original-title="Remove file">\
                      &nbsp;<i class="icon-minus"></i>&nbsp;</button></td>';

    $('.tiptip').tooltip();
    return trWrapper.replace(/{i}/g, intId).replace(/{i2}/g, intId2);
  }

  function update_description() {
    var $row = $(this).closest('tr');
    var attachmentId = $row.find('input[name*="[id]"]').val();
    var description = $row.find('textarea[name*="[description]"]').val();

    if (!attachmentId) {
      alert('Attachment ID is missing. Please upload a file first.');
      return;
    } 
    $.ajax({
      url: '/attachments/update_description/' + attachmentId + '.json',
      method: 'POST',
      data: {
        id: attachmentId,
        description: description
      },
      success: function(response) {
        alert('Description updated successfully!');
      },
      error: function() {
        alert('Failed to update description.');
      }
    });
  }

  function remove_row() {
    deleteButton = $(this);
    if ($(this).closest('tr').find('input:file').length == 1) {
      // $(this).closest('tr').remove();
      updateATr(deleteButton);
    } else {
      if (confirm("are you sure you would like to delete this attachment?")) {
        fileThis = $(this);
        intId = parseInt(fileThis.val());
        if (!isNaN(intId)) {
          $.ajax({
            type: 'POST',
            url: '/attachments/delete/' + intId + '.json',
            data: { 'id': intId },
            success: function (data) {
              // fileThis.closest('div').remove();
              //nimiweka but will definitely update
              updateATr(deleteButton);
            },
            error: function (data) {
              fileThis.closest('td').append('<div class="alert alert-error"> \
                                    <a class="close" data-dismiss="alert" href="#">&times;</a> \
                                    <p>Sorry! we could not complete this action. Please logout and login again to proceed..</p> </div>');
            }
          });
        } else {
          fileThis.closest('td').append('<div class="alert alert-error"> \
                                <a class="close" data-dismiss="alert" href="#">&times;</a> \
                                <p>Sorry! we could not complete this action. Please logout and login again to proceed</p> </div>');
        }
      }
    }
  }

  function updateATr(myobj) {
    myobj
      .closest('td')
      .siblings()
      .wrapInner('<div style="display: block;" />')
      .closest('tr')
      .find('td > div')
      .slideUp(300, function () {
        $(this).closest('tr').siblings(function () {
          rowNo = parseInt($(this).find('td:first').text()) - 1;
          intId = rowNo - 1;
          $(this).find('td:first').text(rowNo);
          $(this).find('input').each(function () {
            id = $(this).attr('id');
            name = $(this).attr('name');
            $(this).prop('id', id.replace(/\d+/, intId));
            $(this).prop('name', name.replace(/\d+/, intId));
          });
        })

        $(this).closest('tr').remove();
      });
  };
});
