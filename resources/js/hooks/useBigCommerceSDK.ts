import {useScript} from "./useScript";

export const useBigCommerceSDK = () => {

  const status = useScript('//cdn.bigcommerce.com/jssdk/bc-sdk.js');

  return [status];
}
