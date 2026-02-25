$(function () {
    $(document).on('click', '.remove-row-amendment', remove_row);
    $(document).on('click', '.update-row-amendment', update_description);

    function normalizeAmendmentYear(value) {
      var normalized = (value || '').toString().trim().toLowerCase();
      normalized = normalized.replace(/^-+/, '');
      normalized = normalized.replace(/^amd[\s_-]*/i, '');
      if (normalized === '') {
        return '';
      }

      if (/^\d+(\.\d+)?$/.test(normalized)) {
        var numericValue = parseFloat(normalized);
        if (!isNaN(numericValue)) {
          if (Math.floor(numericValue) === numericValue) {
            normalized = String(parseInt(numericValue, 10));
          } else {
            normalized = String(numericValue).replace(/(\.\d*?[1-9])0+$/, '$1').replace(/\.0+$/, '');
          }
        }
      }

      return normalized ? 'amd-' + normalized : '';
    }

    function formatAmendmentLabel(value) {
      var normalized = normalizeAmendmentYear(value);
      if (!normalized) {
        return '';
      }

      return 'AMD-' + normalized.replace(/^amd-/, '');
    }

    function setSelectedYearDisplay(value) {
      var normalized = normalizeAmendmentYear(value);
      var label = formatAmendmentLabel(value);

      $('.selected-year-name')
        .text(label)
        .attr('data-year-value', normalized);

      return normalized;
    }

    function getSelectedYearValue() {
      var selectedEl = $('.selected-year-name');
      var storedValue = (selectedEl.attr('data-year-value') || '').trim();
      if (storedValue !== '') {
        return normalizeAmendmentYear(storedValue);
      }

      return normalizeAmendmentYear(selectedEl.text());
    }

    yeardataset();
    $('.amendmentyear').change(function () {
      // Get the selected value
      var selectedYear = $(this).val(); 
      console.log("Selected year " +selectedYear); 
      setSelectedYearDisplay(selectedYear);
      yeardataset();
    });
  
    function yeardataset() {
      var selectedYear = setSelectedYearDisplay($('.amendmentyear').val());
      if ($('.checklistyearyear').val() != $('.amendmentyear').val()) {
        $('.amendmentyear').closest('table').find('input[name*="year"]').each(function () {
          $(this).val(selectedYear);
          
        });
      }
    }
  
  
    if ($("#buildamendmentform tr").length == 1) { $("#amendmentsTableHeader").hide(); }
    setAttachmentUpload();
    function setAttachmentUpload() {
      $('.morefiles').each(function () {
        $(this).fileupload({
          dataType: 'json',
          fileInput: $(this),
          add: function (e, data) {
            data.context = $(this).closest('.control-group');
            if (!data.context.find('.more_progress').length) {
              data.context.append('\
                        <div class="progress progress-striped active" style="width: 85%; margin-top: 5px;"> \
                          <div class="bar" style="width: 0%;"></div> \
                        </div>');
            }
            data.context.find('.more_progress .bar').css('width', '0%');
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
            data.context.find('.more_progress').show().find('.bar').css('width', progress + '%');
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
              data.context.find('[name*="id"]').val(data.result.content.Attachment.id);
              data.context.find('[name*="basename"]').val(data.result.content.Attachment.basename);
              data.context.find('[name*="dirname"]').val(data.result.content.Attachment.dirname);
              data.context.find('[name*="checksum"]').val(data.result.content.Attachment.checksum);
              data.context.closest('.fieldwrapper').find('.remove-row-amendment').val(data.result.content.Attachment.id);
              data.context.closest('.fieldwrapper').find('.server-id').val(data.result.content.Attachment.id);
              data.context.find('.morefiles').remove();
              data.context.find('.more_progress').fadeOut('slow');
            } else {
              data.context.append('<div class="alert alert-error"> \
                        <a class="close" data-dismiss="alert" href="#">&times;</a> \
                        <p>'+ data.result.errors + '</p> </div>');
              data.context.find('.more_progress').fadeOut('slow');
            }
          }
        })
      });
    }
    // incremental development
    $("#addAttachmentA").click(function () {
      $("#amendmentsTableHeader").show();
      var intId = $("#buildamendmentform tr").length - 1;
      if ($('#buildamendmentform :input[type="file"]').length < 10) {
        $("#buildamendmentform").append(constructATr(intId));
        setAttachmentUpload();
      } else {
        alert("Sorry, cant save more than Four Attachments at a time!");
      }
    });
    function constructATr(intId) {
      var intId2 = intId + 1;
      var otherPValue = getSelectedYearValue();
      var trWrapper = '\
          <tr class="fieldwrapper" id="field{i}">\
          <td>{i2}</td>\
          <td><div class="control-group">\
              <input type="hidden" id="AmendmentChecklist{i}Model" name="data[AmendmentChecklist][{i}][model]" value="AmendmentChecklist">\
              <input type="hidden" id="AmendmentChecklist{i}Group" name="data[AmendmentChecklist][{i}][group]" value="amendment">\
              <input type="hidden" id="AmendmentChecklist{i}Year" name="data[AmendmentChecklist][{i}][year]" value="{year}">\
              <input type="hidden" id="AmendmentChecklist{i}Dirname" name="data[AmendmentChecklist][{i}][dirname]">\
              <input type="hidden" id="AmendmentChecklist{i}Basename" name="data[AmendmentChecklist][{i}][basename]">\
              <input type="hidden" id="AmendmentChecklist{i}Checksum" name="data[AmendmentChecklist][{i}][checksum]">\
              <input type="hidden" id="AmendmentChecklist{i}Checksum" name="data[AmendmentChecklist][{i}][server-id]">\
              <input type="hidden" id="AmendmentChecklist{i}Id" class="" \
                  name="data[AmendmentChecklist][{i}][id]"><input type="file" id="AmendmentChecklist{i}File" class="span12 morefiles" \
                  name="data[AmendmentChecklist][{i}][file]"  data-items="4"  autocomplete="off" >\
          </div></td>\
          <td><div class="control-group"><textarea id="AmendmentChecklist{i}Description"  rows="1" \
                 name="data[AmendmentChecklist][{i}][description]" class="span11 otherdescription"></textarea></div></td>\
                 <td width="10%"><input type="text" class="version_no" id="AmendmentChecklist{i}Version" name="data[AmendmentChecklist][{i}][version_no]"></td>\
                 <td><input type="date" class="amendment_date" id="AmendmentChecklist{i}File" name="data[AmendmentChecklist][{i}][file_date]"></td>\
          <td><button  type="button" class="btn-primary update-row-amendment tiptip" data-original-title="Save File">\
                        &nbsp;<i class="icon-save"></i>&nbsp;</button></td>';
  
      $('.tiptip').tooltip();
      return trWrapper.replace(/{i}/g, intId).replace(/{i2}/g, intId2).replace(/{year}/g, otherPValue);
    }
  
    function update_description() {
      var tr = $(this).closest('.fieldwrapper');
      if (tr.find('.morefiles').length && tr.find('.morefiles').val() === '') {
        alert('Please upload the current file before adding another one');
        return;
      }
      if (tr.find('.otherdescription').val().trim() === '') {
        alert('Please provide description');
        return;
      }
      if (tr.find('.version_no').val().trim() === '') {
        alert('Please select version');
        return;
      }
      if (tr.find('.amendment_date').val().trim() === '') {
        alert('Please enter date');
        return;
      }
      var otherPValue = getSelectedYearValue();
  
      console.log('Amendment ID:', otherPValue);
      var serverId = tr.find('input:hidden[id^="AmendmentChecklist"][name$="[server-id]"]').val();
  
      // Continue with other operations using the retrieved serverId if needed
  
      // Example: Log the retrieved serverId to the console
      console.log('Server ID:', serverId);
  
      //upload this throug ahax api call
  
      var description = tr.find('.otherdescription').val().trim();
      var version = tr.find('.version_no').val().trim();
      var date = tr.find('.amendment_date').val().trim();
  
      // Block the UI
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
  
      // Prepare data to be sent via AJAX
      var formData = {
        amendmentId: otherPValue,
        serverId: serverId,
        description: description,
        version: version,
        date: date
        // Include other data as needed
      };
  
      // Perform AJAX call
      $.ajax({
        url: '/attachments/update_amendment/'+serverId+'.json', // Specify your API endpoint
        type: 'POST', // Adjust HTTP method if needed
        dataType: 'json', // Adjust data type if needed
        data: formData,
        success: function (response) {
          // Unblock the UI
          $.unblockUI(); 
          console.log('Upload successful:', response); 
        },
        error: function (xhr, status, error) {
          // Unblock the UI
          $.unblockUI(); 
          console.error('Upload error:', status, error); 
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
