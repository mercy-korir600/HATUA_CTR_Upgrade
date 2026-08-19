$(function() {
    // Multi Drugs Handling
    $("#addSuspectedDrug").on("click", addSuspectedDrugs);
    $(document).on('click', '.removeSuspectedDrug', removeSuspectedDrug);

    // Multi Drugs Handling
    function addSuspectedDrugs() {
        var se = $("#suspected-drugs .suspected-group").last().find('button').attr('id');
        if ( typeof se !== 'undefined' && se !== false && se !== "") {
            intId = parseFloat(se.replace('suspected_drugsButton', '')) + 1;
        } else {
            // Server-rendered rows are 0-indexed (data[SuspectedDrug][0], [1], ...) - start
            // dynamically-added rows at 0 too, otherwise the first JS-added row lands on index 1
            // and the two numbering schemes drift apart across saves.
            intId = 0;
        }
        if ($("#suspected-drugs .suspected-group").length < 9) {
            var new_suspectdrug = $('<div class="suspected-group">\
    <div class="row-fluid">\
        <div class="span6">\
          <input type="hidden" name="data[SuspectedDrug][{i}][id]" class="" id="SuspectedDrug{i}Id">\
          <div class="control-group">\
            <label for="SuspectedDrug{i}GenericName" class="control-label required">Generic Name <span class="sterix">*</span></label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][generic_name]" class="" maxlength="100" type="text" id="SuspectedDrug{i}GenericName">\
            </div>\
          </div>\
          <div class="control-group">\
            <label for="SuspectedDrug{i}Dose" class="control-label required">Dose <span class="sterix">*</span></label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][dose]" class="" maxlength="1{i}{i}" type="text" id="SuspectedDrug{i}Dose">\
            </div>\
          </div>\
          <div class="control-group">\
            <label for="SuspectedDrug{i}RouteId" class="control-label required">Administration Route <span class="sterix">*</span></label>\
            <div class="controls">\
              <select name="data[SuspectedDrug][{i}][route_id]" class="" id="SuspectedDrug{i}RouteId">\
              </select>\
            </div>\
          </div>\
          <div class="control-group">\
            <label for="SuspectedDrug{i}Indication" class="control-label required">Indication for use <span class="sterix">*</span></label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][indication]" class="" maxlength="255" type="text" id="SuspectedDrug{i}Indication">\
            </div>\
          </div>\
          <div class="control-group">\
            <label class="control-label required">Did reaction abate after stopping drug? <span class="sterix">*</span> </label>\
            <input type="hidden" value="" id="SuspectedDrug{i}reaction_abate" name="data[SuspectedDrug][{i}][reaction_abate]"> \
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_abate]" id="SuspectedDrug{i}ReactionAbateYes" class="answer{i}" value="Yes">Yes</label>\
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_abate]" id="SuspectedDrug{i}ReactionAbateNo" class="answer{i}" value="No">No</label>\
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_abate]" id="SuspectedDrug{i}ReactionAbateNA" class="answer{i}" value="N/A">N/A</label>\
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection" onclick="$(\'.answer{i}\').removeAttr(\'checked disabled\')">\
                    <em class="accordion-toggle"><i class="icon-remove-circle"></i> </em></a> </span>\
                   </div>\
          </div>\
        <div class="span6">\
          <div class="control-group">\
            <label for="SuspectedDrug{i}DateFrom" class="control-label required">Therapy Date <small class="muted">(from)</small>  <span class="sterix">*</span></label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][date_from]" class="datepickers" type="text" id="SuspectedDrug{i}DateFrom">\
            </div>\
          </div>\
          <div class="control-group">\
            <label for="SuspectedDrug{i}DateTo" class="control-label required">Therapy Date <small class="muted">(to)</small> </label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][date_to]" class="datepickers" type="text" id="SuspectedDrug{i}DateTo">\
            </div>\
          </div>\
          <div class="control-group">\
            <label for="SuspectedDrug{i}TherapyDuration" class="control-label required">Therapy duration</label>\
            <div class="controls">\
              <input name="data[SuspectedDrug][{i}][therapy_duration]" class="" maxlength="255" type="text" id="SuspectedDrug{i}TherapyDuration">\
            </div>\
          </div>\
          <div class="control-group">\
            <label class="control-label required">Did reaction reappear after reintroduction? <span class="sterix">*</span> </label>\
            <input type="hidden" value="" id="SuspectedDrug{i}reappear" name="data[SuspectedDrug][{i}][reaction_reappear]"> \
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_reappear]" id="SuspectedDrug{i}ReactionReappearYes" class="reappear{i}" value="Yes">Yes</label>\
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_reappear]" id="SuspectedDrug{i}ReactionReappearNo" class="reappear{i}" value="No">No</label>\
            <label class="radio inline"><input type="radio" name="data[SuspectedDrug][{i}][reaction_reappear]" id="SuspectedDrug{i}ReactionReappearNA" class="reappear{i}" value="N/A">N/A</label>\
                    <span class="help-inline" style="padding-top: 5px;"><a class="tooltipper" data-original-title="Clear selection" onclick="$(\'.reappear{i}\').removeAttr(\'checked disabled\')">\
                    <em class="accordion-toggle"><i class="icon-remove-circle"></i> </em></a> </span>\
          </div>\
        </div>\
        <div class="row-fluid">\
          <div class="span12">\
            <div class="controls"><button type="button" id="suspected_drugsButton{i}" class="btn btn-small btn-danger removeSuspectedDrug"><i class="icon-trash"></i> Remove Suspected Drug</button></div> \
            <hr id="SuspectedDrugHr{i}">\
          </div>\
        </div>\
    </div>\
  </div>\
</div>'.replace(/{i}/g, intId));
            // console.log(se.replace(/\d/, '1441441'));
            $(new_suspectdrug).find('[name*="route_id"]').append($("#SaeLiboso > option").clone()).val('');
            $("#suspected-drugs").append(new_suspectdrug);
        } else {
            alert("Sorry, cant add more than "+$("#suspected-drugs .suspected-group").length+" Suspected Drugs!");
        }

        $( ".datepickers" ).datepicker({
            minDate:"-100Y", maxDate:"-0D", dateFormat:'dd-mm-yy', showButtonPanel:true, changeMonth:true, changeYear:true,
            yearRange: "-100Y:+0",
            buttonImageOnly:true, showAnim:'show', showOn:'both', buttonImage:'/img/calendar.gif'
        });
    }

    function removeSuspectedDrug() {
        intId = parseFloat($(this).attr('id').replace('suspected_drugsButton', ''));
        
        var inputVal = $('#SuspectedDrug'+ intId +'Id').val();
        if (inputVal) {
            $.ajax({
                type:'POST',
                url:'/suspected_drugs/delete/'+inputVal+'.json',
                data:{'id': inputVal},
                success : function(data) {
                    // console.log(data);
                }
            });
        }
        $(this).closest('div.suspected-group').remove();
    }
});
