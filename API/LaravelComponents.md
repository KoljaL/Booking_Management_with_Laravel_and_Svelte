
# Building the application
To build the application, we need to understsand the basic concepts of Laravel. It brings a lot of components, and as a beginner it is not easy to understand, how they work together. So I (as a beginner) will try to explain, what I have learned, mostly by reading the [Laravel Documentation](https://laravel.com/docs/10.x).

## Artisan Console
Artisan is like a magic command tool for Laravel. It helps you create things, manage your app, and perform various tasks using the command line. You can think of it as your assistant for managing your Laravel app.
```bash
php artisan make:model Post
php artisan make:migration create_posts_table
php artican make:controller PostController
php artisan make:factory PostFactory
php artisan make:seeder PostSeeder
php artisan make:middleware IsMember
```
And there are many more commands. You can find a list of all commands [here](https://laravel.com/docs/10.x/artisan).
Some more advanced commands are:
```bash
php artisan make:provider RiakServiceProvider
php artisan make:event PostCreated
php artisan make:listener SendPostCreatedNotification
```


## Eloquent Models:
Models are like data detectives for your app. They help you interact with your app's database. They understand how to talk to the database, fetch information, and update data. Models are like your go-to friends for database operations.
```php
// /app/Models/Post.php
class Post extends Model {
  use HasFactory;
  protected $table = 'my_posts';
}
```
This is the code for the model `Post`. It is a class, which extends the class `Model`. The class `Model` is part of Laravel and it is used to interact with the database. The class `Post` is a model for the table `posts` in the database. The name of the table is the plural of the name of the model. So the model `Post` is for the table `posts`. If you want to use a different name for the table, you can add the following code to the model:


## Eloquent Relationships:
Relationships help models talk to each other. Imagine your models as friends, and relationships define how they are connected. For example, one friend might know many others. This makes it easy to work with related data in your app.
```php
// /app/Models/Post.php
class Post extends Model {
  use HasFactory;
    public function user() {
      return $this->belongsTo(User::class);
  }
}
```
The function `user()` is a relationship to the model `User`. The model `User` is already created by Laravel, but we need to add the relation to the model `Post`.
```php
// /app/Models/User.php
class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;
  public function posts() {
    return $this->hasMany(Post::class);
  }
}
```
With this, Laravel knows that the model `User` has many `Post`s, but the model `Post` belongs to one `User`.


## Eloquent Collections:
Collections are like toolboxes for working with lists of data. They have special tools for sorting, filtering, and manipulating lists of information. It's a handy way to work with data in your app.
```php
// /app/Models/Post.php
$filteredPosts = Post::where('is_published', true)->get();  
``` 
This code will return all posts, which are published. The function `where()` is a filter, which filters the posts by the column `is_published`. The function `get()` returns all posts, which are published.


## Migration:
Migrations are like blueprints for your database. They describe how your database tables should look and how they change over time. You can use migrations to create or change your database structure.
```php
// /database/migrations/2021_10_01_000000_create_posts_table.php
public function up() {
  Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('body');
    $table->foreignId('user_id')->constrained();
    $table->timestamps();
  });
}
```
This code will create a new table in the database, called `posts`. It has the columns `id`, `title`, `body`, `user_id`, `created_at` and `updated_at`. The column `id` is the primary key and it is auto-incremented. The column `user_id` is a foreign key and it is constrained to the column `id` of the table `users`. The columns `created_at` and `updated_at` are timestamps and they are automatically updated, when a new record is created or updated.

## Factory
Factories are like data factories for testing. They help you create pretend data for testing your app. Think of them as a way to make up data to test your app with.
```php 
// /database/factories/PostFactory.php
public function definition() {
  return [
    'title' => $this->faker->sentence(),
    'body' => $this->faker->paragraph(),
    'user_id' => User::factory(),
  ];
}
```
This code will create a new factory for the model `Post`. It will create a new post with a random title and body. The column `user_id` will be a random user.


## Seeder
Seeders are like gardeners for your database. They plant your database with sample data, so it's not empty. This is useful when you want to start with some initial data in your app.
```php
// /database/seeders/DatabaseSeeder.php
public function run() {
User::factory()
  ->count(50)
  ->hasPosts(1)
  ->create();
}
```
This code will create 50 users with one post for each user.

## Controller
Controllers are like the traffic cops of your app. They receive incoming requests, decide what to do, and send back a response. They're in charge of managing how your app responds to different web actions.
```php
// /app/Http/Controllers/PostController.php
public function index() {
  $posts = Post::all();
  return view('posts.index', ['posts' => $posts]);
}
```
This code will return all posts and pass them to the view `posts.index`.

## Middleware
Middleware are like gatekeepers for your app. They check incoming requests and decide if they should be allowed in. You can use them for tasks like authentication, authorization, or logging.
```php
// /app/Http/Middleware/IsMember.php
public function handle(Request $request, Closure $next) {
  if (Auth::user()->role == 'member') {
    return $next($request);
  }
    return redirect('home');
}
```
This code will check if the user is a member and if so, it will allow the request to continue. If not, it will redirect to the home page.


## Routes
Routes are like road signs for your app. They tell your app which code to run when a user visits a specific web page. Think of them as directions for handling web requests.
```php
// /routes/web.php
Route::get('/posts', [PostController::class, 'index']);

// /routes/api.php
Route::get('/posts', [PostController::class, 'index']);
```
This code will create a route for the url `/posts`, but the first route will deliver a view and the second route will deliver a json response. 


Routes can be grouped and they can have a prefix.
```php
// /routes/web.php
Route::prefix('admin')->group(function () {
  Route::get('/posts', [PostController::class, 'index']);
  Route::get('/posts/create', [PostController::class, 'create']);
  Route::post('/posts', [PostController::class, 'store']);
});
```
and protected by middleware.
```php
// /routes/web.php
Route::middleware('auth:sanctum')->group(function () {
  Route::get('/posts', [PostController::class, 'index']);
  Route::get('/posts/create', [PostController::class, 'create']);
  Route::post('/posts', [PostController::class, 'store']);
});
``` 


## Authentication
Authentication is like the entry ticket to your app. It's the process of confirming who a user is. Laravel provides tools to handle user registration and login.
```php
// /app/Http/Controllers/AuthController.php
  public function login(Request $request) {
// Validate the incoming request data
$request->validate([
  'email' => 'required|email',
  'password' => 'required',
]);

// Attempt to authenticate the user
$user = User::where('email', $request->email)->first();

if (!$user || !Hash::check($request->password, $user->password)) {
  // Authentication failed
  throw ValidationException::withMessages([
    'email' => ['The provided credentials are incorrect.'],
  ]);
}

// Create a personal access token for the user
$token = $user->createToken('auth-token')->plainTextToken;

return response()->json([
    'user' => $user,
    'token' => $token,
  ]);
}
```
This code will check if the user exists and if the password is correct. If so, it will create a token for the user and return the user and the token as json response.


## Validation
Validation is like a security guard for your app. It checks if the information users provide is correct and safe. You can set rules to ensure the data is valid before using it.
```php 
// /app/Http/Controllers/PostController.php
public function store(Request $request) {
  $request->validate([
    'title' => 'required|unique:posts|max:255',
    'body' => 'required',
  ]);
  $post = new Post;
  $post->title = $request->title;
  $post->body = $request->body;
  $post->save();
  return redirect('/posts');
}
```
This code will check if the title is unique and if the body is not empty. If so, it will create a new post and redirect to the page `/posts`.

## Views
Views are like the user interface designers of your app. They create what users see on the screen. In Laravel, views are typically written in a special way using Blade, which mixes HTML with PHP.
```php
// /resources/views/posts/index.blade.php
@foreach ($posts as $post)
  <div class="post">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->body }}</p>
  </div>
@endforeach
```
This code will loop through all posts and display the title and the body of each post.

## Blade Template Engine
Blade is a template engine for Laravel. It's a special way of writing views that mixes HTML with PHP. It makes it easy to write clean and dynamic HTML. We start with creating a layout.

```html
// /resources/views/layouts/app.blade.php
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>My Blog</title>
  </head>
  <body>
    <div class="container">
      @yield('content')
    </div>
  </body>
</html>
```
This Blade layout provides a basic structure for our web pages. It sets up the HTML and defines a container where the main content will be placed using the `@yield()` directive.
If we want to use this layout, we need to extend it in our view.

```php
// /resources/views/posts/index.blade.php
@extends('layouts.app')
@section('content')
  @foreach ($posts as $post)
    <div class="post">
    <h2>{{ $post->title }}</h2>
    <p>{{ $post->body }}</p>
  </div>
@endforeach
@endsection
```
This code will extend the layout `app.blade.php` and add the content of the view `posts.index.blade.php` to the container defined in the layout.
  

## Service Providers
Service providers are like helpers that set up your app. They do important tasks like connecting different parts of your app, adding services, and preparing your app to run.  
```php
// /app/Providers/AppServiceProvider.php
public function boot() {
  Post::creating(function ($post) {
    $post->excerpt = Str::limit($post->body, 250);
  });
}
```
This code will create an excerpt of the body of the post, when a new post is created.



## Events
Events are like messengers that tell your app when something interesting happens. For instance, you can send a message when a new user signs up, and other parts of your app can listen for this message and take action when it happens.
```php
// /app/Models/Post.php
class Post extends Model {
  use HasFactory;
  protected $dispatchesEvents = [
    'created' => PostCreated::class,
  ];
}
```
This code will dispatch the event `PostCreated` when a new post is created. With this, you can send an email to the user, when a new post is created.
```php 
// /app/Events/PostCreated.php
class PostCreated {
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $post;
  public function __construct(Post $post) {
    $this->post = $post;
  }
}
``` 


## Listeners
Listeners are like the ears of your app. They listen for events and take action when they hear something. For example, you can listen for a new user signing up and send them a welcome email.
```php
// /app/Providers/EventServiceProvider.php
protected $listen = [
  PostCreated::class => [
    SendPostCreatedNotification::class,
  ],
];
```
This code will listen for the event `PostCreated` and send a notification to the listener `SendPostCreatedNotification`.


## Sanctum

**Sanctum** is Laravel's lightweight API authentication package. It provides a simple, convenient way to authenticate users in your single-page application (SPA), mobile application, or any other API client using Laravel. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / scopes which specify which actions the tokens are allowed to perform.




# Misc

## `belongsTo` vs `hasOne`
In Laravel's Eloquent ORM, `belongsTo` and `hasOne` are two different types of relationships between database tables, and they represent the associations between models. Here's the key difference between the two:

### `belongsTo` Relationship:

- `belongsTo` is used to define a "parent" relationship where the current model "belongs to" another model.
- It's typically used when the foreign key is on the current model's table to reference the related model's table.
- For example, if you have a `Comment` model that belongs to a `Post`, you would define the relationship like this:
    
```php
class Comment extends Model {
  public function post() {         
    return $this->belongsTo(Post::class);     
  } 
}
```
    
### `hasOne` Relationship:

- `hasOne` defines a "child" relationship where the current model has a single associated model.
- It's typically used when the foreign key is on the related model's table to reference the current model's table.
- For example, if you have a `User` model with a `Profile`, you would define the relationship like this:
    
```php
class User extends Model {     
  public function profile() {         
    return $this->hasOne(Profile::class);    
  } 
}
```

So, the key distinction is the direction of the relationship and where the foreign key is located. `belongsTo` means the foreign key is on the current model's table, and it points to the related model's table, while `hasOne` means the foreign key is on the related model's table, and it points to the current model's table.


--------------------
# I will build a Laravel API. 
--------------------
There will be four Models: `Location`, `Staff`, `Member` and `Booking`.
And I need some help with the relationships.
`Staff` and `Member` are both `User` extending the `User` Model from Laravel.
Therefore Im using a Middleware to check the role of the user.
That works fine.
But now I need to set up the relationships.
Both `Staff` and `Member` are working in a `Location`.
A `Member`is created by a `Staff`.
A `Member` can have create `Booking`s for himself, or a `Staff` can create `Booking`s for his `Member`.
A `Booking` has the same `Location` as the `Member` or `Staff` who created it.

## First i need the foreign keys for the relationships in the migrations.
```php
// /database/migrations/2021_10_01_000000_create_users_table.php
public function up() {
  Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
    $table->string('role');
  });
}
```

```php
// /database/migrations/2021_10_01_000000_create_locations_table.php
public function up() {
  Schema::create('locations', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->timestamps();
  });
}
```
```php
// /database/migrations/2021_10_01_000000_create_staffs_table.php
public function up() {
  Schema::create('staffs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('location_id')->constrained();
    $table->timestamps();
  });
}
```
```php
// /database/migrations/2021_10_01_000000_create_members_table.php
public function up() {
  Schema::create('members', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained();
    $table->foreignId('location_id')->constrained();
    $table->foreignId('staff_id')->constrained();
    $table->timestamps();
  });
}
```
```php
// /database/migrations/2021_10_01_000000_create_bookings_table.php
public function up() {
  Schema::create('bookings', function (Blueprint $table) {
    $table->id();
    $table->foreignId('member_id')->constrained();
    $table->foreignId('location_id')->constrained();
    $table->foreignId('staff_id')->constrained();
    $table->timestamps();
  });
}
```
## Now I need to set up the relationships in the models.
```php
// /app/Models/Location.php
class Location extends Model {
  use HasFactory;
  public function staffs() {
    return $this->hasMany(Staff::class);
  }
  public function members() {
    return $this->hasMany(Member::class);
  }
  public function bookings() {
    return $this->hasMany(Booking::class);
  }
}
```
```php
// /app/Models/Staff.php
class Staff extends Model {
  use HasFactory;
  public function location() {
    return $this->belongsTo(Location::class);
  }
  public function members() {
    return $this->hasMany(Member::class);
  }
  public function bookings() {
    return $this->hasMany(Booking::class);
  }
}
```
```php
// /app/Models/Member.php
class Member extends Model {
  use HasFactory;
  public function location() {
    return $this->belongsTo(Location::class);
  }
  public function staff() {
    return $this->belongsTo(Staff::class);
  }
  public function bookings() {
    return $this->hasMany(Booking::class);
  }
}
```
```php
// /app/Models/Booking.php
class Booking extends Model {
  use HasFactory;
  public function location() {
    return $this->belongsTo(Location::class);
  }
  public function staff() {
    return $this->belongsTo(Staff::class);
  }
  public function member() {
    return $this->belongsTo(Member::class);
  }
}
```
```php
// /app/Models/User.php
class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;
  public function staff() {
    return $this->hasOne(Staff::class);
  }
  public function member() {
    return $this->hasOne(Member::class);
  }
}
```

Now I need to set up the factories.
```php
// /database/factories/LocationFactory.php
public function definition() {
  return [
    'name' => $this->faker->company(),
  ];
}
```
```php
// /database/factories/StaffFactory.php
public function definition() {
  return [
    'user_id' => User::factory(),
    'location_id' => Location::factory(),
  ];
}
```
```php
// /database/factories/MemberFactory.php
public function definition() {
  return [
    'user_id' => User::factory(),
    'location_id' => Location::factory(),
    'staff_id' => Staff::factory(),
  ];
}
```
```php
// /database/factories/BookingFactory.php
public function definition() {
  return [
    'member_id' => Member::factory(),
    'location_id' => Location::factory(),
    'staff_id' => Staff::factory(),
  ];
}
```
```php
// /database/factories/UserFactory.php
public function definition() {
  return [
    'name' => $this->faker->name(),
    'email' => $this->faker->unique()->safeEmail(),
    'email_verified_at' => now(),
    'password' => Hash::make('password'),
    'remember_token' => Str::random(10),
    'role' => $this->faker->randomElement(['staff', 'member']),
  ];
}
```
## Now I need to set up the controller function to show all `Member`s. W
This function returns a json response with all `Member`s and include the Name from the `User` Model and the `Location` from the `Location` Model. But only the `User`-Name and the `Location`-City`, not the whole Model.
```php
// /app/Http/Controllers/MemberController.php
public function index() {
  $members = Member::with('user:name', 'location:city')->get();
  return response()->json($members);
}
```
This is the response:
```json
  {
    "id": 1,
    "user_id": 8,
    "location_id": 1,
    "staff_id": 2,
    "phone": "0123456789",
    "jc_number": "1234567890",
    "max_booking": 5,
    "active": true,
    "archived": false,
    "created_at": "2023-11-01T15:30:15.000000Z",
    "updated_at": "2023-11-01T15:30:15.000000Z",
    "user": null,
    "location": null
  },
```
The `user` and the `location` are null. I need to add the `name` to the `User` and the `Location` Model.
```php
// /app/Models/User.php
class User extends Authenticatable {
  use HasApiTokens, HasFactory, Notifiable;
  protected $fillable = [
    'name',
    'email',
    'password',
    'role',
  ];
  public function staff() {
    return $this->hasOne(Staff::class);
  }
  public function member() {
    return $this->hasOne(Member::class);
  }
}
```
The `name` is now fillable.
```php
// /app/Models/Location.php
class Location extends Model {
  use HasFactory;
    protected $fillable = [
        'city',
        'address',
        'phone',
        'email',
        'active',
        'archived',
    ];
  public function staffs() {
    return $this->hasMany(Staff::class);
  }
  public function members() {
    return $this->hasMany(Member::class);
  }
  public function bookings() {
    return $this->hasMany(Booking::class);
  }
}
```

But the response is still the same. I need to add the `name` to the `Member` Model.
```php
// /app/Models/Member.php
class Member extends Model {
  use HasFactory;
  protected $fillable = [
    'user_id',
    'location_id',
    'staff_id',
    'phone',
    'jc_number',
    'max_booking',
    'active',
    'archived',
  ];
  public function location() {
    return $this->belongsTo(Location::class);
  }
  public function staff() {
    return $this->belongsTo(Staff::class);
  }
  public function bookings() {
    return $this->hasMany(Booking::class);
  }
  public function user() {
    return $this->belongsTo(User::class);
  }
}
```

But the response is still the same. I have to getthe Name from the `User` Model and the `Location` from the `Location` Model.
```php
// /app/Http/Controllers/MemberController.php
public function index() {

  // either we can use Laravels eager loading with the with() function
  $members = Member::with([
    'user' => function ($query) {
      $query->select('id', 'name');
    },
    'location' => function ($query) {
      $query->select('id', 'city');
    }
  ])->get();

  // or we can use the join() function
  $members = Member::select('members.*', 'users.name as user_name', 'locations.city as location_city')
    ->join('users', 'members.user_id', '=', 'users.id')
    ->join('locations', 'members.location_id', '=', 'locations.id')
    ->get();


return response()->json($members);
```

The difference in the response will be, that the `with()` function will return the `user` and the `location` as nested objects, while the `join()` function will return the `user` and the `location` as flat objects.



## `::select()` vs `::with()`
The choice between using Member::select and join() versus Member::with depends on your specific use case and performance considerations.

- Using select and join():  
This approach allows you to select specific columns from multiple tables in a more fine-grained manner, which can be efficient when you need a flat structure. It can be more efficient when you only require a subset of columns from the related tables and don't want the data to be nested. It minimizes the amount of data retrieved from the database because you only select the columns you need.

- Using with:  
Eager loading with with is convenient and easy to use. It loads related models and relationships in a single query, reducing the number of database queries. It simplifies the code and can be more intuitive when working with relationships, especially for nested relationships. with may be more efficient when you need a complete set of related data, including relationships and nested data.

In terms of efficiency, if you need all the data associated with the Member model and its relationships, with might be more efficient because it reduces the number of queries sent to the database. However, if you only need specific columns from related tables and want a flat structure, using select and join() can be more efficient and reduce data transfer between the database and your application.

Ultimately, the choice between these two methods should consider your specific use case and whether performance optimizations are needed. You may need to benchmark and profile your application to determine which approach is best for your application's requirements and data volume.

### Benchmarking
In Laravel we can use the `dd()` function to benchmark the performance of our code. The `dd()` function dumps the given variables and ends ex ecution of the script. So we can use it to measure the time it takes to execute a function.
```php
  Benchmark::dd([
    'join' => fn() =>
      Member::select('members.*', 'users.name as user_name', 'locations.city as location_city')
        ->join('users', 'members.user_id', '=', 'users.id')
        ->join('locations', 'members.location_id', '=', 'locations.id')
        ->get(),
    "with" => fn() => Member::with([
      'user' => function ($query) {
        $query->select('id', 'name');
      },
      'location' => function ($query) {
        $query->select('id', 'city');
      }
    ])->get(),
  ]);
```
The result is:
```json
array:2 [// vendor/laravel/framework/src/Illuminate/Support/Benchmark.php:67
  "join" => "8.186ms"
  "with" => "25.601ms"
]
```


## Custom JSOn response
To enshure, that the response is always the same, I will create a custom JSON response.
Therefor I will create a custom macro for the Response class.


```php
// app/Providers/AppServiceProvider.php

public function boot(): void {
  // custom macro for JSON response
  Response::macro('customJson', function ($data = [], $name = 'data', $message = '', $status = 200) {
    if (is_countable($data)) {
      $data_count = count($data);
    }
    // data is an Exception object
    // so we want to return the error message
    else {
      $data_count = null;
      $data = [
        'message' => $data->getMessage(),
        'file' => $data->getFile(),
        'line' => $data->getLine(),
        'code' => $data->getCode(),
        // 'string' => $data->__toString(),
        // 'trace' => $data->getTrace(),
      ];
    }
    return response()->json(['message' => $message, 'data_count' => $data_count, $name => $data], $status);

  });
}
```
So we can use the custom macro like this:
```php
return response()->customJson($data, $name, $message, $status);
```

And the result will be:
```json
{
  "message": "Here are your members.",
  "data_count": 2,
  "member": [
    {},
    {}
  ]
}
```

## Resource
A resource class represents a single model that needs to be transformed into a JSON structure. For example, we can have a `Member` model that needs to be transformed into a JSON structure. To do so, we will create a `MemberResource` class. By default, the `make:resource` command will place the new resource class in the `app/Http/Resources` directory. The `make:resource` command will also create a resource collection class in the same directory. Resource collections are responsible for transforming collections of models into JSON.

```bash
php artisan make:resource MemberResource
```
```php

// app/Http/Resources/MemberResource.php

public function toArray($request) {
  return [
    'id' => $this->id,
    'user_id' => $this->user_id,
    'location_id' => $this->location_id,
    'staff_id' => $this->staff_id,
    'phone' => $this->phone,
    'jc_number' => $this->jc_number,
    'max_booking' => $this->max_booking,
    'active' => $this->active,
    'archived' => $this->archived,
    'created_at' => $this->created_at,
    'updated_at' => $this->updated_at,
    'user' => $this->user,
    'location' => $this->location,
    'staff' => $this->staff,
    'bookings' => $this->bookings,
  ];
}
```




## Benchmarking

In Laravel we can use the `dd()` function to benchmark the performance of our code. The `dd()` function dumps the given variables and ends ex ecution of the script. So we can use it to measure the time it takes to execute a function.
```php
use Illuminate\Support\Benchmark;
...
Benchmark::dd([
  'join' => fn() =>
    Member::select('members.*', 'users.name as user_name', 'locations.city as location_city')
      ->join('users', 'members.user_id', '=', 'users.id')
      ->join('locations', 'members.location_id', '=', 'locations.id')
      ->get(),
  "with" => fn() => Member::with([
    'user' => function ($query) {
      $query->select('id', 'name');
    },
    'location' => function ($query) {
      $query->select('id', 'city');
    }
  ])->get(),
]);
```

 