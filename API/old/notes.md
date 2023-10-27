# Notes


## Links Laravel
- [installing Laravel with SPA Auth, USING THE SAME TOP-LEVEL DOMAIN](https://cdruc.com/laravel-spa-auth-extended)
- https://dev.to/shanisingh03/laravel-api-authentication-using-laravel-sanctum-edg
- https://cdruc.com/laravel-spa-auth
- https://www.allphptricks.com/laravel-custom-user-registration-and-login-tutorial/
- [API best practices](https://dev.to/rebelnii/building-a-restful-api-with-laravel-best-practices-and-implementation-tips-1lok)

## Links Svelte
- [Formsnap - a11y & validation](https://www.formsnap.dev/docs/introduction)
- [fetch wrapper](https://rodneylab.com/using-fetch-sveltekit/)
- [better fretch wrapper](https://github.com/kwhitley/itty-fetcher)
- [Svelte fluid css units](https://github.com/jshikanova/svelte-fluid-css-units)





## Laravel install steps

- Install Laravel in `API` folder: `laravel new API`
- set sqlite in `.ENV` and remove other DB settings
- Create database file: `touch datatbase/database.sqlite`
- Create [Models](https://github.com/KoljaL/Bootcamp-Abschluss/issues/2) with migration, seeder, factory and controller:
- `php artisan make:model Location -mfsc`
- `php artisan make:model Staff -mfsc`
- `php artisan make:model Member -mfsc`
- `php artisan make:model Booking -mfsc`
- [API/database/migrations](API/database/migrations)
- Each [factory](API/database/factories) get called in the [seeder](API/database/seeders) file, all seeders get called in the [DatabaseSeeder](API/database/seeders/DatabaseSeeder.php#L13-L18) file
- Migrate all models: `php artisan migrate:fresh --seed`

## SvelteKit install steps

- npm init svelte@next sveltekit
 

 