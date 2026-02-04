$(function () {
  $(document).ajaxStop($.unblockUI);

 
  setUpload();
  function setUpload() {
    $('input:file').fileupload({
      dataType: 'json',
      add: function (e, data) {
        data.context = $(this).closest('tr');
        // $(this).after(data.files[0].name);
        $('label[for=' + $(this).attr('id') + ']').remove();
        $(this).after('<label class="btn pull-left" style="background-color: #99C0DD" for="' + $(this).attr('id') + '">' + data.files[0].name + '</label>&nbsp;');
        $(this).hide();
        data.context.find('button.add-approval').off('click').on('click', function () {
          if (!data.context.find('[name*="version_no"]').val() || !data.context.find('[name*="file_date"]').val()) {
            alert('Please enter the document version and date.');
          } else {
            data.submit();
          }
        });
      },
      formData: function (form) {
        var fieldData = new Array();
        fieldData.push({
          name: $('#ApplicationId').attr('name'),
          value: $('#ApplicationId').val()
        });
        $('#' + $(this.fileInput[0]).attr('id')).closest('tr').find(':input').each(function () {
          fieldData.push({ name: $(this).attr('name'), value: $(this).val() });
        });
        // console.log(fieldData);
        // return false;
        return fieldData;
      },
      beforeSend: function () {
        $.blockUI({
          css: {
            border: 'none',
            padding: '15px',
            backgroundColor: '#000',
            '-webkit-border-radius': '10px',
            '-moz-border-radius': '10px',
            opacity: .5,
            color: '#fff'
          },
          message: '<p class="lead"><span><i class="icon-spinner icon-spin"></i> Please wait... </span></p>'
        });
      },
      send: function (e, data) {
        if ($('#ApplicationId').val()) {
          return true;
        }
        return false;
      },
      done: function (e, data) {
        if (data.result.message == 'Success') {
          $(this).closest('td').prepend('<div style="margin-bottom: 5px;"> \
                    <a href="/applicant/attachments/download/'+ data.result.content.Attachment.id + '" class="btn btn-info"> \
                    '+ data.result.content.Attachment.basename + '</a> \
                    <small class="muted">- '+ data.result.content.Attachment.created + '</small> \
                    <button id="AmendmentChecklist'+ data.result.content.Attachment.id + '" type="button" style="margin-left:10px;" \
                              class="btn btn-mini btn-danger delete_file_link">&nbsp;<i class="icon-trash"></i>&nbsp;</button></div>');
          closestTd = $(this).closest('td');
          $('label[for=' + $(this).attr('id') + ']').remove();
          $(this).remove();
          // toggleApproval();
          closestTd.find('.progress').fadeOut('slow');
        } else {
          $(this).closest('td').append('<div class="alert alert-error"> \
                    <a class="close" data-dismiss="alert" href="#">&times;</a> \
                    <p>'+ data.result.errors + '</p> </div>');
          $(this).closest('td').find('.progress').fadeOut('slow');
        }

      },
      progress: function (e, data) {
        var progress = parseInt(data.loaded / data.total * 100, 10);
        // console.log();
        $('#' + $(this).attr('id')).closest('td').find('.progress').show().find('.bar').css(
          'width',
          progress + '%'
        );
        // $('#'+$(this).attr('id')).closest('td').find('.progress').fadeOut('slow');
      }
    });

  }

  //Set the date
  lastDate = new Date();
  var currentMonth = lastDate.getMonth() + 1; // JavaScript months are zero-based

  // Check if the current month is December
  if (currentMonth === 12) {
    lastDate.setFullYear(lastDate.getFullYear() + 1); // Add one year if it's December
  }
  $('.checklistyear').datepicker({
    format: " yyyy",
    minViewMode: "years",
    startDate: '2000',
    endDate: lastDate.getFullYear().toString(),
    autoclose: true
  });
  yearset();
  $('.kayear').change(yearset);

  function yearset() {
    if ($('.checklistyearyear').val() != $('.kayear').val()) {
      $('.kayear').closest('table').find('input[name*="year"]').each(function () {
        $(this).val($('.kayear').val().trim());
      });
    }
  }

  // Approval Next Year Start
  var lastDate = new Date();
  var currentMonth = lastDate.getMonth() + 1; // JavaScript months are zero-based

  // Check if the current month is December
  if (currentMonth === 12) {
    lastDate.setFullYear(lastDate.getFullYear() + 1); // Add one year if it's December
  }

  yeardataset();
  $('.amendmentyear').change(function () {
    // Get the selected value
    var selectedYear = $(this).val();

    yeardataset();
  });

  function yeardataset() {

    if ($('.checklistyearyear').val() != $('.amendmentyear').val()) {
      $('.amendmentyear').closest('table').find('input[name*="year"]').each(function () {
        $(this).val('amd-' + $('.amendmentyear').val().trim());
      });
    }
  }

  // End 

  $('.add-approval').click(function () {
    if ($(this).closest('tr').find('input:file').length) {
      alert('Please upload the current file before adding another one');
    } else {
      var fileId = parseInt($(this).closest('tr').find('input[name*="model"]').attr('id').match(/\d+/g), 10);
      if (!isNaN(fileId)) {

        var fileInput = '<input type="file" id="AmendmentChecklist' + fileId + 'File" class="span12 input-file" \
              name="data[AmendmentChecklist]['+ fileId + '][file]">';
        $(this).closest('tr').find('td.files').append(fileInput);
        setUpload();
      } else {
        alert('We have slight problem. Please refresh this page');
      }
    }
  });

  // $(".delete_file_link").on("click", delete_file);
  $(document).on('click', '.delete_file_link', delete_file);
  function delete_file() {
    if (confirm("are you sure you would like to delete this attachment?")) {
      intId = parseInt($(this).attr('id').replace(/\D/g, ''));
      name = $(this).attr('id').replace(/\d/g, '');
      if (intId) {
        $.ajax({
          type: 'POST',
          url: '/attachments/delete/' + intId + '.json',
          data: { 'id': intId },
          success: function (data) {
            // console.log(data);
          }
        });
      }
      $(this).parent('div').remove();
    }
  }

  setAttachmentUpload();
  function setAttachmentUpload() {
    $('input:file').each(function () {
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
});
