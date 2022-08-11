import {
  AccountLoggedOut,
  ErrorPage,
  FourOhFour,
  Home,
  InstallationSuccess,
  WelcomeNewUser,
} from "./pages";

export const routes = [
  {path: '/', component: Home},
  {path: '/account/loggedout', component: AccountLoggedOut},
  {path: '/error', component: ErrorPage},
  {path: '/stores/:store_hash', component: Home},
  {path: '/stores/:store_hash/installed', component: InstallationSuccess},
  {path: '/stores/:store_hash/welcome', component: WelcomeNewUser},
  {path: '/stores/:store_hash/*', component: FourOhFour},
  {path: '*', component: FourOhFour},
];
