import React, {useContext, useEffect} from 'react';
import {Message, Text} from '@bigcommerce/big-design';
import {PageBody, PageHeader} from '../../components';
import ConfigContext from '../../context/ConfigContext';
import {useQueryParams, useStore, useStoreDeepLink} from '../../hooks';
import {useHistory} from 'react-router-dom';

const AccountLoggedOut = () => {
  const context = useContext(ConfigContext);
  const params = useQueryParams();
  const storeHash = params.get('store_hash');
  const storeDeepLink = useStoreDeepLink(storeHash);
  const [store] = useStore(storeHash);
  const history = useHistory()

  useEffect(() => {
    if (!store) return;
    history.push(`/stores/${storeHash}`);
  }, [store])

  return (
    <>
      <PageHeader title={context?.appName}>
        <Text>{context?.appDescription}</Text>
      </PageHeader>
      <PageBody>
        <Message
          type="error"
          messages={[
            {
              text: 'You have been logged out.',
              link: {text: 'Log back in', href: storeDeepLink ?? 'https://login.bigcommerce.com/'},
            }
          ]}
          marginVertical="medium"/>
      </PageBody>
    </>
  );
}

export default AccountLoggedOut;
