import axios from "axios";
import useSWR from "swr";
import {useLogoutOn401Response} from "./useLogoutOn401Response";

export const useStore = (store_hash: string | null = null) => {
  if (store_hash === null) {
    return [null, null, false] as const;
  }

  const fetcher = url => axios.get(url).then(res => res.data)
  const {data, error} = useSWR(`/api/stores/${store_hash}`, fetcher, {
    revalidateOnFocus: false,
    revalidateOnReconnect: false
  });
  const isPending = !error && !data;

  useLogoutOn401Response(error?.response?.status, store_hash);

  return [data?.data, error, isPending] as const;
};
