export const useLogoutOn401Response = (status, store_hash: string | null = null) => {
  let loggedOutUrl: string = `/account/loggedout`;
  if (store_hash !== null) {
    loggedOutUrl = loggedOutUrl + `?store_hash=${store_hash}`;
  }

  const currentUrl = window.location.pathname + window.location.search;
  if (status === 401 && currentUrl !== loggedOutUrl) {
    window.location.href = loggedOutUrl;
  }
}
