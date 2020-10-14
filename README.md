Slim4 and PHP-DI Starter Project
================================

A starter project for, 

- Slim4,
- PHP-DI,
- nyholm/psr7,
- monolog,
- Twig (using `slim/twig-view`)

with sample directory. 

based on [slim/slim-skeleton](https://github.com/slimphp/Slim-Skeleton).

### License

MIT License

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
    public function action() {} // <- always executed
    public function onPost() {}
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

### hidden tag for CSRF tokens

CSRF tokens in hidden tag for [Slim-Csrf](https://github.com/slimphp/Slim-Csrf). 

```
{{ hidden_csrf_token() }}
```


Functions from slim/twig-view.

### url_for()

Get the url for a named route.

`{{ url_for(string $routeName, array $data = [], array $queryParams = []) }}`

### full_url_for()

Get the full url for a named route. 

`{{ full_url_for(string $routeName, array $data = [], array $queryParams = []) }}`

### is_current_url()

`{{ is_current_url(string $routeName, array $data = []) }}`

### current_url()

Get current path on given Uri.

`{{ current_url(bool $withQueryString = false) }}`

### get_uri()

Get `Psr\Http\Message\UriInterface` object.

`{{ get_uri() }}`

### base_path()

Get base path string.

`{{ base_path() }}`