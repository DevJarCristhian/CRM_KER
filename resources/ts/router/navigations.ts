export default [
    {
        label: "Dashboard",
        key: "home",
        href: "/",
        i: "home",
    },
    {
        label: "Personas",
        key: "persons",
        i: "people",
        children: [
            {
                label: "Dependientes",
                key: "dependent",
                href: "/dependent",
            },
            // {
            //     label: "Doctores",
            //     key: "doctors",
            //     href: "/doctors",
            // },
        ],
    },
    {
        label: "Acessos",
        key: "access",
        i: "users",
        children: [
            {
                label: "Usuarios",
                key: "users",
                href: "/users",
            },
            {
                label: "Roles",
                key: "roles",
                href: "/roles",
            },
        ],
    },
    {
        label: "Ajustes",
        key: "settings",
        href: "/settings",
        i: "settings",
    },
];
// {
//   title: 'Dashboard',
//   to: { name: '/' },
//   icon: { icon: 'dashboard' },
// },
