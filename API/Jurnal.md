# Laravel Booking System

In this article I will describe my first steps with Laravel and OOP. The poject which I will build is a booking system for a fictional company, which offers workspaces on different locations. It is the final project for my [Coding Bootcamp](https://www.coding-bootcamps.eu/).
 
**Technologies**
- Backend: [Laravel](https://laravel.com)
- Frontend: [SvelteKit](https://kit.svelte.dev/)
- Database: [SQLite](https://www.sqlite.org)
- API-Testing: [Bruno](https://www.usebruno.com/)

> OOP is very different from procedural programming. It is not just a different way of writing code, it is a different way of thinking about code. Everything is an object, and objects interact with each other through methods and properties. So it feels like everything is connected to everything else. Laravel is a popular and very powerfull PHP framework, which has been developed over years from an uncountable number of developers. So I will trust them and try to follow the Laravel way of OOP.

## Description  

The company operates CoworkingSpaces in several cities, each of this spaces is called a `Location`. 
To manage these spaces, every `Location` has minimum one worker, called `Staff`. 
A `Staff` can create an account for customer, called `Member` and send them a welcome mail with a link to set a password.
After the `Member` has set a password, he can login to the system and manage his `Bookings` on his `Location`.

### Permissions
- Both, `Staff` and `Member` are bind to their `Location`
- Every `Staff` can create, read, update and delete `Member` for his `Location`. Even if The `Member` was created by another `Staff`.

- A `Member` can only create `Bookings` for his `Location`, the maximum amount of `Bookings` depends on the `Location`
- A `staff` is bind to one `location` and can only manage `bookings` and `member` for his `location`.
- For creating a `staff` account, there is an admin account, which can create `staff` accounts for all `locations`.

### Data relations
There are four tables in the database: `locations`, `staff`, `member` and `bookings`.

- `location` has no dependencies
- `staff` depends on `location`
- `member` depends on `location` and `staff`
- `booking` depends on `member` and `location`

 
# My first steps with Laravel

## Development Environment, Composer and Laravel Installer


### Development Environment with Herd
[Herd](https://herd.laravel.com) is used to manage the local development environment.
It can manage multiple projects, their dependencies and although it is designed for Laravel, it can be used for any PHP project.
The only stepp after installing Herd is to add the path to the project or just the folder where you store all your projects.

### Composer and Laravel Installer 
[Composer](https://getcomposer.org/) is a dependency manager for PHP. After installing it, we use it to install the Laravel installer globally.  
`composer global require laravel/installer`

### Laravel 
To create a new Laravel project inside the current folder, we use the following command:  
`laravel new example-app`

The installer will ask you, if you want to install a Starter-Kit, but we don't need it.
As database, we use SQLite, because it is easy to use and we don't need to configure.
And of course we want to use `git` as version control system.
The only thing we need to do is to create the SQLite file:   
`touch database/database.sqlite`

### First Start
Assuming you have installed Herd and added the path to the project, you can start the project with the following command:  
`php artisan serve`  
This will start a local development server at `http://localhost:8000`.






## Models, Migrations, Factories and Seeder
Models, migrations, and factories are essential components in Laravel development.

### Models
In Laravel, a **Model** is the fundamental component that represents and manages data within your application.
It is a class that is used to interact with the database. In Laravel, each database table has a corresponding **Model** that allows us to interact with that table.

We nee to create a **Model** for each table in our database.

```bash
php artisan make:model Location
php artisan make:model Staff
php artisan make:model Member
php artisan make:model Booking
``` 
This will create a new files for each **Model**.

```bash
/app
  /Models
    Location.php
    Staff.php
    Member.php
    Booking.php
```

For `Location`and `Booking` we don't need to change anything, but for `Staff` and `Member` we need to add the following code:

```php
// /app/Models/Staff.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model {
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

```php
// /app/Models/Member.php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model {
    use HasFactory;
    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

With this code, we tell Laravel, that the **Models** `Staff` and `Member` are related to the **Model** `User`.
The Model `User` is already created by Laravel, but we need to add the relation to the **Models** `Staff` and `Member`.

```php
// /app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    // constans for each role
    const ROLE_STAFF = 'staff';
    const ROLE_MEMBER = 'member';

    // relation to staff 
    public function member() {
        return $this->hasOne(Member::class);
    }
    // relation to member
    public function staff() {
        return $this->hasOne(Staff::class);
    }

    // The attributes that are mass assignable.
    protected $fillable = [
        'name',
        'email',
        'password',
        // add role to fillable
        'role',
    ];

    // The attributes that should be hidden for serialization.
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // The attributes that should be cast.
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
```

### Migrations

**Migrations** are stored in the `database/migrations` directory, they include the schema for the database tables of your application. They are typically paired with **Models** to build your application's database schema.The filenames of migrations are prefixed with a timestamp, which allows Laravel to determine the order of the migrations. So we need to create a migration for each table in our database.

```bash
php artisan make:migration create_locations_table
php artisan make:migration create_staff_table
php artisan make:migration create_members_table
php artisan make:migration create_bookings_table
``` 

This will create a new file for each migration.

```bash
/database
  /migrations
    2021_09_30_000000_create_locations_table.php
    2021_09_30_000001_create_staff_table.php
    2021_09_30_000002_create_members_table.php
    2021_09_30_000003_create_bookings_table.php
```

At first we add one line to the migration file for the `User` table, to add the `role` column. This is necessary, because we want to use the `User` table for `Staff` and `Member` and we need to know which role the user has.


```php {9}
// /database/migrations/2014_10_12_000000_create_users_table.php
public function up(): void {
  Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->string('role')->default('member'); //staff, member
    $table->rememberToken();
    $table->timestamps();
  });
}
```

Now we can add the columns for each table.

```php
// /database/migrations/2021_09_30_000000_create_locations_table.php
Schema::create('locations', function (Blueprint $table) {
  $table->id();
  $table->string('city');
  $table->string('address');
  $table->integer('opening_hour_from');
  $table->integer('opening_hour_to');
  $table->string('opening_days');
  $table->string('phone');
  $table->string('email');
  $table->integer('slot_duration');
  $table->integer('max_booking');
  $table->integer('workspaces');
  $table->string('imap_host');
  $table->string('imap_pw');
  $table->timestamps();
});
```

```php
// /database/migrations/2021_09_30_000001_create_staff_table.php
Schema::create('staff', function (Blueprint $table) {
  $table->id();
  $table->unsignedBigInteger('user_id');
  $table->foreign('user_id')->references('id')->on('users');
  $table->unsignedBigInteger('location_id');
  $table->foreign('location_id')->references('id')->on('locations');
  $table->boolean('is_admin')->default(false);
  $table->string('phone');
  $table->timestamps();
});
```

```php
// /database/migrations/2021_09_30_000002_create_members_table.php
Schema::create('members', function (Blueprint $table) {
  $table->id(); // This is the same ID as the "User" table's ID
  $table->foreignId('user_id')->constrained('users');
  $table->unsignedBigInteger('staff_id'); // This is the ID of the staff member who created the member
  $table->foreign('staff_id')->references('id')->on('staff');
  $table->unsignedBigInteger('location_id'); // This is the ID of the location where the member is registered
  $table->foreign('location_id')->references('id')->on('locations');
  $table->string('phone');
  $table->string('jc_number');
  $table->integer('max_booking');
  $table->boolean('active');
  $table->boolean('archived');
  $table->timestamps();
});
```

```php
// /database/migrations/2021_09_30_000003_create_bookings_table.php
Schema::create('bookings', function (Blueprint $table) {
  $table->id();
  $table->unsignedBigInteger('member_id');
  $table->unsignedBigInteger('location_id');
  $table->date('date');
  $table->string('time');
  $table->integer('slots');
  $table->integer('state');
  $table->timestamp('started_at')->nullable();
  $table->timestamp('ended_at')->nullable();
  $table->foreign('member_id')->references('id')->on('members');
  $table->foreign('location_id')->references('id')->on('locations');
  $table->timestamps();
});
```

### Factories

**Factories** are used to generate large amounts of database records. We use them to create test data for our application.
For each table in our database, we need to create a factory.


```bash
php artisan make:factory LocationFactory
php artisan make:factory StaffFactory
php artisan make:factory MemberFactory
php artisan make:factory BookingFactory
```

This will create a new file for each factory.

```bash
/database
  /factories
    LocationFactory.php
    StaffFactory.php
    MemberFactory.php
    BookingFactory.php
```

With the help of faker, a PHP library that generates fake data for you, we can easily create realistic test data.


```php
// /database/factories/LocationFactory.php
use App\Models\Location;
...
public function definition() {
  return [
    'city' => fake()->city,
    'address' => fake()->address,
    'opening_hour_from' => fake()->randomElement([8, 9, 10, 11, 12]),
    'opening_hour_to' => fake()->randomElement([16, 17, 18, 19, 20]),
    'opening_days' => fake()->randomElement(['1,2,3,4,5', '1,2,3,4,5,6', '1,2,3,4,5,6,7']),
    'phone' => fake()->phoneNumber,
    'email' => fake()->unique()->safeEmail,
    'slot_duration' => fake()->randomElement([90, 120, 150, 180, 210, 240, 270, 300, 330, 360]),
    'max_booking' => fake()->numberBetween(3, 5),
    'workspaces' => fake()->numberBetween(10, 20),
    'imap_host' => fake()->domainName,
    'imap_pw' => fake()->password,
  ];
}
```
As you can see, we use `fake()` to generate the data. We can use `fake()` for all data, except for the `id` and the `timestamps`, because they are generated by Laravel.

```php
// /database/factories/StaffFactory.php
use App\Models\User;
use App\Models\Staff;
...
public function definition(): array {
  return [
    'user_id' => function () {
      return User::factory()->create()->id;
    },
    'phone' => $this->faker->phoneNumber,
    'is_admin' => false,
    'location_id' => function () {
      return \App\Models\Location::inRandomOrder()->first()->id;
    },
  ];
}

public function configure(): StaffFactory {
    return $this->afterCreating(function (Staff $staff) {
        $staff->user->update(['role' => 'staff']);
    });
}
```
For the `location_id` we use the already existing `Location` table, to get a random id.
To get a `user_id` for the `Staff`, we need to create a `User` first. We can do this with the `User::factory` from Laravel.
And after creating the `Staff`, we need to update the `role` of the `User` to `staff`.

```php
// /database/factories/MemberFactory.php
use App\Models\User;
use App\Models\Member;
...
public function definition(): array {
  return [
        'user_id' => function () {
            return User::factory()->create()->id;
        },
        'phone' => $this->faker->phoneNumber,
        'location_id' => function () {
            return \App\Models\Location::inRandomOrder()->first()->id;
        },
        'jc_number' => $this->faker->unique()->numberBetween(1000000000, 9999999999),
        'max_booking' => $this->faker->numberBetween(1, 10),
        'active' => $this->faker->boolean,
        'archived' => $this->faker->boolean,
        'staff_id' => function (array $attributes) {
            return \App\Models\Staff::where('location_id', $attributes['location_id'])->inRandomOrder()->first()->id ?? \App\Models\Staff::inRandomOrder()->first()->id;
        },
  ];
}
public function configure(): MemberFactory {
    return $this->afterCreating(function (Member $member) {
        $member->user->update(['role' => 'member']);
    });
}
```
Like in the `StaffMigration` we get a random `location_id` from the  `Location` table.
With this we choose a `staff_id`from a `Staff` with the same `lOcation`, because we need to know which `Staff` created the `Member`. And after creating the `Member` we need to update the `User` `role` to `member`. 

```php
// /database/factories/BookingFactory.php
public function definition(): array {
  return [
    'member_id' => function () {
       return \App\Models\Member::inRandomOrder()->first()->id;
    },
    'location_id' => function (array $attributes) {
      return \App\Models\Member::find($attributes['member_id'])->location_id;
    },
    'date' => fake()->dateTimeBetween('now', '+30 days')->format('Y-m-d'),
    'time' => fake()->dateTimeBetween('09:00', '18:00')->format('H:00'),
    'slots' => fake()->numberBetween(1, 4),
    'state' => fake()->numberBetween(1, 3),
    'started_at' => function (array $attributes) {
      return \Carbon\Carbon::parse($attributes['time'])->addMinutes(15);
    },
    'ended_at' => function (array $attributes) {
      return \Carbon\Carbon::parse($attributes['started_at'])->addMinutes(15);
    },
  ];
}
```

### Seeder

The `Factories` only create the data, but we need to tell Laravel, when and how to create the data. This is done by the `Seeder`.
Although we use the `Seeder` to create two `User`with known credentials, so we can login to the application.

To create a seeder for each table, we use the following command:  
```bash
php artisan make:seeder LocationSeeder
php artisan make:seeder StaffSeeder
php artisan make:seeder MemberSeeder
php artisan make:seeder BookingSeeder
```
This will create a new file for each seeder.

```bash
/database
  /seeders
    LocationSeeder.php
    StaffSeeder.php
    MemberSeeder.php
    BookingSeeder.php
```

```php
// /database/seeders/LocationSeeder.php
use App\Models\Location;
...
public function run() {
  Location::factory(5)->create();
}
```

```php
// /database/seeders/StaffSeeder.php
use App\Models\Staff;
...
public function run() {
  Staff::factory(5)->create();
}
```

```php
// /database/seeders/MemberSeeder.php
use App\Models\Member;
...
public function run() {
  Member::factory(10)->create();
}
```

```php
// /database/seeders/BookingSeeder.php
use App\Models\Booking;
...
public function run() {
  Booking::factory(50)->create();
}
```

Now we need to add all `Seeder` to the `DatabaseSeeder` file to run them all at once.
Although we need to add the `Seeder` in the right order, because we need to create the `Location` first, then the `Staff` and `Member` and at last the `Booking`.
At last we create our two `User` with known credentials.


```php
// /database/seeders/DatabaseSeeder.php
public function run() {
  $this->call([
    LocationSeeder::class,
    StaffSeeder::class,
    MemberSeeder::class,
    BookingSeeder::class,
  ]);
    // Create known staff user
  Staff::create([
      'user_id' => \App\Models\User::create([
          'name' => 'Staff Name',
          'email' => 'staff@example.com',
          'password' => bcrypt('password'),
          'role' => 'staff',
      ])->id,
      'is_admin' => true,
      'phone' => '0123456789',
      'location_id' => Location::inRandomOrder()->first()->id,
  ]);

  // Create known member user
  Member::create([
      'user_id' => \App\Models\User::create([
          'name' => 'Member Name',
          'email' => 'member@example.com',
          'password' => bcrypt('password'),
          'role' => 'member',
      ])->id,
      'phone' => '0123456789',
      'location_id' => Location::inRandomOrder()->first()->id,
      'jc_number' => '1234567890',
      'max_booking' => 5,
      'active' => true,
      'archived' => false,
      'staff_id' => Staff::inRandomOrder()->first()->id,
  ]);
}
```

### Run the app initialisation

Now we can run the app initialisation with the following command:
```bash
php artisan migrate:fresh --seed
```
This will create the database and all tables, add the data from the `Seeder` and run the `Seeder`.
It althougt removes all data from the database, so we can use it to reset the database.

If everything works fine, we can see the data in the database with the following command:
```bash
php artisan tinker
```
This will open the Laravel REPL, where we can interact with our application.
To see all `Location` we use the following command:
```bash
App\Models\Location::all();
```

But for me, it is easier to use [Bruno](https://www.usebruno.com/) an API Client for MacOS, or the VSCode extension [SQLite](https://marketplace.visualstudio.com/items?itemName=alexcvzz.vscode-sqlite), to see the data in the database.

## Routes and Controllers

### Routes

**Routes** are used to map URLs to controller actions. We can find the routes in the `routes` folder. As this application will be a REST API, we only use the `api.php` file.  

We need only one unprotected route, to `login` the `User`, Staff and Member using the same route for this.

- Login `POST /api/login`

All other routes are protected by Sanctum, so we need to be logged in to use them.

**Staff** 
- Get all `Member` from the same location `GET /api/member`
- Get one `Member` `GET /api/member/{member}`
- Create a new `Member` `POST /api/member`
- Edit a `Member` `PUT /api/member/{member}`
- Delete a `Member` `DELETE /api/member/{member}`
- Get all `Booking` from the same location `GET /api/booking`
- Get one `Booking` `GET /api/booking/{booking}`
- Create a new `Booking` `POST /api/booking`
- Edit a `Booking` `PUT /api/booking/{booking}`
- Delete a `Booking` `DELETE /api/booking/{booking}`

**Member**
- Get all `Booking` from the same `Member` `GET /api/booking`
- Create a new `Booking` `POST /api/booking`
- Edit a `Booking` `PUT /api/booking/{booking}`
- Delete a `Booking` `DELETE /api/booking/{booking}`




```php
// /routes/api.php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\UserController;
...

// unprotected route to login
Route::post('/login', [UserController::class, 'login']);

// protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Staff
    Route::get('/member', [MemberController::class, 'index']);
    Route::get('/member/{member}', [MemberController::class, 'show']);
    Route::post('/member', [MemberController::class, 'store']);
    Route::put('/member/{member}', [MemberController::class, 'update']);
    Route::delete('/member/{member}', [MemberController::class, 'destroy']);
    Route::get('/booking', [BookingController::class, 'index']);
    Route::get('/booking/{booking}', [BookingController::class, 'show']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::put('/booking/{booking}', [BookingController::class, 'update']);
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);
    // Member
    Route::get('/booking', [BookingController::class, 'index']);
    Route::post('/booking', [BookingController::class, 'store']);
    Route::put('/booking/{booking}', [BookingController::class, 'update']);
    Route::delete('/booking/{booking}', [BookingController::class, 'destroy']);
});
```

### Controllers

**Controllers** are used to group related request handling logic into a single class. Controllers are stored in the `app/Http/Controllers` directory. We need one controller for each table in our database.

```bash
php artisan make:controller UserController
php artisan make:controller LocationController
php artisan make:controller StaffController
php artisan make:controller MemberController
php artisan make:controller BookingController
```

This will create a new file for each controller.

```bash
/app
  /Http
    /Controllers
      UserController.php
      LocationController.php
      StaffController.php
      MemberController.php
      BookingController.php
```

No we need to add the code for each controller.

```php
// /app/Http/Controllers/UserController.php
namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Staff;
use App\Models\Member;
use Illuminate\Http\Request;
...
class UserController extends Controller {
  public function login(Request $request) {
    $request->validate([
      'email' => 'required|email',
      'password' => 'required',
    ]);
    $user = User::where('email', $request->email)->first();
    if (!$user || !Hash::check($request->password, $user->password)) {
      return response([
        'message' => ['These credentials do not match our records.'],
      ], 404);
    }
    $token = $user->createToken('auth-token')->plainTextToken;

    $role = $user->role;

    if ($role == 'staff') {
      $roleData = Staff::where('user_id', $user->id)->first();
      $userData = [
        'id' => $roleData->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $role,
        'phone' => $roleData->phone,
        'location_id' => $roleData->location_id,
        'is_admin' => $user->is_admin,
      ];
    } else {
      $roleData = Member::where('user_id', $user->id)->first();
      $userData = [
        'id' => $roleData->id,
        'name' => $user->name,
        'email' => $user->email,
        'role' => $role,
        'location_id' => $roleData->location_id,
      ];
    }
    $response = [
      'user' => $userData,
      'token' => $token,
    ];

    return response($response, 201);
  }
  public function logout(Request $request) {
    auth()->user()->tokens()->delete();
    return [
      'message' => 'Logged out',
    ];
  }
}
```
The `login` function is used to login the `User`. We need to check if the `User` exists and if the password is correct.
If so, we create a token for the `User` and get the `role` of the `User`. With the `role` we can get the data from the `Staff` or `Member` table and return a selection of the data and the token to the client.


```php
// /app/Http/Controllers/LocationController.php
namespace App\Http\Controllers;
use App\Models\Location;
use Illuminate\Http\Request;
...
class LocationController extends Controller {
    public function index() {
        return Location::all();
    }
    public function show(Location $location) {
        return $location;
    }
    public function store(Request $request) {
        return Location::create($request->all());
    }
    public function update(Request $request, Location $location) {
        $location->update($request->all());
        return $location;
    }
    public function destroy(Location $location) {
        $location->delete();
        return response()->json(null, 204);
    }
}
```

```php
// /app/Http/Controllers/StaffController.php
namespace App\Http\Controllers;
use App\Models\Staff;
use Illuminate\Http\Request;
...
class StaffController extends Controller {
    public function index() {
        return Staff::all();
    }
    public function show(Staff $staff) {
        return $staff;
    }
    public function store(Request $request) {
        return Staff::create($request->all());
    }
    public function update(Request $request, Staff $staff) {
        $staff->update($request->all());
        return $staff;
    }
    public function destroy(Staff $staff) {
        $staff->delete();
        return response()->json(null, 204);
    }
}
```

```php
// /app/Http/Controllers/MemberController.php
namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Http\Request;
...
class MemberController extends Controller {
    public function index() {
        return Member::all();
    }
    public function show(Member $member) {
        return $member;
    }
    public function store(Request $request) {
        return Member::create($request->all());
    }
    public function update(Request $request, Member $member) {
        $member->update($request->all());
        return $member;
    }
    public function destroy(Member $member) {
        $member->delete();
        return response()->json(null, 204);
    }
}
```

```php
// /app/Http/Controllers/BookingController.php
namespace App\Http\Controllers;
use App\Models\Booking;
use Illuminate\Http\Request;
...
class BookingController extends Controller {
    public function index() {
        return Booking::all();
    }
    public function show(Booking $booking) {
        return $booking;
    }
    public function store(Request $request) {
        return Booking::create($request->all());
    }
    public function update(Request $request, Booking $booking) {
        $booking->update($request->all());
        return $booking;
    }
    public function destroy(Booking $booking) {
        $booking->delete();
        return response()->json(null, 204);
    }
}
```




## Testing

Testing is an important part of the development process and it is a good practice to write tests for your code. Laravel provides a lot of tools to make testing easier.

### Unit Tests

**Unit tests** are used to test a single class or method. We can find the unit tests in the `tests/Unit` folder. We need one unit test for each table in our database.

```bash 
php artisan make:test LocationTest
php artisan make:test StaffTest
php artisan make:test MemberTest
php artisan make:test BookingTest
```

This will create a new file for each unit test.

```bash
/tests
  /Unit
    LocationTest.php
    StaffTest.php
    MemberTest.php
    BookingTest.php
```

```php
// /tests/Unit/LocationTest.php
namespace Tests\Unit;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
...
class LocationTest extends TestCase {
    use RefreshDatabase;
    public function test_create_location() {
        $location = Location::factory()->create();
        $this->assertDatabaseHas('locations', $location->toArray());
    }
    public function test_update_location() {
        $location = Location::factory()->create();
        $location->city = 'New City';
        $location->save();
        $this->assertDatabaseHas('locations', $location->toArray());
    }
    public function test_delete_location() {
        $location = Location::factory()->create();
        $location->delete();
        $this->assertDeleted($location);
    }
}
```

```php
// /tests/Unit/StaffTest.php
namespace Tests\Unit;
use App\Models\Staff;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
...
class StaffTest extends TestCase {
    use RefreshDatabase;
    public function test_create_staff() {
        $staff = Staff::factory()->create();
        $this->assertDatabaseHas('staff', $staff->toArray());
    }
    public function test_update_staff() {
        $staff = Staff::factory()->create();
        $staff->phone = '0123456789';
        $staff->save();
        $this->assertDatabaseHas('staff', $staff->toArray());
    }
    public function test_delete_staff() {
        $staff = Staff::factory()->create();
        $staff->delete();
        $this->assertDeleted($staff);
    }
}
```

```php
// /tests/Unit/MemberTest.PHP
namespace Tests\Unit;
use App\Models\Member;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
...

class MemberTest extends TestCase {
    use RefreshDatabase;
    public function test_create_member() {
        $member = Member::factory()->create();
        $this->assertDatabaseHas('members', $member->toArray());
    }
    public function test_update_member() {
        $member = Member::factory()->create();
        $member->phone = '0123456789';
        $member->save();
        $this->assertDatabaseHas('members', $member->toArray());
    }
    public function test_delete_member() {
        $member = Member::factory()->create();
        $member->delete();
        $this->assertDeleted($member);
    }
}
```

```php
// /tests/Unit/BookingTest.php
namespace Tests\Unit;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
...
class BookingTest extends TestCase {
    use RefreshDatabase;
    public function test_create_booking() {
        $booking = Booking::factory()->create();
        $this->assertDatabaseHas('bookings', $booking->toArray());
    }
    public function test_update_booking() {
        $booking = Booking::factory()->create();
        $booking->date = '2021-10-01';
        $booking->save();
        $this->assertDatabaseHas('bookings', $booking->toArray());
    }
    public function test_delete_booking() {
        $booking = Booking::factory()->create();
        $booking->delete();
        $this->assertDeleted($booking);
    }
}
```

<!-- 
### Feature Tests

**Feature tests** are used to test a complete feature of your application. We can find the feature tests in the `tests/Feature` folder. We need one feature test for each table in our database.

```bash
php artisan make:test LocationFeatureTest
php artisan make:test StaffFeatureTest
php artisan make:test MemberFeatureTest
php artisan make:test BookingFeatureTest
```

This will create a new file for each feature test.

```bash
/tests
  /Feature
    LocationFeatureTest.php
    StaffFeatureTest.php
    MemberFeatureTest.php
    BookingFeatureTest.php
```

```php
// /tests/Feature/LocationFeatureTest.php
namespace Tests\Feature;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
...
class LocationFeatureTest extends TestCase {
    use RefreshDatabase;
    public function test_get_all_locations() {
        $locations = Location::factory()->count(5)->create();
        $response = $this->get('/api/location');
        $response->assertStatus(200);
        $response->assertJson($locations->toArray());
    }
    public function test_get_one_location() {
        $location = Location::factory()->create();
        $response = $this->get('/api/location/' . $location->id);
        $response->assertStatus(200);
        $response->assertJson($location->toArray());
    }
    public function test_create_location() {
        $location = Location::factory()->make();
        $response = $this->post('/api/location', $location->toArray());
        $response->assertStatus(201);
        $response->assertJson($location->toArray());
    }
    public function test_update_location() {
        $location = Location::factory()->create();
        $location->city = 'New City';
        $response = $this->put('/api/location/' . $location->id, $location->toArray());
        $response->assertStatus(200);
        $response->assertJson($location->toArray());
    }
    public function test_delete_location() {
        $location = Location::factory()->create();
        $response = $this->delete('/api/location/' . $location->id);
        $response->assertStatus(204);
    }
}
```
 -->


## Test for the MemberController

To create a test file we can use the following command:
```bash
php artisan make:test MemberFeatureTest
```

and run it with:
```bash
php artisan test --filter MemberFeatureTest
```


To test the `MemberController`  we hafe to refresh the database:
```bash
php artisan migrate:fresh --seed
```

Befor we can test the `MemberController` we need to login a `Staff` and get the token.
```php
// /tests/Feature/MemberFeatureTest.php
namespace Tests\Feature;
use App\Models\Member;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
...
class MemberFeatureTest extends TestCase {
    use RefreshDatabase;
    public function test_get_all_members() {
        $staff = Staff::factory()->create();
        $user = User::factory()->create([
            'role' => 'staff',
        ]);
        $user->update(['role' => 'staff']);
        $token = $user->createToken('auth-token')->plainTextToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/member');
        $response->assertStatus(200);
    }
    public function test_get_one_member() {
        $staff = Staff::factory()->create();
        $user = User::factory()->create([
            'role' => 'staff',
        ]);
        $user->update(['role' => 'staff']);
        $token = $user->createToken('auth-token')->plainTextToken;
        $member = Member::factory()->create();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->get('/api/member/' . $member->id);
        $response->assertStatus(200);
    }
    public function test_create_member() {
        $staff = Staff::factory()->create();
        $user = User::factory()->create([
            'role' => 'staff',
        ]);
        $user->update(['role' => 'staff']);
        $token = $user->createToken('auth-token')->plainTextToken;
        $member = Member::factory()->make();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->post('/api/member', $member->toArray());
        $response->assertStatus(201);
    }
    public function test_update_member() {
        $staff = Staff::factory()->create();
        $user = User::factory()->create
        ([
            'role' => 'staff',
        ]);
        $user->update(['role' => 'staff']);
        



With this we have seed the database with some data, so we can test the `MemberController`.
Therefore we use the `Staff` with the email: staff@example.con and the password: password to login.
We get back a token, which we need to use for the other routes.

 


