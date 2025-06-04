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
```bash
composer require james-heinrich/getid3
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


##vite.config.js
```bash
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify';
import path from 'path';

export default defineConfig({
    root: '.',
    server: {
        host: 'localhost',
        port: 5173,
        strictPort: true,
        hmr: {
            host: 'localhost',
        },
    },


    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),  // Resolves the @ to resources/js
            '@mediamanager': path.resolve(__dirname, 'resources/vendor/mediamanager/js'),
        },
        dedupe: ['vuetify'],
    },

    plugins: [
        laravel({
            input: [
                'resources/js/apps/homepage.js',
                'resources/js/apps/admin.js',
                'resources/js/apps/application.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        vuetify({
            autoImport: true, // ✅ Das verhindert die Fehlerhaften Importe wie vuetify/components/VDialog
        }),
    ],

    build: {
        sourcemap: true,
        rollupOptions: {
            output: {
                manualChunks(id) {
                    // Falls du hier Custom Chunks brauchst, sonst entfernen
                },
            },
        },
        chunkSizeWarningLimit: 500,
    }
});
```





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
