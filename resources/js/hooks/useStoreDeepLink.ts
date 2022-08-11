import {useContext} from 'react';
import ConfigContext from '../context/ConfigContext';
import {ContextType} from '../types';

export const useStoreDeepLink = store_hash => {
  const context = useContext<ContextType|undefined>(ConfigContext);

  if (!store_hash || context === undefined) return null;

  return `https://store-${store_hash}.mybigcommerce.com/manage/app/${context.appId}`;
}
