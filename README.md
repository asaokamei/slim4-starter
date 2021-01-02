Slim4 and PHP-DI Starter Project
================================

A starter project for ordinary web sites, based on [slim/slim-skeleton](https://github.com/slimphp/Slim-Skeleton).

Uses, 

- Slim4,
- PHP-DI,
- nyholm/psr7,
- monolog,
- Twig (using `slim/twig-view`)
- Aura/Session
- filp/whoops
- vlucas/phpdotenv

### License

MIT License

### Demo

installation.

```bash
git clone https://github.com/asaokamei/slim4-starter
cd slim4-starter
composer install
```

run demo, after installation.

```bash
cd public
php -S 127.0.0.1:8000 index.php
```

AbstractController
-----------

`App\Controllers\AbstractController` provides useful features,
such as;
 
- execute class method based on http method. 
- bind arguments with input value.
- modify input value. 

For instance, the method `onPost` is executed when
http method is post. 


```php
use App\Controllers\AbstractController;

class WelcomeController extends AbstractController
{
    public function action() {} // <- always executed, or
    public function onMethod() {} // <- executed based on HTTP method
}
```

### execution of method. 

1. if `action` method is present, always execute `action`.
2. check http method, and execute `on{$method}`. 
3. post with `_method` to specify http method other than 'GET' and 'POST'.  

### bind argument

For route, `/users/{user_id}`, the argument `$user_id` is bound with 
the route variable, `{user_id}`. 

```php
class WelcomeController extends AbstractController {
    public function onGet($user_id) {}
}
```

### modify input value

You can modify the input value using a `arg{$InputKey}` method in a controller.  

- Return a single value to replace the original input value, 
as shown in `argTags`. 
- Or, return an associative array to create a new entry, 
as shown in `argUserId`. 

```php
// 'users/{user_id}/{tags}
class WelcomeController extends AbstractController
{
    public function onGet($user_id) {}

    private function argUserId($user_id) {
        return ['user' => $this->user->find($user_id)];
    }
    private function argTags($tags) {
        return explode(',', $tags);
    }
}
```


Twig Functions
--------------

additional functions for Twig. 

#### `csrf_token()`

CSRF tokens in hidden tag for [Slim-Csrf](https://github.com/slimphp/Slim-Csrf). 
(was `hidden_csrf_token()`)

```
{{ csrf_token() }}
```

#### `path(string $routeName, array $data = [], array $queryParams = [])`

Get the url for a named route
(same as `url_for()` in slim/twig-view).

#### `url(string $routeName, array $data = [], array $queryParams = [])`

Get the full url for a named route.
(same as `full_url_for()` in slim/twig-view).


### More Functions from slim/twig-view.

#### `is_current_url(string $routeName, array $data = [])`

check if the route name is the current URL.

#### `current_url(bool $withQueryString = false)`

Get the current path.

#### `get_uri()`

Get `Psr\Http\Message\UriInterface` object.

#### `base_path()`

Get base path string.
