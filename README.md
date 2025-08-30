# This is my package mediamanager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/itstudioat/mediamanager.svg?style=flat-square)](https://packagist.org/packages/itstudioat/mediamanager)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/itstudioat/mediamanager/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/itstudioat/mediamanager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/itstudioat/mediamanager.svg?style=flat-square)](https://packagist.org/packages/itstudioat/mediamanager)




## Installation

You can install the package via composer:

```bash
composer require itstudioat/mediamanager
```

Now publish the files to the folder vendor/mediamanager.
Also publish the config-file
```bash
php artisan vendor:publish --tag="mediamanager-config"
```

In the config-file you may adapt the needed_role,
which is the spatie-role, that the user must have to use the mediamanager.
If it is empty, no role is needed.

```bash
return [
    ...
    'needed_role' => 'mediamanager_admin'
    ...
];
```

Include this in composer.json:
```bash
    "autoload": {
        "psr-4": {
            ...
            "Itstudioat\\Mediamanager\\": "vendor/itstudioat/mediamanager/"
        }
    },
```


The paths to the components (/vendor/itstudioat/mediamanager/routes/routes.js) should be correct by default
```bash
    {
        path: '/hpm/admin/mm',
        name: 'media.index',
        component: () => import('../js/pages/admin/index/Index.vue'), // or actual path
        meta: {
            title: 'Media Manager',
        },
    {
        path: '/admin/mm/select',
        name: 'media.select',
        component: () => import('../js/pages/admin/select/Select.vue'), // or actual path
        meta: {
            title: 'Media Manager Selection',
        },
```

In the admin.js (or whereever) include the mediamanager-routes dynamically:
```bash
...
import mediaRoutes from '../../vendor/itstudioat/mediamanager/resources/routes/routes'
...
const routes = [
    { path: '/admin', component: Index },
    ...mediaRoutes,
];

```

Import the css-file into your resources/css/app.js (or admin.js), then you can use classes like *.mm-bg-folder*
```bash
    ...
    import '../../../vendor/itstudioat/mediamanager/resources/css/mediamanager.css';
    ...
```

Import the themes-file into your resources/plugins/admin.js
```bash
    ...
    import {
    mediamanagerLightTheme,
    mediamanagerDarkTheme,
    } from '../../vendor/itstudioat/mediamanager/resources/plugins/mediamanager.js'
    ...


const lightTheme = {
    dark: false,
    colors: {
        ...
        // For errors, alerts
        error: '#E53935',           // red.darken1

        ...mediamanagerLightTheme.colors,
    },


const darkTheme = {
    dark: true,
    colors: {
        ...
        // For errors, alerts
        error: '#EF5350',           // red.lighten2
        ...mediamanagerDarkTheme.colors,
    },
```



## Needed Packages (installed by default)
### getID3() by James Heinrich
```bash
composer require james-heinrich/getid3
```

### laravel-chunk-upload
```bash
composer require pion/laravel-chunk-upload
```

### Spatie Image
```bash
composer require spatie/image
```

## Vue Filepond
```bash
npm install vue-filepond filepond
npm install filepond-plugin-file-validate-type
npm install filepond-plugin-image-preview
```



## Usage

Call the MadiaManager in this example with the path: hpm/admin/mm.

### Selecting a file with the MediaManager
Import the Component Select.vue
```bash
import Select from "../select/Select.vue";

components: { Select },
```

Call this component, maybe with something like this:
```bash
 <Select v-if="is_select" @abort="selectAbort" @takeIt="selectTakeIt" />
```

You will get the event __@abort__, if the user press abort or __@takeIt__ if the user selects a file.
The selectTakeIt looks like something like this:
```bash
        selectTakeIt(data) {
            console.log(data); // shows path and filename
            this.is_select = false;
        },
```





## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Guenther Kron](https://github.com/itstudioat)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
