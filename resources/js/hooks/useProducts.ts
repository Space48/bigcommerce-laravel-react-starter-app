import useSWR from 'swr';
import {useLogoutOn401Response} from './useLogoutOn401Response';

export const useProducts = (store_hash, queryParams) => {
  const params = new URLSearchParams(queryParams);
  const {data, error} = useSWR(
    `/bc-api/stores/${store_hash}/v3/catalog/products?${params.toString()}`,
    {
      revalidateOnFocus: false,
    }
  );

  const isPending = (!error && !data);
  useLogoutOn401Response(error?.response?.status, store_hash);
  return [data?.data?.data, data?.data?.meta, error, isPending];
};
