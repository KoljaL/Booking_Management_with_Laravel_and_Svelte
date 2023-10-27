# Progress

## 27.10.23
- tried to get an over view of the Laravel components: [LaravelComponents.md](API/LaravelComponents.md)
- completly new Laravel installation
- added mogration, model, controller and routes for Location, Member, Staff and Booking
- Staff and Member are using the same User table, but have different `role`  
- relationships are setted up: 
- - Location has many Staff
- - Location has many Member
- - Staff has many Member
- - Member has many Booking
- added factory and seeder for all models
- using [Bruno](https://www.usebruno.com/) for testing the API, all files are in the `Bruno` folder
- added login via API
- there are three Users to test the login: 
    - `admin@example.com`
    - `staff@example.com`
    - `member@example.com`
  Password for all is `password`
- added CRUD for `Staff` -> `Member`
- binded `name`, `email`and `role` from the `User` Modell to the `Member` Modell 
- tried to write a jurnal for the progress: [jurnal.md](API/Jurnal.md), but it is now partially outdated
- project ist ready to use, SQLite database and no secrets in the .env file
- initial commit


# Issues

1. [x] handle two different roles (staff, member) with the same user table is annoying. For every request we need to check the role and get the data from the right table. And both table have timestamps. Is there a better way?
2. [x] There will be no response after a Member is deleted
3. [ ] I a non existing Member is requested, there will be an ugly response:`"message": "No query results for model [App\\Models\\Member]`

