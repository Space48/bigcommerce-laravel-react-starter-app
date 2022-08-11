import React from 'react';
import {Message, Text} from '@bigcommerce/big-design';
import {PageBody, PageHeader} from '../components';
import {useCookies} from 'react-cookie';

const ErrorPage = () => {
  const [cookies] = useCookies(['last_error']);
  const errorMessage = cookies.last_error ?? 'Oops. Something went wrong. Please wait and try again.';

  return (
    <>
      <PageHeader title="Something went wrong">
        <Text>Unfortunately, we have run into a problem.</Text>
      </PageHeader>
      <PageBody>
        <Message
          type="error"
          messages={[{text: errorMessage}]}
          marginVertical="medium"/>
      </PageBody>
    </>
  );
}

export default ErrorPage;
