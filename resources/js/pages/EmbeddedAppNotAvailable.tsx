import React, {useContext} from 'react';
import {Button, Message, Text} from '@bigcommerce/big-design';
import {PageBody, PageHeader} from '../components';
import ConfigContext from '../context/ConfigContext';
import {openAppInNewWindow} from '../utils';

const EmbeddedAppNotAvailable = () => {
  const context = useContext(ConfigContext);

  return (
    <>
      <PageHeader title={context?.appName}>
        <Text>{context?.appDescription}</Text>
      </PageHeader>
      <PageBody>
        <Message
          header="Just one more step."
          type="error"
          messages={[{text: 'This browser does not support loading apps within the BigCommerce admin.'}]}
          marginVertical="xxLarge"
        />
        <Button onClick={openAppInNewWindow}>Open app in a new tab</Button>
      </PageBody>
    </>
  );
}

export default EmbeddedAppNotAvailable;
