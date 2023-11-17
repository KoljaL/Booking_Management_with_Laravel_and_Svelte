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

## 03.11.23
- rebuild the way to divide the User-roles (using Middleware)
- moved the code from the MemberController to the Member Model
- use Scopes to filter the Member
- use `select` & `join` to get the data from the database
- made a benchmark against `withs` and `join`is faster
- added `inviteMail` endpoint to send an email to a new Member
- added `register` endpoint to register a new Member
- use the `customJson` to format the response inkl. Exception handling



# Issues

1. [x] handle two different roles (staff, member) with the same user table is annoying. For every request we need to check the role and get the data from the right table. And both table have timestamps. Is there a better way?
2. [x] There will be no response after a Member is deleted
3. [x] I a non existing Member is requested, there will be an ugly response:`"message": "No query results for model [App\\Models\\Member]`
4. [ ] decide if we sort out the active member in the backend (/member?active) or in the frontend

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
- [Laravel Soft Deletes - Papierkorb-Funktion für Eloquent | a coding project](https://www.a-coding-project.de/ratgeber/laravel/soft-deletes)
- [Best way to flatten a json column on the model](https://laracasts.com/discuss/channels/laravel/best-way-to-flatten-a-json-column-on-the-model?page=1&replyId=906118)
- [alexeymezenin/laravel-best-practices: Laravel best practices](https://github.com/alexeymezenin/laravel-best-practices#methods-should-do-just-one-thing)
- [Global scope in Laravel, the easy way. - DEV Community](https://dev.to/baronsindo/global-scope-in-laravel-the-easy-way-7jf)
- [Move Model Scopes To Traits In Laravel | by Code Axion The Security Breach | Medium](https://medium.com/@codeaxion77/move-model-scopes-to-traits-in-laravel-a07b36cc04da)
- [sveltekit-starter/src/hooks/laravel-sanctum.ts at develop · daison12006013/sveltekit-starter](https://github.com/daison12006013/sveltekit-starter/blob/develop/src/hooks/laravel-sanctum.ts)
- [dedoc/scramble: Modern Laravel OpenAPI (Swagger) documentation generator. No PHPDoc annotations required.](https://github.com/dedoc/scramble)
- [Authentication in Svelte using cookies - LogRocket Blog](https://blog.logrocket.com/authentication-svelte-using-cookies/#what-http-cookies)
- [A simple way to enable CORS on Laravel - DEV Community](https://dev.to/keikesu0122/a-simple-way-to-enable-cors-on-laravel-55i)
### Not used, but maybe useful in the future
- [Laravel Active Trait](https://www.trovster.com/blog/2023/02/laravel-active-trait)


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