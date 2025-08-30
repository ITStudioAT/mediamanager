// resources/js/routes.js (inside your package)
export default [
    {
        path: '/admin/mm',
        name: 'media.index',
        component: () => import('../js/pages/admin/index/Index.vue'), // or actual path
        meta: {
            title: 'Media Manager',
        },
    },

    {
        path: '/admin/mm/select',
        name: 'media.select',
        component: () => import('../js/pages/admin/select/Select.vue'), // or actual path
        meta: {
            title: 'Media Manager Selection',
        },
    },
]
