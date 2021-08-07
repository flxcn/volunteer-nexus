function loadVolunteers(){
    var select = document.getElementById("volunteersSelect");
    for(var i = 0; i < volunteers.length; i++){
    select.options[i] = new Option(volunteers[i].volunteer_name, volunteers[i].volunteer_id);
    }
}

function loadEvents(){
    var select = document.getElementById("eventsSelect");
    select.onchange = updateOpportunities;
    for(var i = 0; i < events.length; i++){
    select.options[i] = new Option(events[i].event_name, events[i].event_id);
    }
}

function updateOpportunities(){
    var eventSelect = this;
    var eventId = this.value;
    var opportunitySelect = document.getElementById("opportunitiesSelect");
    opportunitiesSelect.options.length = 0; // clear previous options
    opportunitiesSelect.options[0] = new Option('Select Opportunity', '{"opportunity_id":"","contribution_value":""}');
    if (typeof opportunities[eventId] != 'undefined') {
    for(var i = 0; i < opportunities[eventId].length; i++){
        var opportunityValue = [opportunities[eventId][i].opportunity_id, opportunities[eventId][i].contribution_value];
        opportunitiesSelect.options[1+i] = new Option(opportunities[eventId][i].opportunity_name, JSON.stringify(opportunityValue));
    }
    opportunitySelect.onchange = updateContributionValue;
    }
}

function updateContributionValue(){
    var opportunitySelect = document.getElementById('opportunitiesSelect');
    var contributionValue = document.getElementById('contributionValue');
    var opportunityValues = JSON.parse(opportunitySelect.value);
    contributionValue.value = opportunityValues[1];
    // console.log(opportunityValues[1]);
}

function updateContributionValue(){
    var opportunitySelect = document.getElementById('opportunitiesSelect');
    var contributionValue = document.getElementById('contributionValue');
    var opportunityValues = JSON.parse(opportunitySelect.value);
    contributionValue.value = opportunityValues[1];
    //console.log(opportunityValues[1]);
}

function validateForm() {
    console.log(document.forms["engagementForm"]["volunteersSelect"].value);
    console.log(document.forms["engagementForm"]["eventsSelect"].value);

    // validate submittedVolunteerId
    var submittedVolunteerId = document.forms["engagementForm"]["volunteersSelect"].value;
    if (submittedVolunteerId == "") {
        alert("Please select a volunteer.");
        return false;
    }
    volunteer_id = submittedVolunteerId;

    // validate submittedEventId
    var submittedEventId = document.forms["engagementForm"]["eventsSelect"].value;
    if (submittedEventId == "") {
        alert("Please select an event.");
        return false;
    }
    eventId = submittedEventId;

    // validate submittedOpportunityValues
    var submittedOpportunityValues = document.forms["engagementForm"]["opportunitiesSelect"].value;
    var submittedOpportunityValuesArray = JSON.parse(submittedOpportunityValues);
    var submittedOpportunityId = submittedOpportunityValuesArray['opportunity_id'];
    if (submittedOpportunityId == "") {
        alert("Please select an opportunity.");
        return false;
    }

    return true;
}