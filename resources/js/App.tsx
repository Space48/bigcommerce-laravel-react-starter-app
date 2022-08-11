import React from 'react';
import styled from 'styled-components';
import {BrowserRouter, Route} from 'react-router-dom';
import {AlertsManager, Box, GlobalStyles} from '@bigcommerce/big-design';
import {SWRConfig} from 'swr'
import axios from 'axios';
import ConfigProvider from './context/ConfigProvider';
import {EmbeddedAppNotAvailable} from './pages';
import {useBigCommerceSDK, useSupportsThirdPartyCookies} from './hooks';
import {isInIframe, alertsManager} from './utils';
import {AnimatedSwitch} from './components';
import {routes} from './config'

const AppContainer = styled(Box)`
  max-width: 1200px;
  margin: 0 auto;
  position: relative;
`;

const App = () => {
  useBigCommerceSDK();
  const isThirdPartyCookiesSupported = useSupportsThirdPartyCookies();
  const unsupportedEmbeddedApp = isInIframe() && !isThirdPartyCookiesSupported;

  return (
    <ConfigProvider>
      <BrowserRouter>
        <SWRConfig
          value={{fetcher: url => axios.get(url).then(res => res.data)}}
        >
          <GlobalStyles/>
          <AlertsManager manager={alertsManager}/>

          <AppContainer>
            {
              unsupportedEmbeddedApp ?
                <EmbeddedAppNotAvailable/>
                :
                <AnimatedSwitch>
                  {routes.map(({path, component: Component}) => (
                    <Route key={path} exact path={path}>
                      <div className="page">
                        <Component/>
                      </div>
                    </Route>
                  ))}
                </AnimatedSwitch>
            }
          </AppContainer>
        </SWRConfig>
      </BrowserRouter>
    </ConfigProvider>
  )
}

export default App;
