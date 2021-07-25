# Database Design

## Tables

### organizers
the role of sponsors
matched with tutors via affiliations

### tutors
tutor_id
volunteer_id
first_name
last_name
phone_number
target_audience
availability (may need to join with an availibility table of scheduling times --- future feature)
expertise (join with strengths table to store what skills are best)

### affiliations
use existing one to find out which sponsors manage which volunteers

## learners
(wondering if this is actually needed, or if we can fold this into the assignments)
Student Name *
Student Email *
Student Phone Number
Parent Name
Parent Email
Parent Phone Number
Student Grade *
What subject do you need tutoring for? (please be as specific as possible, make sure to mention AP or Pre-AP if applicable) *
What is your availability? (please be as specific as possible, giving dates and times preferred) *
If you would like to request a specific person, please type their name here

### subjects
---------
subject_id
subject_name

### strengths
---------
tutor_id
subject_id

### weaknesses 
(there might be a better word, but this is a good antonym to match the strengths table)
might be unnecessary if we fold this into assignments
---------
learner_id
subject_id

### assignments / matches
-----------
tutor_id (aka volunteer_id)
learner_id (aka volunteer_id)
focus_area (aka subject_id) what needs to be worked on?
