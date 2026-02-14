$(function() {
    $(document)
        .off('click.investigatorRemove', '.removePIContact')
        .on('click.investigatorRemove', '.removePIContact', removePIContact);

    // Fallback for environments where delegated click binding is interfered with.
    window.addInvestigatorContactByRole = function(role) {
        addInvestigatorContact(role);
        return false;
    };

    function getNextInvestigatorIndex() {
        var maxIndex = -1;
        $('input[name^="data[InvestigatorContact]"]').each(function() {
            var inputName = $(this).attr('name');
            var match = inputName.match(/^data\[InvestigatorContact\]\[(\d+)\]/);
            if (match && parseInt(match[1], 10) > maxIndex) {
                maxIndex = parseInt(match[1], 10);
            }
        });

        return maxIndex + 1;
    }

    function buildInvestigatorContact(index, role) {
        var sectionClass = 'principal-group';
        var removeLabel = 'Remove Contact';
        var header = '<p class="topper" id="InvestigatorContactLabel{i}">{i} additional contacts</p>';
        var badge = '<span class="badge badge-info">{i}</span>';

        if (role === 'co_pi') {
            sectionClass = 'co-pi-group';
            removeLabel = 'Remove Co-PI';
            header = '';
            badge = '';
        } else if (role === 'co_i') {
            sectionClass = 'co-i-group';
            removeLabel = 'Remove Co-I';
            header = '';
            badge = '';
        }

        var template = '<div class="contact-group {sectionClass}"> \
                        {header} \
                        {badge} \
                        <input type="hidden" id="InvestigatorContact{i}InvestigatorRole" name="data[InvestigatorContact][{i}][investigator_role]" value="{role}"> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}GivenName">Given name <span class="sterix">*</span></label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}GivenName" maxlength="100" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][given_name]"></div></div> \
                        <div class="control-group"><label class="control-label" for="InvestigatorContact{i}MiddleName">Middle name, if applicable</label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}MiddleName" maxlength="100" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][middle_name]"></div></div> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}FamilyName">Family name <span class="sterix">*</span></label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}FamilyName" maxlength="100" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][family_name]"></div></div> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}Qualification">Qualification <span class="sterix">*</span></label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}Qualification" maxlength="255" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][qualification]"></div></div> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}ProfessionalAddress">Professional address <span class="sterix">*</span></label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}ProfessionalAddress" maxlength="255" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][professional_address]"></div></div> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}Telephone">Telephone number <span class="sterix">*</span></label> \
                            <div class="controls"><input type="text" id="InvestigatorContact{i}Telephone" maxlength="255" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][telephone]"></div></div> \
                        <div class="control-group"><label class="control-label required" for="InvestigatorContact{i}Email">Email address <span class="sterix">*</span></label> \
                            <div class="controls"><input type="email" id="InvestigatorContact{i}Email" maxlength="255" placeholder=" " class="input-xxlarge" name="data[InvestigatorContact][{i}][email]"></div></div> \
                        <div class="controls"><button type="button" id="InvestigatorContactButton{i}" class="btn btn-mini btn-danger removePIContact">{removeLabel}</button></div> \
                        <hr id="InvestigatorContactHr{i}"> \
                        </div>';

        template = template.replace(/{i}/g, index);
        template = template.replace('{role}', role);
        template = template.replace('{sectionClass}', sectionClass);
        template = template.replace('{removeLabel}', removeLabel);
        template = template.replace('{header}', header.replace(/{i}/g, index));
        template = template.replace('{badge}', badge.replace(/{i}/g, index));

        return $(template);
    }

    function renumberAdditionalRoleContacts(role) {
        var roleConfigs = {
            co_pi: { selector: '#investigator_co_pi_contact .co-pi-group', label: 'Co-PI' },
            co_i: { selector: '#investigator_co_i_contact .co-i-group', label: 'Co-I' }
        };

        if (!roleConfigs[role]) return;

        var config = roleConfigs[role];
        var $groups = $(config.selector);

        $groups.each(function(index) {
            var $group = $(this);
            $group.children('.role-additional-topper').remove();
            $group.children('.role-additional-badge').remove();

            if (index > 0) {
                var additionalNumber = index;
                var contactLabel = (additionalNumber === 1) ? 'contact' : 'contacts';
                $('<p class="topper role-additional-topper">' + additionalNumber + ' additional ' + config.label + ' ' + contactLabel + '</p>').prependTo($group);
                $('<span class="badge badge-info role-additional-badge">' + additionalNumber + '</span>').prependTo($group);
            }
        });
    }

    function addInvestigatorContact(role) {
        if (role === 'principal' && $("#investigator_contacts .principal-group").length >= 9) {
            alert("Sorry, cant add more than " + $("#investigator_contacts .principal-group").length + " Contacts!");
            return;
        }

        var intId = getNextInvestigatorIndex();
        var newInvestigatorContact = buildInvestigatorContact(intId, role);

        if (role === 'co_pi') {
            if ($("#investigator_co_pi_contact").length) {
                $("#investigator_co_pi_contact").append(newInvestigatorContact);
            } else {
                $("#investigator_contacts").append(newInvestigatorContact);
            }
        } else if (role === 'co_i') {
            if ($("#investigator_co_i_contact").length) {
                $("#investigator_co_i_contact").append(newInvestigatorContact);
            } else {
                $("#investigator_contacts").append(newInvestigatorContact);
            }
        } else {
            $("#investigator_contacts").append(newInvestigatorContact);
        }

        if (role === 'co_pi' || role === 'co_i') {
            renumberAdditionalRoleContacts(role);
        }
    }

    function removePIContact() {
        var $group = $(this).closest('.contact-group');
        var isCoPiGroup = $group.hasClass('co-pi-group');
        var isCoIGroup = $group.hasClass('co-i-group');
        var buttonId = $(this).attr('id') || '';
        var intId = parseFloat(buttonId.replace('InvestigatorContactButton', ''));
        var inputVal = $('#InvestigatorContact' + intId + 'Id').val();
        if (inputVal) {
            $.ajax({
                type: 'POST',
                url: '/investigator_contacts/delete/' + inputVal + '.json',
                data: {'id': inputVal}
            });
        }
        $group.remove();

        if (isCoPiGroup) {
            renumberAdditionalRoleContacts('co_pi');
        }
        if (isCoIGroup) {
            renumberAdditionalRoleContacts('co_i');
        }
    }

    renumberAdditionalRoleContacts('co_pi');
    renumberAdditionalRoleContacts('co_i');
});
