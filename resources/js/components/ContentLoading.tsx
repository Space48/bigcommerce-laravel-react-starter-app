import React from 'react';
import {Message,} from "@bigcommerce/big-design";
import Spinner from "./Spinner";

interface Props {
  loading: boolean,
  error: string | null,
  children: React.ReactNode,
}

const ContentLoading = ({loading, error, children}: Props) => {
  const loadingContent = loading ? <Spinner/> : null;
  const errorContent = !loading && error ?
    <Message type="error" messages={[{text: error}]} marginVertical="medium"/> : null;
  const pageContent = !loading && !error ? children : null;

  return (
    <>
      {loadingContent}
      {errorContent}
      {pageContent}
    </>
  )
}

export default ContentLoading;
