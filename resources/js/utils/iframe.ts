export const isInIframe = () => {
  return window !== window.parent;
}

export const openAppInNewWindow = () => {
  const url = window.location.protocol + '//' + window.location.host + '/bc/load' + window.location.search;
  window.open(url, '_blank');
};
