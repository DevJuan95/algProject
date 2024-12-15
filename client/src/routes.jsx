// routes.jsx
import {
    createRootRoute,
    createRootRouteWithContext,
    createRoute,
    createRouter,
    Outlet,
    redirect
} from '@tanstack/react-router';
import AppShellLayout from "./components/layouts/AppShellLayout.jsx";
import {HomePage} from "./pages/Home.page.jsx";
import {ComprasPage} from "./pages/Compras.page.jsx";
import {BodegaPage} from "./pages/Bodega.page.jsx";
import {RecetasPage} from "./pages/Recetas.page.jsx";
import {OrdenesPage} from "./pages/Ordenes.page.jsx";
import {OrdenPage} from "./pages/Orden.page.jsx";
import LoginPage from "./pages/Login.page.jsx";

const rootRoute = createRootRouteWithContext()({
    component: () => <Outlet/>,
});

const layoutRoute = createRoute({
    getParentRoute: () => rootRoute,
    id: 'layout',
    beforeLoad: ({context}) => {
        if (!localStorage.getItem('token')) {
            throw redirect({to: '/login'});
        }
    },
    component: AppShellLayout,
});

const indexRoute = createRoute({
    getParentRoute: () => layoutRoute,
    path: '/',
    component: HomePage,
});

const bodegaRoute = createRootRoute({
    getParentRoute: () => layoutRoute,
    path: '/bodega',
    component: BodegaPage,
});

const comprasRoute = createRoute({
    getParentRoute: () => layoutRoute,
    path: '/compras',
    component: ComprasPage,
});
const recetasRoute = createRoute({
    getParentRoute: () => layoutRoute,
    path: '/recetas',
    component: RecetasPage,
});

const ordenesRoute = createRoute({
    getParentRoute: () => layoutRoute,
    path: '/Ordenes',
    component: OrdenesPage,
});

const loginRoute = createRoute({
    getParentRoute: () => rootRoute,
    path: '/login',
    component: LoginPage,
});

export const ordenRoute = createRoute({
    getParentRoute: () => layoutRoute,
    path: '/orden/$orderId',
    component: OrdenPage,
});
const routeTree = rootRoute.addChildren([
    layoutRoute.addChildren([
        indexRoute,
        bodegaRoute,
        comprasRoute,
        recetasRoute,
        ordenesRoute,
        ordenRoute,
    ]),
    loginRoute
]);

export const router = createRouter({routeTree});