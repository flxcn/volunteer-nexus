function loadVolunteers() {
    var select = document.getElementById("volunteersSelect");
    for(var i = 0; i < volunteers.length; i++){
    select.options[i] = new Option(volunteers[i].volunteer_name, volunteers[i].volunteer_id);
    }
}

function loadEvents() {
    var select = document.getElementById("eventsSelect");
    select.onchange = updateOpportunities;
    for(var i = 0; i < events.length; i++){
    select.options[i] = new Option(events[i].event_name, events[i].event_id);
    }
}

function updateOpportunities() {
    var eventSelect = this;
    var eventId = this.value;
    var opportunitySelect = document.getElementById("opportunitiesSelect");
    opportunitiesSelect.options.length = 0; // clear previous options
    opportunitiesSelect.options[0] = new Option('Select Opportunity', '');
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
    //console.log(opportunityValues[1]);
}

function validateForm() {
    console.log(document.forms["engagementForm"]["event_name"].value);
    console.log(document.forms["engagementForm"]["opportunity_name"].value);


    // validate submittedEventId
    var submittedEventId = document.forms["engagementForm"]["event_name"].value;
    if (submittedEventId == "") {
    alert("Please select an event.");
    return false;
    }
    eventId = submittedEventId;

    // validate submittedOpportunityValues
    var submittedOpportunityValues = document.forms["engagementForm"]["opportunity_name"].value;
    if (submittedOpportunityValues == "") {
    alert("Please select an opportunity.");
    return false;
    }

    // parse OpportunityValues array to find opportunityId and contributionValue;
    var submittedOpportunityValuesArray = JSON.parse(submittedOpportunityValues);
    var submittedOpportunityId = submittedOpportunityValuesArray[0];
    opportunityId = submittedOpportunityId;
    //console.log(submittedOpportunityId);
    var submittedContributionValue = submittedOpportunityValuesArray[1];
    contributionValue = submittedContributionValue;
    //console.log(submittedContributionValue);

    document.getElementById('eventsSelect').disabled = true;
    document.getElementById('opportunitiesSelect').disabled = true;

    var initialForm = document.getElementById("initialForm");
    console.log(initialForm)
    if (initialForm.style.display === "none") {
    initialForm.style.display = "block";
    } else {
    initialForm.style.display = "none";
    }

    // QuaggaJS
    var scanningArea = document.getElementById("scanningArea");
    if (scanningArea.style.display === "none") {
        scanningArea.style.display = "block";
    } else {
        scanningArea.style.display = "none";
    }

    return true;
}


function confirmAttendance(volunteerId) {
    if (window.XMLHttpRequest)
    {
    var http = new XMLHttpRequest();
    }
    var url = 'attendance-anywhere-process.php';
    var params =
    'sponsor_id=' + sponsorId +
    '&event_id=' + eventId +
    '&opportunity_id=' + opportunityId +
    '&volunteer_id=' + volunteerId +
    '&contribution_value=' + contributionValue;
    http.open('POST', url, true);

    //Send the proper header information along with the request
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function() {
    //Call a function when the state changes.
    if(this.readyState == 4 && this.status == 200) {
        alert(http.responseText);
    }
    }
    http.send(params);
}
