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
3. [x] I a non existing Member is requested, there will be an ugly response:`"message": "No query results for model [App\\Models\\Member]`

# ToDo
- [ ] better error messages:
- [ ] - if a Member is deleted, there should be a response
- [ ] - if an email is already in use and a new Member is created with this email, there should be a response

## Links:
- [How to Send Email in Laravel 9 using SMTP | All PHP Tricks](https://www.allphptricks.com/how-to-send-email-in-laravel-9-using-smtp/)
- [Building a Role-Based REST API with Laravel Sanctum](https://www.amezmo.com/laravel-hosting-guides/role-based-api-authentication-with-laravel-sanctum)
- [Laravel Telescope - Laravel 10.x - The PHP Framework For Web Artisans](https://laravel.com/docs/10.x/telescope)
- [Junior Code Review: Laravel Routes, Middleware, Validation and more - YouTube](https://www.youtube.com/watch?v=sukS7QOBpK0)
- [7 "Tricks" With dd() in Laravel](https://laraveldaily.com/post/7-tricks-with-dd-in-laravel)
- [Adding some Laravel magic to your Eloquent joins](https://kirschbaumdevelopment.com/insights/power-joins)
- 


## Responses and Status Codes

All requests and responses are of an application/json content type and follow typical HTTP response status codes for success and failure.

- **200** - Everything is OK
- **201** - Created Successfully
- **202** - Accepted
- **204** - No Content
- **301** - Moved Permanently
- **400** - Bad Request
- **401** - Unauthorized
- **404** - Not Found
- **500** - Internal Server Error

The API follows the general rule that all **200** codes are deemed successful, **300** codes denote a redirection, **400** codes are client errors and **500** codes are server errors.



## [Actions Handled By Resource Controller](https://laravel.com/docs/10.x/controllers#actions-handled-by-resource-controller)
| Verb       | URI                     | Action | Route Name      |
|------------|-------------------------|--------|-----------------|
| GET        | `/photos`               | index  | photos.index    |
| GET        | `/photos/create`        | create | photos.create   |
| POST       | `/photos`               | store  | photos.store    |
| GET        | `/photos/{photo}`       | show   | photos.show     |
| GET        | `/photos/{photo}/edit`  | edit   | photos.edit     |
| PUT/PATCH  | `/photos/{photo}`       | update | photos.update   |
| DELETE     | `/photos/{photo}`       | destroy| photos.destroy  |