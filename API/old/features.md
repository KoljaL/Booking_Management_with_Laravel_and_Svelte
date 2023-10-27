
# Features inna di Gherkin style

## Preparation

Before we start coding, we need to prepare the project. We will use Laravel as backend and SvelteKit as frontend. We will use the same top-level domain for both, so we can use JWT for authentication. The backend will be a API only project, so we can use Sanctum for authentication. The frontend will be a SPA, so we can use the same top-level domain for both.
A detailed list for the preparation can be found in the [preparation.md](preparation.md) file.



## Features


### Staff authentication [#3](https://github.com/KoljaL/Bootcamp-Abschluss/issues/3)
```gherkin
Scenario    Staff login successfully
  Given     I am a staff member
  When      I login with the correct credentials
    Then    I should be logged in until page reload
    And     I should be redirected to "./staff/bookings"

Scenario    Staff login with wrong credentials
  Given     I am a staff member
  When      I login with the wrong credentials
    Then    I should not be logged in
    And     I should see an error message
    And     There should be an password reset link
``` 

### Staff CRUD Staff [#15](https://github.com/KoljaL/Bootcamp-Abschluss/issues/15)
```gherkin
Scenario  CRUD Staff user
  Given     I am a Staff user with admin rights
  When      I click on "Admin" in the navigation
  Then      I should see a table of all Staff users
    When    I click on a Staff user
    Then    I should see a form to edit the Staff user
    When    I click on the button to delete the Staff user
    Then    I should see a confirmation dialog
    When    I confirm the deletion
    Then    I should see a success message
  And       I should see a button to add a new Staff user
  When      I click on the button to add a new Staff user
    Then    I should see a form to add a new Staff user
    When    I fill in the form and click on the button to save
    Then    I should see a success message
```

### Staff CRUD Member [#16](https://github.com/KoljaL/Bootcamp-Abschluss/issues/16)
```gherkin
Scenario  CRUD Member user
  Given     I am a Staff user and have a specific Location
  When      I click on "Member" in the navigation
  Then      I should see a table of all Member users of my Location
    When    I click on a Member user
    Then    I should see a form to edit the Member user
    When    I click on the button to delete the Member user
    Then    I should see a confirmation dialog
    When    I confirm the deletion
    Then    I should see a success message
  And       I should see a button to add a new Member user
  When      I click on the button to add a new Member user
    Then    I should see a form to add a new Member user
    When    I fill in the form and click on the button to save
    Then    I should see a success message
```

### Staff CRUD Booking [#17](https://github.com/KoljaL/Bootcamp-Abschluss/issues/17)
```gherkin
Scenario  CRUD Bookings
  Given     I am a Staff user and have a specific Location
  When      I click on "Bookings" in the navigation
  Then      I should see a table of all Bookings of my Location for this day
    When    I click on a Booking
    Then    I should see a form to edit the Booking
    When    I click on the button to delete the Booking
    Then    I should see a confirmation dialog
    When    I confirm the deletion
    Then    I should see a success message
  And       I should see a button to add a new Booking
  When      I click on the button to add a new Booking
    Then    I should see a form to add a new Booking
    When    I fill in the form and click on the button to save
    Then    I should see a success message
    And     I should see the new Booking in the table
  When      I clicked the Datepicker
    Then    I should see a calendar
    When    I click on a date
    Then    I should see the Bookings for this day
  When      I clicked the Member drop down menu
    Then    I should see a list of all Members
    When    I click on a Member
    Then    I should see the Bookings for this Member
```

### Member authentication [#5](https://github.com/KoljaL/Bootcamp-Abschluss/issues/5)
```gherkin
Scenario    Member login successfully
  Given     I am a Member user
  When      I login with the correct credentials
    Then    I should be logged in until page reload
    And     I should see my bookings

Scenario    Member login with wrong credentials
  Given     I am a Member user
  When      I login with the wrong credentials
    Then    I should not be logged in
    And     I should see an error message
    And     There should be an password reset link
``` 

### Member CRUD Booking [#18](https://github.com/KoljaL/Bootcamp-Abschluss/issues/18)
```gherkin
Scenario    CRUD Bookings
  Given     I am a Member user and logged in
  Then      I should see a grid of cards with my Bookings
    When    I click on a Booking
    Then    I should see a form to edit the Booking
    When    I click on the button to delete the Booking
    Then    I should see a confirmation dialog
    When    I confirm the deletion
    Then    I should see a success message
  And       I should see a button to add a new Booking
  When      I click on the button to add a new Booking
    Then    I should see a form to add a new Booking
    When    I fill in the form and click on the button to save
    Then    I should see a success message
    And     I should see the new Booking card in the grid
  When      I have no Bookings
    Then    I should see a message that I have no Bookings
  When      I have reached my limit of Bookings
    Then    I should see a message that I have reached my limit of Bookings
    And     I should not see a button to add a new Booking
```

### Email notification on new Member [#20](https://github.com/KoljaL/Bootcamp-Abschluss/issues/20)
```gherkin
Scenario    Email notification on new Member
  Given     I am a Staff user and have a specific Location
  When      I add a new Member
  Then      I should receive an email with the details of the Member
  And       The Location should receive an email with the details of the Member
```


### Email notification on booking [#19](https://github.com/KoljaL/Bootcamp-Abschluss/issues/19)
```gherkin
Scenario    Email notification on booking
  Given     I am a Member user and logged in
  When      I add a new Booking
  Then      I should receive an email with the details of the Booking
  And       The Location should receive an email with the details of the Booking
```

### Admin view [#21](https://github.com/KoljaL/Bootcamp-Abschluss/issues/21)
```gherkin
Scenario    Admin view Bookings
  Given     I am a Staff user with admin rights
  When      I click on "Bookings" in the navigation
  Then      I should see a drop down menu with all Locations
  When      I click on a Location
  Then      I should see a table of all Bookings of this Location  

Scenario    Admin view Members
  Given     I am a Staff user with admin rights
  When      I click on "Member" in the navigation
  Then      I should see a drop down menu with all Locations
  When      I click on a Location
  Then      I should see a table of all Members of this Location  
```