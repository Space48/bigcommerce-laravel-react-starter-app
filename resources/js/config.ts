import {
  AccountLoggedOut,
  ErrorPage,
  FourOhFour,
  Default,
  InstallationSuccess,
  WelcomeNewUser,
  Dashboard,
} from "./pages";

export const routes = [
  {path: '/', component: Default},
  {path: '/account/loggedout', component: AccountLoggedOut},
  {path: '/error', component: ErrorPage},
  {path: '/stores/:store_hash', component: Dashboard},
  {path: '/stores/:store_hash/installed', component: InstallationSuccess},
  {path: '/stores/:store_hash/welcome', component: WelcomeNewUser},
  {path: '/stores/:store_hash/*', component: FourOhFour},
  {path: '*', component: FourOhFour},
];
