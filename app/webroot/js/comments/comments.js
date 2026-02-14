$(function() {
    $(document).on('click', '.remove-attachment', remove_attachment);
    var intId = 0;
    var trWrapper = '\
          <div class="row-fluid attacho">\
            <div class="span10"><input name="data[Attachment][{i}][id]" id="attachments-{i}-id" type="hidden"> \
                <input name="data[Attachment][{i}][file]" id="attachments-{i}-file" type="file" class="firo"> \
                <input type="hidden" id="attachments-{i}-model" value="Comments" name="data[Attachment][{i}][model]" style="display: inline;">\
                <input type="hidden" id="attachments-{i}-category" value="{n}" name="data[Attachment][{i}][category]" style="display: inline;">\
                <textarea name="data[Attachment][{i}][description]" id="attachments-{i}-description" class="flow"\
                          placeholder="descripton" cols="16" rows="1"></textarea> \
            </div>\
            <div class="span2">\
                <button type="button" class="btn btn-danger btn-small remove-attachment"><i class="icon-minus"></i></button>\
            </div>\
          </div><hr>\ ';
    // incremental development
    $(document).on('click', '.addUpload', function() {
      var $button = $(this);
      var $form = $button.closest('form');
      var $uploadsTable = $button.closest('div.uploadsTable');
      var attachmentCount = $uploadsTable.children('div.attacho').length;
      var attachmentCategory =
        $uploadsTable.data('attachmentCategory') ||
        $form.find('input[name="data[Comment][model]"]').val() ||
        $form.find('input[id$="Model"]').val() ||
        'Comment';

      if (attachmentCount >= 7) {
        alert("Sorry, can't add more than 7 attachments at a time!");
        return;
      }

      intId = intId + 1;
      var trVar = $.parseHTML(trWrapper.replace(/{i}/g, intId).replace(/{n}/g, attachmentCategory));
      $uploadsTable.append(trVar);
    });

    function remove_attachment() {
      $(this).closest('.attacho').remove();        
    }
    

});
