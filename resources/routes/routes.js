// resources/js/routes.js (inside your package)
export default [
    {
        path: '/hpm/admin/mm',
        name: 'media.index',
        component: () => import('../../../../vendor/itstudioat/mediamanager/resources/js/pages/admin/index/Index.vue'), // or actual path
        meta: {
            title: 'Media Manager',
        },
    },
]
