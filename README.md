# This is my package mediamanager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/itstudioat/mediamanager.svg?style=flat-square)](https://packagist.org/packages/itstudioat/mediamanager)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/itstudioat/mediamanager/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/itstudioat/mediamanager/actions?query=workflow%3Arun-tests+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/itstudioat/mediamanager.svg?style=flat-square)](https://packagist.org/packages/itstudioat/mediamanager)




## Installation

You can install the package via composer:

```bash
composer require itstudioat/mediamanager
```

Now publish the files to the folder vendor/mediamanager:

```bash
php artisan vendor:publish --tag=mediamanager-all
```

Check in vendor/mediamanager/routes/routes.js the correct routes
```bash
    {
        path: '/hpm/admin/mm',
        name: 'media.index',
        component: () => import('../../../../vendor/itstudioat/mediamanager/resources/js/pages/admin/index/Index.vue'), // or actual path
        meta: {
            title: 'Media Manager',
        },
    },
```

Include the mediamanager-routes dynamically:
```bash
...
import mediaRoutes from '../../mediamanager/routes/routes'
...
const routes = [
    { path: '/hpm/admin', component: Index },
    ...mediaRoutes,
];

```

Adapt your app.js-file and include the mediamanager.css and mediamanager.js-files:
```bash
...
import '../../../mediamanager/css/mediamanager.css';
...
import mediamanager from "../../../mediamanager/plugins/mediamanager.js";
...
const pinia = createPinia();
const app = createApp(App).use(vuetify).use(mediamanager).use(pinia).use(router);
app.mount('#app');
```


## Needed Packages
### getID3() by James Heinrich
composer require james-heinrich/getid3

### Spatie Image
composer require spatie/image


## Usage


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
