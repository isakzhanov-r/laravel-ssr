## Server Side Rendering
This helper allows you to render the SPA using node js for search bots.

<p align="center">
    <a href="https://packagist.org/packages/isakzhanov-r/laravel-ssr"><img src="https://img.shields.io/packagist/dt/isakzhanov-r/laravel-ssr.svg?style=flat-square" alt="Total Downloads" /></a>
    <a href="https://packagist.org/packages/isakzhanov-r/laravel-ssr"><img src="https://poser.pugx.org/isakzhanov-r/laravel-ssr/v/stable?format=flat-square" alt="Latest Stable Version" /></a>
    <a href="https://packagist.org/packages/isakzhanov-r/laravel-ssr"><img src="https://poser.pugx.org/isakzhanov-r/laravel-ssr/v/unstable?format=flat-square" alt="Latest Unstable Version" /></a>
    <a href="LICENSE"><img src="https://poser.pugx.org/isakzhanov-r/laravel-ssr/license?format=flat-square" alt="License" /></a>
</p>

## Contents
* [Installation](#installation)
* [Configuration](#configuration)
* [Usage](#usage)
    * [Methods ](#methods )
    * [Examples](#examples)
* [License](#license)


## Installation

To get the latest version of Laravel Server Side Rendering package, simply require the project using [Composer](https://getcomposer.org):

```
composer require isakzhanov-r/laravel-ssr
```

Instead, you can, of course, manually update the dependency block `require` in `composer.json` and run `composer update` if you want to:

```json
{
    "require": {
        "isakzhanov-r/laravel-ssr": "^1.0"
    }
}
```

If you don't use auto-discovery, add the `ServiceProvider` to the providers array in `config/app.php`:

```php
IsakzhanovR\Ssr\ServiceProvider::class;
```

## Configuration
The package is configured to use node, since it is probably already installed on your system.
If you need to change the configuration file you can publish it for modification (ie. interface to specific class):

```
php artisan vendor:publish --provider="IsakzhanovR\Ssr\ServiceProvider"
```

The configuration file contains two settings: the path where temporary files will be saved by default `"storage/app/ssr"` and the path to the `node` executable file

```php
return [
    'temp_storage' => [
        'disk' => 'local', // filesystem disk
        'path' => 'ssr',   // directory
    ],
    'node_path'    => env('NODE_PATH', ''), // default /usr/bin/node
];
```
You can check on the server where the node is located by running the `which node` command and add the `NODE_PATH` key to the environment file

## Usage
You will need two files for two scenarios: server and client.
There is an example of a server script in the `tests` folder

```js
mix
    .js('resources/js/server.js', 'public/js')
    .js('resources/js/client.js', 'public/js');
```

The server script must be passed to the ssr function, and the client script must be loaded manually. The package assumes that you are using Laravel Mix, and will resolve the path for you. I use as follows:

```blade
@section('content')
    {!! ssr()
            ->entry('js/server.js')
            ->fallback('<div id="wrapper" class="wrapper"></div>')
            ->setData(compact('titles'))
            ->render()  !!}
@endsection

@section('scripts')
    <script src="{{ mix('js/client.js') }}" defer></script>
@endsection
```
###Methods
The `entry()` method takes the path to the server file argument.js, you can also pass the file path to the `ssr()` method
```blade
{!! ssr('js/server.js') !!}  ===  {!! ssr()->entry('js/server.js') !!}
```

The `fallback()` method is required if there are errors in the production process during rendering, this method will return the div to which the client application will be mounted.

The `setData()` method is required for transferring data to the server.js takes an array as an argument.

The `render ()` method renders html from a js file
###Examples
Example index blade
```blade
<html>
    <head>
        <title>My server side rendered app</title>
        <script defer src="{{ mix('client.js') }}">
    </head>
    <body>
        {!!
        ssr(mix('server.js'))
        ->setData(compact('news','posts'))
        ->render()
        !!}
    </body>
</html>
```

Example app.js
```js
import Vue from 'vue';
import store from './store/index';
import router from './routes/index';
import App from './components/App';

export default new Vue({
    store,
    router,
    render: h => h(App)
});
```

Example server.js
```js
import app from './app';
import renderVueComponentToString from 'vue-server-renderer/basic';

app.$store.commit('SetNews', {news: news});
app.$store.commit('SetPosts', {posts: posts});

app.$router.push(url.path);

renderVueComponentToString(app, (err, html) => {
    if (err) {
        throw new Error(err);
    }
    dispatch(html);
});
```

Example client.js
```js
import app from './app';
import './../vue-client/plugins/bootstrap';

app.$store.commit('SetNews', {news: news});
app.$store.commit('SetPosts', {posts: posts});

app.$mount('#wrapper');
```

Try to include all plugins and packages that can use the `window` object in the `client.js` file because when rendering the server file, `node` will not find the `window object` and will return an error.

##License
This package is released under the [MIT License](LICENSE.md).
